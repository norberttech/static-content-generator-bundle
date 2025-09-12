<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Command;

use function Flow\Types\DSL\type_map;
use function Flow\Types\DSL\type_string;
use function Flow\Types\DSL\type_structure;
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
            ->setHidden(true)
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
            Source::hydrate(
                type_structure([
                    'route_name' => type_string(),
                    'parameters' => type_map(type_string(), type_string()),
                ])->assert(
                    \json_decode(
                        type_string()->assert(
                            \base64_decode(
                                type_string()->assert($input->getArgument('source')),
                                true
                            )
                        ),
                        true
                    )
                )
            ),
            function (Content $content) use ($output, $io) : void {
                if ($output->isVerbose()) {
                    $io->note('Generated content: ' . $content->path());
                }
            }
        );

        return 0;
    }
}
