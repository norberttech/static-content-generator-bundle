<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Route;

use Symfony\Component\Routing\Route;

interface Filter
{
    /**
     * @param array<string, Route> $routes
     *
     * @return array<string, Route>
     */
    public function filter(array $routes) : array;
}
