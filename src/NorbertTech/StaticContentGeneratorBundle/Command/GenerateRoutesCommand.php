<?php declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Command;

use Aeon\Calendar\Stopwatch;
use NorbertTech\StaticContentGeneratorBundle\Content\Source;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProvider;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProviderFilter\ChainFilter;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProviderFilter\RoutesWithNameFilter;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProviderFilter\RoutesWithNamePrefixFilter;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProviderFilter\RoutesWithoutNameFilter;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProviderFilter\RoutesWithoutNamePrefixFilter;
use NorbertTech\StaticContentGeneratorBundle\Content\Writer;
use NorbertTech\SymfonyProcessExecutor\AsynchronousExecutor;
use NorbertTech\SymfonyProcessExecutor\ProcessPool;
use NorbertTech\SymfonyProcessExecutor\ProcessWrapper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

class GenerateRoutesCommand extends Command
{
    public const NAME = 'static-content-generator:generate:routes';

    protected static $defaultName = self::NAME;

    private SourceProvider $sourceProvider;

    private Writer $writer;

    public function __construct(SourceProvider $sourceProvider, Writer $writer)
    {
        parent::__construct(self::NAME);
        $this->sourceProvider = $sourceProvider;
        $this->writer = $writer;
    }

    protected function configure() : void
    {
        $this
            ->setDescription('Transform routes into static content and dump them into the output location')
            ->addOption('clean', null, InputOption::VALUE_OPTIONAL, 'Cleanup output location before dumping new content', false)
            ->addOption('filter-route', 'r', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Filter out all routes except those with given name')
            ->addOption('filter-route-prefix', 'rp', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Filter out all routes except those with given name prefix')
            ->addOption('exclude-route', 'ex', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Exclude routes with given name')
            ->addOption('exclude-route-prefix', 'exp', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Exclude routes that starts with given name prefix')
            ->addOption('parallel', 'p', InputOption::VALUE_OPTIONAL, 'How many process to launch in parallel', '1')
            ->addOption('cli', 'c', InputOption::VALUE_OPTIONAL, 'Path to Symfony CLI application entry', $_SERVER['SCRIPT_NAME']);
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Generate routes static content');

        $clean = ($input->getOption('clean') !== false);

        if ($clean) {
            if ($output->isVerbose()) {
                $io->note('Cleaning output location...');
            }

            $this->writer->clean();
        }

        $sourcesFilter = new ChainFilter(
            new RoutesWithoutNamePrefixFilter($prefixes = ['_'])
        );

        if (\count($input->getOption('filter-route'))) {
            $sourcesFilter->addFilter(new RoutesWithNameFilter($input->getOption('filter-route')));
        }

        if (\count($input->getOption('filter-route-prefix'))) {
            $sourcesFilter->addFilter(new RoutesWithNamePrefixFilter($input->getOption('filter-route-prefix')));
        }

        if (\count($input->getOption('exclude-route'))) {
            $sourcesFilter->addFilter(new RoutesWithoutNameFilter($input->getOption('exclude-route')));
        }

        if (\count($input->getOption('exclude-route-prefix'))) {
            $sourcesFilter->addFilter(new RoutesWithoutNamePrefixFilter($input->getOption('exclude-route-prefix')));
        }

        if ((int) $input->getOption('parallel') <= 0) {
            $io->error('Parallel option must be greater or equal 1');

            return 1;
        }

        $sources = $sourcesFilter->filter($this->sourceProvider->all());

        if (!\count($sources)) {
            $io->note('There are no sources that could be transformed into static content');

            return 0;
        }

        $progress = $io->createProgressBar(\count($sources));

        $stopwatch = new Stopwatch();
        $stopwatch->start();

        $chunks = \array_chunk($sources, (int) $input->getOption('parallel'));

        foreach ($chunks as $chunk) {
            $processes = new ProcessPool(
                ...\array_map(
                    function (Source $source) use ($input) : Process {
                        return new Process([
                            $input->getOption('cli'),
                            DumpSourceCommand::NAME,
                            \base64_encode(\json_encode($source->serialize())),
                            '--env=' . $input->getOption('env'),
                        ]);
                    },
                    $chunk
                )
            );

            if ($io->isVerbose()) {
                $io->note('Starting ' . \count($chunks) . ' processes...');
            }

            $executor = new AsynchronousExecutor($processes);

            $executor->execute();
            $executor->waitForAllToFinish();

            if ($executor->pool()->withFailureExitCode() > 0) {
                $executor->pool()->each(function (ProcessWrapper $processWrapper) use ($io) : void {
                    if ($processWrapper->exitCode() !== 0) {
                        $io->writeln('Process "' . $processWrapper->process()->getCommandLine() . '" failed');
                        $io->error($processWrapper->process()->getErrorOutput());
                    }
                });

                return 1;
            }

            if ($io->isVerbose()) {
                $io->note('Finished ' . \count($chunks) . ' processes in ' . $executor->executionTime()->inSecondsPrecise() . ' seconds');
            }

            $progress->advance(\count($chunk));
        }

        $stopwatch->stop();

        $progress->finish();

        $io->newLine(2);

        $io->success('Static content generated');
        $io->writeln(\sprintf('Generation time: %s seconds', $stopwatch->totalElapsedTime()->inSecondsPrecise()));

        return 0;
    }
}
