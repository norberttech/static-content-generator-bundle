<?php

declare(strict_types=1);

namespace NorbertTech\Calendar\Tests\Unit\Content\SourceProviderFilter;

use NorbertTech\StaticContentGeneratorBundle\Content\Source;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProviderFilter\RoutesWithNamePrefixFilter;
use PHPUnit\Framework\TestCase;

final class RoutesWithNamePrefixFilterTest extends TestCase
{
    public function test_filter_out_all_without_expected_name() : void
    {
        $filter = new RoutesWithNamePrefixFilter(['route_']);

        $sources = $filter->filter(
            [
                new Source('admin_1', []),
                new Source('route_2', []),
                new Source('admin_3', []),
            ]
        );

        $this->assertCount(1, $sources);
        $this->assertEquals(new Source('route_2', []), $sources[0]);
    }
}
