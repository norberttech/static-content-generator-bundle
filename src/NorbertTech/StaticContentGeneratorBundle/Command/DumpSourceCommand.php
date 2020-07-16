<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Command;

use NorbertTech\StaticContentGeneratorBundle\Content\Content;
use NorbertTech\StaticContentGeneratorBundle\Content\Source;
use NorbertTech\StaticContentGeneratorBundle\Content\Transformer;
use NorbertTech\StaticContentGeneratorBundle\Content\Writer;
use NorbertTech\StaticContentGeneratorBundle\StaticContent;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class DumpSourceCommand extends Command
{
    public const NAME = 'static-content-generator:dump:source';

    protected static $defaultName = self::NAME;

    private Transformer $generator;

    private Writer $writer;

    public function __construct(Transformer $generator, Writer $writer)
    {
        parent::__construct(self::NAME);
        $this->generator = $generator;
        $this->writer = $writer;
    }

    protected function configure() : void
    {
        $this
            ->setDescription('Dump source into a static content')
            ->addArgument('source', InputArgument::REQUIRED, 'Serialized and base64 encoded sourcex content');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io = new SymfonyStyle($input, $output);

        $generator = new StaticContent(
            $this->generator,
            $this->writer,
        );

        $generator->dump(
            Source::hydrate(\json_decode(\base64_decode($input->getArgument('source'), true), true)),
            function (Content $content) use ($output, $io) : void {
                if ($output->isVerbose()) {
                    $io->note('Generated content: ' . $content->path());
                }
            }
        );

        return 0;
    }
}
