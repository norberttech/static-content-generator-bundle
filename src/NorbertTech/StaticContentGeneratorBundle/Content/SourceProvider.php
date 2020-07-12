<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Content;

interface SourceProvider
{
    /**
     * @return Source[]
     * @psalm-return array<Source>
     */
    public function all() : array;
}
