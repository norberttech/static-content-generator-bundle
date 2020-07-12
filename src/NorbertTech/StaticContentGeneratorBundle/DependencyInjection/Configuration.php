<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('static_content_generator');

        // @phpstan-ignore-next-line
        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('output_directory')
                    ->defaultValue('%kernel.project_dir%/output')
                ->end()
            ->end();

        return $treeBuilder;
    }
}
