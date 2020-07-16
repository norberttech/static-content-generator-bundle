<?php declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use NorbertTech\StaticContentGeneratorBundle\Assets\Writer\FilesystemAssets;
use NorbertTech\StaticContentGeneratorBundle\Command\CopyAssetsCommand;
use NorbertTech\StaticContentGeneratorBundle\Command\DumpSourceCommand;
use NorbertTech\StaticContentGeneratorBundle\Command\GenerateRoutesCommand;
use NorbertTech\StaticContentGeneratorBundle\Content\OutputPathResolver\IndexHTML;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProvider\ProvidersCollection;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProvider\RoutesWithoutParameters;
use NorbertTech\StaticContentGeneratorBundle\Content\Transformer\HttpKernelTransformer;
use NorbertTech\StaticContentGeneratorBundle\Content\Writer\FilesystemWriter;
use NorbertTech\StaticContentGeneratorBundle\Route\Iterator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Filesystem\Filesystem;

return static function (ContainerConfigurator $container) : void {
    $container->services()
        ->set(GenerateRoutesCommand::class)
        ->tag('console.command', ['command' => GenerateRoutesCommand::NAME])
        ->args([
            new Reference('static_content_generator.source_provider'),
            new Reference('static_content_generator.writer'),
        ])

        ->set(DumpSourceCommand::class)
        ->tag('console.command', ['command' => DumpSourceCommand::NAME])
        ->args([
            new Reference('static_content_generator.transformer'),
            new Reference('static_content_generator.writer'),
        ])

        ->set(CopyAssetsCommand::class)
        ->tag('console.command', ['command' => CopyAssetsCommand::NAME])
        ->args([
            '%kernel.project_dir%',
            new Reference('static_content_generator.assets'),
        ])

        ->set('static_content_generator.transformer', HttpKernelTransformer::class)
        ->args([
            new Reference('kernel'),
            new Reference('router'),
        ])

        ->set('static_content_generator.source_provider', ProvidersCollection::class)
        ->args([
            [
                new Reference(RoutesWithoutParameters::class),
            ],
        ])

        ->set(RoutesWithoutParameters::class)
        ->args([
            new Reference(Iterator::class),
        ])

        ->set('static_content_generator.writer', FilesystemWriter::class)
        ->args([
            new Reference('static_content_generator.filesystem'),
            new Reference('static_content_generator.output_path_resolver'),
        ])

        ->set('static_content_generator.output_path_resolver', IndexHTML::class)
        ->args([
            '%static_content_generator.output_directory%',
        ])

        ->set('static_content_generator.assets', FilesystemAssets::class)
        ->args([
            new Reference('static_content_generator.filesystem'),
            '%static_content_generator.output_directory%',
        ])

        ->set('static_content_generator.filesystem', Filesystem::class)

        ->set(Iterator::class)
        ->args([
            new Reference('router'),
        ]);
};
