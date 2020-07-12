<?php

declare(strict_types=1);

/*
 * This file is part of the Structurizr for PHP.
 *
 * (c) Norbert Orzechowicz <norbert@orzechowicz.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NorbertTech\StaticContentGeneratorBundle\Assets;

final class RecursiveDirectoryIterator
{
    private string $path;

    /**
     * @var string[]
     */
    private array $ignoreExtensions;

    /**
     * @param string[] $ignoredExtensions
     */
    public function __construct(string $path, array $ignoredExtensions = [])
    {
        $this->path = $path;
        $this->ignoreExtensions = $ignoredExtensions;
    }

    /**
     * @param callable(Asset $asset) : void $function
     */
    public function each(callable $function) : void
    {
        foreach ($this->iterate() as $fileInfo) {
            $function($fileInfo);
        }
    }

    /**
     * @return \Generator<Asset>
     */
    private function iterate() : \Generator
    {
        $fileIterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->path)
        );

        foreach ($fileIterator as $file) {
            if ($file->getExtension() && !\in_array($file->getExtension(), $this->ignoreExtensions, true)) {
                yield new Asset($this->path, $file);
            }
        }
    }
}
