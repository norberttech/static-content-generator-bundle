<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle;

use NorbertTech\StaticContentGeneratorBundle\Content\Source;
use NorbertTech\StaticContentGeneratorBundle\Content\Transformer;
use NorbertTech\StaticContentGeneratorBundle\Content\Writer;

final class StaticContent
{
    private Transformer $transformer;

    private Writer $writer;

    public function __construct(Transformer $generator, Writer $writer)
    {
        $this->transformer = $generator;
        $this->writer = $writer;
    }

    /**
     * @param Source[] $sources
     * @psalm-param array<Source> $sources
     */
    public function dump(array $sources, callable $callback = null) : void
    {
        foreach ($sources as $source) {
            $this->writer->write(
                $content = $this->transformer->transform($source)
            );

            if ($callback) {
                $callback($content);
            }
        }
    }
}
