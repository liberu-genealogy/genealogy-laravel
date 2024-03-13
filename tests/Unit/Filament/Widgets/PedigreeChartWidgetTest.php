<?php

namespace Tests\Unit\Filament\Widgets;

use PHPUnit\Framework\TestCase;
use App\Filament\Widgets\PedigreeChartWidget;
use App\Models\Person;
use Illuminate\Support\Facades\View;
use Illuminate\Database\Eloquent\Collection;

class PedigreeChartWidgetTest extends TestCase
{
    public function test_mount_method()
    {
        $mockedPersons = new Collection([
            new Person(['name' => 'John Doe']),
            new Person(['name' => 'Jane Doe'])
        ]);

        Person::shouldReceive('all')->once()->andReturn($mockedPersons);

        $widget = new PedigreeChartWidget();
        $widget->mount();

        $this->assertEquals($mockedPersons, $widget->persons);
    }

    public function test_render_method()
    {
        View::shouldReceive('view')
            ->once()
            ->with('livewire.pedigree-chart', ['persons' => \Mockery::type(Collection::class)])
            ->andReturn('view.rendered');

        $widget = new PedigreeChartWidget();
        $widget->persons = new Collection([
            new Person(['name' => 'John Doe']),
            new Person(['name' => 'Jane Doe'])
        ]);

        $result = $widget->render();

        $this->assertEquals('view.rendered', $result);
    }
}
