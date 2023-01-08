<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Route\Filter;

use NorbertTech\StaticContentGeneratorBundle\Route\Filter;
use Symfony\Component\Routing\Route;

final class AllWithoutParameters implements Filter
{
    /**
     * @param Route[] $routes
     *
     * @psalm-param array<string, Route> $routes
     *
     * @psalm-return array<string, Route>
     *
     * @return Route[]
     */
    public function filter(array $routes) : array
    {
        $withoutParameters = [];

        foreach ($routes as $name => $route) {
            if (!\preg_match('/\{[^\}]+\}/', $route->getPath(), $matches)) {
                $withoutParameters[$name] = $route;
            }
        }

        return $withoutParameters;
    }
}
