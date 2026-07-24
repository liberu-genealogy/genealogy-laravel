<?php

namespace App\Livewire;

use App\Models\Person;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

/**
 * @property Schema $form
 */
class DabovilleReport extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ?array $data = [];

    public ?int $selectedPersonId = null;

    public array $reportData = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('person')
                    ->hiddenLabel()
                    ->options(Person::pluck('name', 'id'))
                    ->searchable()
                    ->placeholder('Select a person…'),
            ])
            ->statePath('data');
    }

    public function generateAction(): Action
    {
        return Action::make('generate')
            ->label('Generate Report')
            ->action(fn () => $this->generateReport());
    }

    public function generateReport(): void
    {
        $personId = $this->data['person'] ?? null;
        $this->selectedPersonId = $personId ? (int) $personId : null;
        $this->reportData = [];

        if (! $personId) {
            return;
        }

        $person = Person::find($personId);

        if ($person) {
            $this->buildDabovilleNumbers($person, '1', 0);
        }
    }

    /**
     * d'Aboville numbering: progenitor = "1"; each generation is separated by a
     * period and birth ranks are plain decimals with no cap (10th child = .10).
     * Only bloodline descendants are numbered; a descendant's children from all
     * marriages form one birth-order sequence.
     */
    private function buildDabovilleNumbers(Person $person, string $number, int $depth): void
    {
        $this->reportData[] = [
            'number' => $number,
            'depth' => $depth,
            'name' => $person->fullname(),
            'birth' => $person->birthday ? Carbon::parse($person->birthday)->format('Y') : null,
            'death' => $person->deathday ? Carbon::parse($person->deathday)->format('Y') : null,
        ];

        /** @var Collection<int, Person> $children */
        $children = $person->children()->get();

        $rank = 1;
        foreach ($children->sortBy('birthday') as $child) {
            $this->buildDabovilleNumbers($child, $number.'.'.$rank, $depth + 1);
            $rank++;
        }
    }

    public function render(): View
    {
        return view('livewire.daboville-report');
    }
}
