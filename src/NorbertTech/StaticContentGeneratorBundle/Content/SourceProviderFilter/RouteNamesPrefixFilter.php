<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Content\SourceProviderFilter;

use NorbertTech\StaticContentGeneratorBundle\Content\Source;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProviderFilter;

final class RouteNamesPrefixFilter implements SourceProviderFilter
{
    /**
     * @var string[]
     * @psalm-var array<string>
     */
    private array $routeNamesPrefixes;

    /**
     * @param string[] $routeNamesPrefixes
     */
    public function __construct(array $routeNamesPrefixes)
    {
        $this->routeNamesPrefixes = $routeNamesPrefixes;
    }

    public function filter(array $sources = []) : array
    {
        return \array_filter(
            $sources,
            function (Source $source) : bool {
                foreach ($this->routeNamesPrefixes as $prefix) {
                    if (\strncmp($source->routerName(), $prefix, \strlen($prefix)) === 0) {
                        return false;
                    }
                }

                return true;
            }
        );
    }
}
