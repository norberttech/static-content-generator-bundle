<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Content\SourceProviderFilter;

use NorbertTech\StaticContentGeneratorBundle\Content\SourceProviderFilter;

final class ChainFilter implements SourceProviderFilter
{
    /**
     * @var SourceProviderFilter[]
     *
     * @psalm-var array<SourceProviderFilter>
     */
    private array $filters;

    public function __construct(SourceProviderFilter ...$filters)
    {
        $this->filters = $filters;
    }

    public function addFilter(SourceProviderFilter $filter) : void
    {
        $this->filters[] = $filter;
    }

    public function filter(array $sources = []) : array
    {
        $filteredSources = $sources;

        foreach ($this->filters as $filter) {
            $filteredSources = $filter->filter($filteredSources);
        }

        return $filteredSources;
    }
}
