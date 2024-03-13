&lt;?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use App\Http\Livewire\EnsureLivewireComponentsAreRegistered;
use Filament\Panel;
use Illuminate\Support\Facades\Facade;

class EnsureLivewireComponentsAreRegisteredTest extends TestCase
{
    public function testCheckAndRegisterComponentsRegistersMissingComponents()
    {
        Facade::setFacadeApplication($this->app);
        $this->partialMock(Panel::class, function ($mock) {
            $mock->shouldReceive('isLivewireComponentRegistered')
                 ->withArgs(['example-component'])
                 ->andReturn(false);
            $mock->shouldReceive('isLivewireComponentRegistered')
                 ->withArgs(['another-component'])
                 ->andReturn(false);
            $mock->shouldReceive('registerLivewireComponent')
                 ->withArgs(['example-component', \App\Http\Livewire\ExampleComponent::class])
                 ->once();
            $mock->shouldReceive('registerLivewireComponent')
                 ->withArgs(['another-component', \App\Http\Livewire\AnotherComponent::class])
                 ->once();
        });

        EnsureLivewireComponentsAreRegistered::checkAndRegisterComponents();
    }

    public function testCheckAndRegisterComponentsDoesNotRegisterAlreadyRegisteredComponents()
    {
        Facade::setFacadeApplication($this->app);
        $this->partialMock(Panel::class, function ($mock) {
            $mock->shouldReceive('isLivewireComponentRegistered')
                 ->withArgs(['example-component'])
                 ->andReturn(true);
            $mock->shouldReceive('isLivewireComponentRegistered')
                 ->withArgs(['another-component'])
                 ->andReturn(true);
            $mock->shouldNotReceive('registerLivewireComponent');
        });

        EnsureLivewireComponentsAreRegistered::checkAndRegisterComponents();
    }
}
