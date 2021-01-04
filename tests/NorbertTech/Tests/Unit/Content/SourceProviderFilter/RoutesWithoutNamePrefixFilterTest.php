<?php

declare(strict_types=1);

namespace NorbertTech\Calendar\Tests\Unit\Content\SourceProviderFilter;

use NorbertTech\StaticContentGeneratorBundle\Content\Source;
use NorbertTech\StaticContentGeneratorBundle\Content\SourceProviderFilter\RoutesWithoutNamePrefixFilter;
use PHPUnit\Framework\TestCase;

final class RoutesWithoutNamePrefixFilterTest extends TestCase
{
    public function test_filter_out_all_without_expected_name() : void
    {
        $filter = new RoutesWithoutNamePrefixFilter(['admin_']);

        $sources = $filter->filter(
            [
                new Source('route_1', []),
                new Source('admin_2', []),
                new Source('route_2', []),
            ]
        );

        $this->assertCount(2, $sources);
        $this->assertEquals(new Source('route_1', []), $sources[0]);
        $this->assertEquals(new Source('route_2', []), $sources[1]);
    }
}
