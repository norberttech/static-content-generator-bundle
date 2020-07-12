<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Assets\Writer;

use NorbertTech\StaticContentGeneratorBundle\Assets\Asset;
use NorbertTech\StaticContentGeneratorBundle\Assets\Assets;
use Symfony\Component\Filesystem\Filesystem;

final class FilesystemAssets implements Assets
{
    private Filesystem $filesystem;

    private string $outputDirectory;

    public function __construct(Filesystem $filesystem, string $outputDirectory)
    {
        $this->filesystem = $filesystem;
        $this->outputDirectory = $outputDirectory;
    }

    public function copy(Asset $asset) : void
    {
        $this->filesystem->copy($asset->absolutePath(), $this->outputDirectory . DIRECTORY_SEPARATOR . $asset->relativePath(), true);
    }
}
