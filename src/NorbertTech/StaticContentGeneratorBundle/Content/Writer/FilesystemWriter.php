<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Content\Writer;

use NorbertTech\StaticContentGeneratorBundle\Content\Content;
use NorbertTech\StaticContentGeneratorBundle\Content\OutputPathResolver;
use NorbertTech\StaticContentGeneratorBundle\Content\Writer;
use Symfony\Component\Filesystem\Filesystem;

final class FilesystemWriter implements Writer
{
    private Filesystem $filesystem;

    private OutputPathResolver $outputPathResolver;

    public function __construct(Filesystem $filesystem, OutputPathResolver $outputPathResolver)
    {
        $this->filesystem = $filesystem;
        $this->outputPathResolver = $outputPathResolver;
    }

    public function clean() : void
    {
        $this->filesystem->remove($this->outputPathResolver->outputLocation());
    }

    public function write(Content $content) : void
    {
        $this->filesystem->dumpFile(
            $this->outputPathResolver->resolve($content),
            $content->content()
        );
    }
}
