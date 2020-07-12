<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Assets;

final class Asset
{
    private string $publicDirectory;

    private \SplFileInfo $file;

    public function __construct(string $publicDirectory, \SplFileInfo $file)
    {
        $this->publicDirectory = $publicDirectory;
        $this->file = $file;
    }

    public function relativePath() : string
    {
        return \ltrim(\str_replace($this->publicDirectory, '', $this->file->getPathname()), '/');
    }

    public function absolutePath() : string
    {
        return $this->file->getPathname();
    }
}
