<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Content;

interface Transformer
{
    public function transform(Source $source) : Content;
}
