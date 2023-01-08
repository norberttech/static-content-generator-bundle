<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Content;

interface SourceProviderFilter
{
    /**
     * @param Source[] $sources
     *
     * @psalm-param array<Source> $sources
     *
     * @return Source[]
     *
     * @psalm-return array<Source>
     */
    public function filter(array $sources = []) : array;
}
