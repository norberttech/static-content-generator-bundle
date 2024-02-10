<?php

declare(strict_types=1);

namespace FixtureProject;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function getProjectDir() : string
    {
        return \realpath(__DIR__ . '/../');
    }
}
