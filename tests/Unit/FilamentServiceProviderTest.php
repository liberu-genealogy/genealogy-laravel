&lt;?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use App\Providers\FilamentServiceProvider;
use Filament\Panel;
use Illuminate\Support\Facades\Facade;

class FilamentServiceProviderTest extends TestCase
{
    public function testBootRegistersComponents()
    {
        Facade::setFacadeApplication($this->app);
        $panelMock = Mockery::mock('alias:' . Panel::class);
        $panelMock->shouldReceive('registerLivewireComponent')
                  ->with('example-component', \App\Http\Livewire\ExampleComponent::class)
                  ->once();
        $panelMock->shouldReceive('registerLivewireComponent')
                  ->with('another-component', \App\Http\Livewire\AnotherComponent::class)
                  ->once();

        $provider = new FilamentServiceProvider($this->app);
        $provider->boot();

        $panelMock->shouldHaveReceived('registerLivewireComponent')
                  ->with('example-component', \App\Http\Livewire\ExampleComponent::class);
        $panelMock->shouldHaveReceived('registerLivewireComponent')
                  ->with('another-component', \App\Http\Livewire\AnotherComponent::class);
    }
}
