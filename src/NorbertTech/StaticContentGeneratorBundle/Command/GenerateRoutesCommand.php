<?php declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Command;

use NorbertTech\StaticContentGeneratorBundle\Content\SourceProvider;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProviderFilter\ChainFilter;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProviderFilter\RouteNamesFilter;
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
            ->addOption('filter-route', 'r', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'Filter out all routes except those with given name');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io = new SymfonyStyle($input, $output);

        $generator = new StaticContent(
            $this->generator,
            $this->writer,
        );

        $clean = ($input->getOption('clean') !== false);

        if ($clean) {
            $io->note('Cleaning output location...');

            $this->writer->clean();
        }

        $sourcesFilter = new ChainFilter();

        if (\count($input->getOption('filter-route'))) {
            $sourcesFilter->addFilter(new RouteNamesFilter($input->getOption('filter-route')));
        }

        $sources = $sourcesFilter->filter($this->sourceProvider->all());

        if (!\count($sources)) {
            $io->note('There are no sources that could be transformed into static content');

            return 0;
        }

        $io->note('Generating static content...');

        $progress = $io->createProgressBar(\count($sources));

        $generator->dump(
            $sources,
            function () use ($progress) : void {
                $progress->advance();
            }
        );

        $progress->finish();

        $io->newLine(2);

        $io->success('Static content generated');

        return 0;
    }
}
