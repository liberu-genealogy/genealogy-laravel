<?php

namespace Tests\Filament\Pages;

use PHPUnit\Framework\TestCase;
use App\Filament\Pages\DAbovilleReportPage;

class DAbovilleReportPageTest extends TestCase
{
    public function testTitleGetter()
    {
        $page = new DAbovilleReportPage();
        $this->assertEquals('DAboville Report', $page->getTitle());
    }

    public function testNavigationIconGetter()
    {
        $page = new DAbovilleReportPage();
        $this->assertEquals('heroicon-o-document-report', $page->getNavigationIcon());
    }
}
