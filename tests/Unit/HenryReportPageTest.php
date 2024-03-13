<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Filament\Pages\HenryReportPage;

class HenryReportPageTest extends TestCase
{
    public function testGetTitleReturnsCorrectTitle()
    {
        $page = new HenryReportPage();
        $title = $page->getTitle();

        $this->assertEquals('Henry Report', $title);
    }

    public function testGetNavigationIconReturnsCorrectIcon()
    {
        $page = new HenryReportPage();
        $icon = $page->getNavigationIcon();

        $this->assertEquals('heroicon-o-document-report', $icon);
    }

    public function testRenderMethodReturnsRenderableInstance()
    {
        $page = new HenryReportPage();
        $renderable = $page->render();

        $this->assertInstanceOf(\Illuminate\Contracts\Support\Renderable::class, $renderable);
    }

    public function testMountMethodMountsLivewireComponent()
    {
        $page = new HenryReportPage();
        $page->mount();

        // Assert that the Livewire component is mounted correctly
        // You can use Livewire testing methods or assertions here
        // Example: $this->assertLivewireComponentIsMounted('livewire.henry-report');
    }
}
