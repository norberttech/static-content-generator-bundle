<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Content\OutputPathResolver;

use NorbertTech\StaticContentGeneratorBundle\Content\Content;
use NorbertTech\StaticContentGeneratorBundle\Content\OutputPathResolver;

final class IndexHTML implements OutputPathResolver
{
    private string $outputDirectory;

    public function __construct(string $outputDirectory)
    {
        $this->outputDirectory = $outputDirectory;
    }

    public function outputLocation() : string
    {
        return $this->outputDirectory;
    }

    public function resolve(Content $content) : string
    {
        if (\pathinfo($content->path(), PATHINFO_EXTENSION)) {
            return \rtrim($this->outputDirectory, '/') . DIRECTORY_SEPARATOR . \ltrim($content->path(), '/');
        }

        return \rtrim($this->outputDirectory . '/') . DIRECTORY_SEPARATOR . \ltrim($content->path(), '/') . DIRECTORY_SEPARATOR . 'index.html';
    }
}
