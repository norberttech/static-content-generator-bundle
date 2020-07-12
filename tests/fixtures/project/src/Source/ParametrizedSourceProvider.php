<?php

declare(strict_types=1);

namespace FixtureProject\Source;

use NorbertTech\StaticContentGeneratorBundle\Content\Source;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProvider;

final class ParametrizedSourceProvider implements SourceProvider
{
    public function all() : array
    {
        return [
            new Source('parametrized_route', ['param1' => 'first-param', 'param2' => 'second-param']),
        ];
    }
}
