<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Content;

interface OutputPathResolver
{
    public function outputLocation() : string;

    public function resolve(Content $content) : string;
}
