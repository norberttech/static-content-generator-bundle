<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class SourceProviderCompilerPass implements CompilerPassInterface
{
    public const TAG_SOURCE_PROVIDER = 'static_content_generator.source_provider';

    public function process(ContainerBuilder $container) : void
    {
        $sourceProvidersDefinition = $container->getDefinition('static_content_generator.source_provider');

        foreach ($container->findTaggedServiceIds(self::TAG_SOURCE_PROVIDER) as $id => $tags) {
            $sourceProvidersDefinition->addMethodCall('addProvider', [new Reference($id)]);
        }
    }
}
