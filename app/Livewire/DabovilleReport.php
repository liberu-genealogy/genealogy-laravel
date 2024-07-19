<?php

namespace App\Livewire;

use App\Models\Person;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class DabovilleReport extends Component implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('person')
                    ->hiddenLabel()
                    ->options(Person::all()->pluck('name', 'id'))
                    ->placeholder('Select a Person:'),
            ])
            ->statePath('data');
    }

    public function generateAction(): Action
    {
        return Action::make('generate')
            ->action(fn () => null);
    }

    public function generateReport(): void
    {
        dd($this->form->getState());
    }

    public function render(): View
    {
        return view('livewire.daboville-report');
    }
}
