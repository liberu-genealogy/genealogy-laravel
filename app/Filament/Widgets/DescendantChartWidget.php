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

    /**
     * Renders the Descendant Chart widget view.
     * 
     * This function prepares the data for the Descendant Chart widget and returns the view to be rendered.
     * 
     * @return \Illuminate\Contracts\View\View The view instance for the Descendant Chart widget.
     */
    public function render(): \Illuminate\Contracts\View\View
    {
        return view(static::$view, ['descendantsData' => $this->descendantsData]);
    }
}
