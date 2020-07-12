<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Content\SourceProvider;

use NorbertTech\StaticContentGeneratorBundle\Content\Source;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProvider;

final class ProvidersCollection implements SourceProvider
{
    /**
     * @var SourceProvider[]
     */
    private array $providers;

    /**
     * @psalm-param array<SourceProvider> $providers
     *
     * @param SourceProvider[] $providers
     */
    public function __construct(array $providers = [])
    {
        $this->providers = $providers;
    }

    public function addProvider(SourceProvider $provider) : void
    {
        $this->providers[] = $provider;
    }

    /**
     * @return Source[]
     * @psalm-return array<Source>
     */
    public function all() : array
    {
        return \array_merge(
            ...\array_map(
                function (SourceProvider $provider) : array {
                    return $provider->all();
                },
                $this->providers
            )
        );
    }
}
