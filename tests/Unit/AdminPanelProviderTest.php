<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Providers\Filament\AdminPanelProvider;

class AdminPanelProviderTest extends TestCase
{
    public function testDAbovilleReportPageIsRegistered()
    {
        $adminPanelProvider = new AdminPanelProvider();
        $registeredPages = $adminPanelProvider->getRegisteredPages(); // Hypothetical method to retrieve registered pages

        $this->assertContains(App\Filament\Pages\DAbovilleReportPage::class, $registeredPages, "DAbovilleReportPage is not registered in the AdminPanelProvider.");
    }
}
