<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Route;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;

final class Iterator
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param Filter $filter
     *
     * @return Route[]
     * @psalm-return array<string, Route>
     */
    public function iterate(Filter $filter) : array
    {
        return $filter->filter($this->router->getRouteCollection()->all());
    }
}
