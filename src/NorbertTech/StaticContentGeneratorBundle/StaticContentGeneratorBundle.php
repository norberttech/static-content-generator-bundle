<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle;

use NorbertTech\StaticContentGeneratorBundle\DependencyInjection\CompilerPass\SourceProviderCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class StaticContentGeneratorBundle extends Bundle
{
    public function build(ContainerBuilder $container) : void
    {
        parent::build($container);

        $container->addCompilerPass(new SourceProviderCompilerPass());
    }
}
