<?php

namespace Tests\Feature;

use App\Filament\App\Pages\DabovilleReportPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DAbovilleReportPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_page_class_can_be_instantiated(): void
    {
        $page = new DabovilleReportPage;

        $this->assertInstanceOf(DabovilleReportPage::class, $page);
    }

    public function test_page_has_correct_navigation_label(): void
    {
        $this->assertEquals('DAboville Report', DabovilleReportPage::getNavigationLabel());
    }
}
