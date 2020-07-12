<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Content;

final class Content
{
    private string $path;

    private string $content;

    public function __construct(string $path, string $content)
    {
        $this->path = $path;
        $this->content = $content;
    }

    public function path() : string
    {
        return $this->path;
    }

    public function content() : string
    {
        return $this->content;
    }
}
