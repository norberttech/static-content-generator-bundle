<?php declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Command;

use Aeon\Calendar\Stopwatch;
use NorbertTech\StaticContentGeneratorBundle\Content\Content;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProvider;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProviderFilter\ChainFilter;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProviderFilter\RouteNamesWithoutPrefixFilter;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProviderFilter\RouteNamesWithPrefixFilter;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProviderFilter\RoutesWithNameFilter;
use NorbertTech\StaticContentGeneratorBundle\Content\Transformer;
use NorbertTech\StaticContentGeneratorBundle\Content\Writer;
use NorbertTech\StaticContentGeneratorBundle\StaticContent;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateRoutesCommand extends Command
{
    public const NAME = 'static-content-generator:generate:routes';

    protected static $defaultName = self::NAME;

    private SourceProvider $sourceProvider;

    private Transformer $generator;

    private Writer $writer;

    public function __construct(SourceProvider $sourceProvider, Transformer $generator, Writer $writer)
    {
        parent::__construct(self::NAME);
        $this->sourceProvider = $sourceProvider;
        $this->generator = $generator;
        $this->writer = $writer;
    }

    protected function configure() : void
    {
        $this
            ->setDescription('Transform routes into static content and dump them into the output location')
            ->addOption('clean', null, InputOption::VALUE_OPTIONAL, 'Cleanup output location before dumping new content', false)
            ->addOption('filter-route', 'r', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Filter out all routes except those with given name')
            ->addOption('filter-route-prefix', 'rp', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Filter out all routes except those with given name prefix');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Generate routes static content');

        $generator = new StaticContent(
            $this->generator,
            $this->writer,
        );

        $clean = ($input->getOption('clean') !== false);

        if ($clean) {
            if ($output->isVerbose()) {
                $io->note('Cleaning output location...');
            }

            $this->writer->clean();
        }

        $sourcesFilter = new ChainFilter(
            new RouteNamesWithoutPrefixFilter($prefixes = ['_'])
        );

        if (\count($input->getOption('filter-route'))) {
            $sourcesFilter->addFilter(new RoutesWithNameFilter($input->getOption('filter-route')));
        }

        if (\count($input->getOption('filter-route-prefix'))) {
            $sourcesFilter->addFilter(new RouteNamesWithPrefixFilter($input->getOption('filter-route-prefix')));
        }

        $sources = $sourcesFilter->filter($this->sourceProvider->all());

        if (!\count($sources)) {
            $io->note('There are no sources that could be transformed into static content');

            return 0;
        }

        $progress = $io->createProgressBar(\count($sources));

        $stopwatch = new Stopwatch();
        $stopwatch->start();

        $generator->dump(
            $sources,
            function (Content $content) use ($progress, $output, $io) : void {
                if ($output->isVerbose()) {
                    $io->note('Generated content: ' . $content->path());
                }

                $progress->advance();
            }
        );

        $stopwatch->stop();

        $progress->finish();

        $io->newLine(2);

        $io->success('Static content generated');
        $io->writeln(\sprintf('Generation time: %s seconds', $stopwatch->totalElapsedTime()->inSecondsPrecise()));

        return 0;
    }
}
