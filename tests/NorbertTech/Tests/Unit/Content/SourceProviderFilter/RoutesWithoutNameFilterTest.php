<?php

declare(strict_types=1);

namespace NorbertTech\Calendar\Tests\Unit\Content\SourceProviderFilter;

use NorbertTech\StaticContentGeneratorBundle\Content\Source;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProviderFilter\RoutesWithoutNameFilter;
use PHPUnit\Framework\TestCase;

final class RoutesWithoutNameFilterTest extends TestCase
{
    public function test_filter_out_all_without_expected_name() : void
    {
        $filter = new RoutesWithoutNameFilter(['admin_1']);

        $sources = $filter->filter(
            [
                new Source('route_1', []),
                new Source('admin_1', []),
                new Source('admin_2', []),
            ]
        );

        $this->assertCount(2, $sources);
        $this->assertEquals(new Source('route_1', []), $sources[0]);
        $this->assertEquals(new Source('admin_2', []), $sources[1]);
    }
}
