<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Filament\App\Pages\DabovilleReportPage;

class DAbovilleReportPageTest extends TestCase
{
    use RefreshDatabase;
    
    public function testRenderMethodReturnsCorrectView(): void
    {
        $page = new DabovilleReportPage();
        $view = $page->render();

        $this->assertViewIs('livewire.daboville-report', $view);
    }

    public function testAhnentafelReportGeneration(): void 
    {
        $page = new DAbovilleReportPage();
        $component = new \App\Http\Livewire\AhnentafelReport();
        $component->generateReport(personId: 1);

        $this->assertEquals($this->expectedReportData, $component->reportData);
    }
}
