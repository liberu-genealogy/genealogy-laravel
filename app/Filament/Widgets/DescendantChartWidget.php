&lt;?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Person;

class DescendantChartWidget extends Widget
{
    protected static string $view = 'livewire.descendant-chart-component';

    public $descendantsData = [];

    public function mount()
    {
        $rawData = Person::all()->toArray();
        $this->descendantsData = $this->processDescendantData($rawData);
    }

    private function processDescendantData($data)
    {
        return array_map(function ($item) {
            return [
                'id' => $item['id'],
                'name' => $item['name'],
            ];
        }, $data);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view(static::$view, ['descendantsData' => $this->descendantsData]);
    }
}
