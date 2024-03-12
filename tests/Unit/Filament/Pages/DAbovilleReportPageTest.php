<?php

namespace Tests\Unit\Filament\Pages;

use Tests\TestCase;
use Livewire\Livewire;
use App\Filament\Pages\DAbovilleReportPage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DAbovilleReportPageTest extends TestCase
{
    use RefreshDatabase;
    
    public function it_mounts_the_livewire_component_correctly()
    {
        Livewire::test(DAbovilleReportPage::class)
            ->assertViewIs('livewire.daboville-report')
            ->assertStatus(200);
    }
}
