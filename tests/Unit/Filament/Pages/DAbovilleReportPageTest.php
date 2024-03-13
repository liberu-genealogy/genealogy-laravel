<?php

namespace Tests\Unit\Filament\Pages;

use Tests\TestCase;
use Livewire\Livewire;
use App\Filament\Pages\DAbovilleReportPage;

class DAbovilleReportPageTest extends TestCase
{
    public function testMountMethodRendersViewSuccessfully()
    {
        Livewire::test(DAbovilleReportPage::class)
            ->assertStatus(200);
    }

    public function testMountMethodHandlesExceptions()
    {
        Livewire::shouldReceive('mount')
            ->with(DAbovilleReportPage::$view)
            ->andThrow(new \Exception('Test Exception'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Test Exception');

        Livewire::test(DAbovilleReportPage::class);
    }
}
