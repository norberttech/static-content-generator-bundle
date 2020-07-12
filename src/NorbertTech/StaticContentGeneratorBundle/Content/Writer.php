<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Content;

interface Writer
{
    public function clean() : void;

    public function write(Content $content) : void;
}
