<?php

declare(strict_types=1);

namespace NorbertTech\StaticContentGeneratorBundle\Assets;

interface Assets
{
    public function copy(Asset $asset) : void;
}
