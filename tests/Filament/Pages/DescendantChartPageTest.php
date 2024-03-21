<?php

namespace Tests\Filament\Pages;

use PHPUnit\Framework\TestCase;
use App\Filament\Pages\DescendantChartPage;

class DescendantChartPageTest extends TestCase
{
    public function testTitleGetter()
    {
        $page = new DescendantChartPage();
        $this->assertEquals('Descendant Chart', $page->getTitle());
    }

    public function testNavigationIconGetter()
    {
        $page = new DescendantChartPage();
        $this->assertEquals('heroicon-o-chart-bar', $page->getNavigationIcon());
    }
}
