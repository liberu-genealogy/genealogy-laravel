<?php

namespace App\Livewire;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Contracts\View\View;

class DabovilleReport extends Component implements HasForms
{
    use InteractsWithForms;

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
                    ->placeholder('Select a Person:'),

            ])
            ->statePath('data');
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
