&lt;?php

namespace Tests\Providers\Filament;

use Tests\TestCase;
use App\Providers\Filament\AdminPanelProvider;
use Filament\Panel;
use App\Filament\Pages\Dashboard;
use App\Filament\Pages\PedigreeChartPage;
use App\Filament\Pages\FanChartPage;
use App\Filament\Pages\DescendantChartPage;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Support\Facades\App;

class AdminPanelProviderTest extends TestCase
{
    public function testPanelSetupCorrectly()
    {
        $provider = new AdminPanelProvider();
        $panel = $provider->panel(new Panel());

        $this->assertEquals('admin', $panel->getId());
        $this->assertEquals('admin', $panel->getPath());
        $this->assertEquals(['primary' => 'amber'], $panel->getColors());
    }

    public function testPagesAreCorrectlyRegistered()
    {
        App::shouldReceive('make')->with(Panel::class)->andReturn(new Panel());

        $provider = new AdminPanelProvider();
        $panel = $provider->panel(App::make(Panel::class));

        $this->assertContains(Dashboard::class, $panel->getPages());
        $this->assertContains(PedigreeChartPage::class, $panel->getPages());
        $this->assertContains(FanChartPage::class, $panel->getPages());
        $this->assertContains(DescendantChartPage::class, $panel->getPages());
    }

    public function testWidgetsAreCorrectlyRegistered()
    {
        App::shouldReceive('make')->with(Panel::class)->andReturn(new Panel());

        $provider = new AdminPanelProvider();
        $panel = $provider->panel(App::make(Panel::class));

        $this->assertContains(AccountWidget::class, $panel->getWidgets());
        $this->assertContains(FilamentInfoWidget::class, $panel->getWidgets());
    }

    public function testMiddlewareIsCorrectlyRegistered()
    {
        App::shouldReceive('make')->with(Panel::class)->andReturn(new Panel());

        $provider = new AdminPanelProvider();
        $panel = $provider->panel(App::make(Panel::class));

        $this->assertNotEmpty($panel->getMiddleware());
    }

    public function testTenantSettingsAreCorrectlyRegistered()
    {
        App::shouldReceive('make')->with(Panel::class)->andReturn(new Panel());

        $provider = new AdminPanelProvider();
        $panel = $provider->panel(App::make(Panel::class));

        $this->assertNotNull($panel->getTenantRegistration());
        $this->assertNotNull($panel->getTenantProfile());
        $this->assertNotNull($panel->getTenant());
        $this->assertNotNull($panel->getTenantBillingProvider());
        $this->assertTrue($panel->requiresTenantSubscription());
        $this->assertNotEmpty($panel->getTenantMiddleware());
    }
}
