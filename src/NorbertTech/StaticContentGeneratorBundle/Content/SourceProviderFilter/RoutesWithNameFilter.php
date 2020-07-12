<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Content\SourceProviderFilter;

use NorbertTech\StaticContentGeneratorBundle\Content\Source;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProviderFilter;

final class RoutesWithNameFilter implements SourceProviderFilter
{
    /**
     * @var string[]
     * @psalm-var array<string>
     */
    private array $routeNames;

    /**
     * @param string[] $routeNames
     */
    public function __construct(array $routeNames)
    {
        $this->routeNames = $routeNames;
    }

    public function filter(array $sources = []) : array
    {
        return \array_filter(
            $sources,
            function (Source $source) : bool {
                return \in_array($source->routerName(), $this->routeNames, true);
            }
        );
    }
}
