<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Content\OutputPathResolver;

use NorbertTech\StaticContentGeneratorBundle\Content\Content;
use NorbertTech\StaticContentGeneratorBundle\Content\OutputPathResolver;

final class IndexHTML implements OutputPathResolver
{
    /**
     * https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Common_types.
     */
    private const HTTP_EXTENSIONS = [
        'aac',
        'abw',
        'arc',
        'avi',
        'azw',
        'bin',
        'bmp',
        'bz',
        'bz2',
        'csh',
        'css',
        'csv',
        'doc',
        'docx',
        'eot',
        'epub',
        'gz',
        'gif',
        'htm',
        'html',
        'ico',
        'ics',
        'jar',
        'jpeg',
        'jpg',
        'js',
        'json',
        'jsonld',
        'mid',
        'midi',
        'mjs',
        'mp3',
        'mpeg',
        'mpkg',
        'odp',
        'ods',
        'odt',
        'oga',
        'ogv',
        'ogx',
        'opus',
        'otf',
        'png',
        'pdf',
        'php',
        'ppt',
        'pptx',
        'rar',
        'rtf',
        'sh',
        'svg',
        'swf',
        'tar',
        'tif',
        'tiff',
        'ts',
        'ttf',
        'txt',
        'vsd',
        'wav',
        'weba',
        'webm',
        'webp',
        'woff',
        'woff2',
        'xhtml',
        'xls',
        'xlsx',
        'xml',
        'xul',
        'zip',
        '3gp',
        '3g2',
        '7z',
    ];

    private string $outputDirectory;

    public function __construct(string $outputDirectory)
    {
        $this->outputDirectory = $outputDirectory;
    }

    public function outputLocation() : string
    {
        return $this->outputDirectory;
    }

    /** @codeCoverageIgnore */
    public function resolve(Content $content) : string
    {
        if ($extension = \pathinfo($content->path(), PATHINFO_EXTENSION)) {
            if (\in_array($extension, self::HTTP_EXTENSIONS, true)) {
                return \rtrim($this->outputDirectory, '/') . DIRECTORY_SEPARATOR . \ltrim($content->path(), '/');
            }
        }

        return \rtrim($this->outputDirectory, '/') . DIRECTORY_SEPARATOR . \ltrim($content->path(), '/') . DIRECTORY_SEPARATOR . 'index.html';
    }
}
