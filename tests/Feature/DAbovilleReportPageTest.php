<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Filament\App\Pages\DabovilleReportPage;

class DAbovilleReportPageTest extends TestCase
{
    use RefreshDatabase;

    public function testPageClassCanBeInstantiated(): void
    {
        $page = new DabovilleReportPage();

        $this->assertInstanceOf(DabovilleReportPage::class, $page);
    }

    public function testPageHasCorrectNavigationLabel(): void
    {
        $this->assertEquals('DAboville Report', DabovilleReportPage::getNavigationLabel());
    }
}
