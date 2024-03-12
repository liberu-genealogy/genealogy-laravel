<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Filament\Pages\DAbovilleReportPage;

class DAbovilleReportPageTest extends TestCase
{
    public function testRenderMethodReturnsCorrectView()
    {
        $page = new DAbovilleReportPage();
        $view = $page->render();

        $this->assertViewIs('livewire.daboville-report', $view);
    }
}
