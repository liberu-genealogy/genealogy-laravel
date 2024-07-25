<?php

/**
 * Feature tests for the DAbovilleReportPage class.
 * 
 * This class tests the functionalities of the DAbovilleReportPage, ensuring that
 * the render method behaves as expected under various scenarios.
 */

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Filament\App\Pages\DabovilleReportPage;

class DAbovilleReportPageTest extends TestCase
{
    // public function testRenderMethodReturnsCorrectView()
    // {
    //     $page = new DabovilleReportPage();
    //     $view = $page->render();

    //     $this->assertViewIs('livewire.daboville-report', $view);
    // }

    // public function testAhnentafelReportGeneration()
    // {
    //     $page = new DAbovilleReportPage();
    //     $component = new \App\Http\Livewire\AhnentafelReport();
    //     $component->generateReport(1);

    //     $this->assertEquals($expectedReportData, $component->reportData);
    // }
}
