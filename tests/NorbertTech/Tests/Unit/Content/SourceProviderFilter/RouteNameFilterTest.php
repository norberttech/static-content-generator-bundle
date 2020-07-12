<?php

declare(strict_types=1);

namespace NorbertTech\Calendar\Tests\Unit\Content\SourceProviderFilter;

use NorbertTech\StaticContentGeneratorBundle\Content\Source;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProviderFilter\RoutesWithNameFilter;
use PHPUnit\Framework\TestCase;

final class RouteNameFilterTest extends TestCase
{
    public function test_filter_out_all_without_expected_name() : void
    {
        $filter = new RoutesWithNameFilter(['route_1']);

        $sources = $filter->filter(
            [
                new Source('route_1', []),
                new Source('route_2', []),
                new Source('route_3', []),
            ]
        );

        $this->assertCount(1, $sources);
        $this->assertEquals(new Source('route_1', []), $sources[0]);
    }
}
