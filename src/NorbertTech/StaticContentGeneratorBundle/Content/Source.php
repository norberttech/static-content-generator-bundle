<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Content;

final class Source
{
    private string $routerName;

    /**
     * @var string[]
     */
    private array $parameters;

    /**
     * @param string $routerName
     * @param string[] $parameters
     */
    public function __construct(string $routerName, array $parameters = [])
    {
        $this->routerName = $routerName;
        $this->parameters = $parameters;
    }

    public function routerName() : string
    {
        return $this->routerName;
    }

    /**
     * @return string[]
     */
    public function parameters() : array
    {
        return $this->parameters;
    }
}
