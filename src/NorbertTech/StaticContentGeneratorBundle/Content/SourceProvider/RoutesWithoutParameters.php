<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Content\SourceProvider;

use NorbertTech\StaticContentGeneratorBundle\Content\Source;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProvider;
use NorbertTech\StaticContentGeneratorBundle\Route\Filter\AllWithoutParameters;
use NorbertTech\StaticContentGeneratorBundle\Route\Iterator;

final class RoutesWithoutParameters implements SourceProvider
{
    /**
     * @var Iterator
     */
    private Iterator $iterator;

    public function __construct(Iterator $iterator)
    {
        $this->iterator = $iterator;
    }

    public function all() : array
    {
        $routes = $this->iterator->iterate(new AllWithoutParameters());

        return \array_map(
            function (string $name) {
                return new Source($name, []);
            },
            \array_keys($routes),
        );
    }

    public function count() : int
    {
        return \count($this->all());
    }
}
