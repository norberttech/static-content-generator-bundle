<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

final class StaticContentGeneratorExtension extends Extension
{
    /**
     * @param mixed[] $configs
     */
    public function load(array $configs, ContainerBuilder $container) : void
    {
        $loader = new PhpFileLoader($container, new FileLocator(\dirname(__DIR__) . '/Resources/config'));

        $loader->load('static_content_generator.php');

        $configuration = $this->getConfiguration($configs, $container);

        if ($configuration instanceof ConfigurationInterface) {
            $config = $this->processConfiguration($configuration, $configs);

            $container->setParameter('static_content_generator.output_directory', $config['output_directory']);
        }
    }
}
