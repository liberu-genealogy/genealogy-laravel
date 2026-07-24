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
class DevillierReport extends Component implements HasActions, HasForms
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
            $this->buildDeVilliersNumbers($person, 'a1', 0);
        }
    }

    /**
     * de Villiers / Pama numbering: the progenitor is "a1"; each descendant's
     * code is a chain of (generation-letter + birth-rank) tokens — b1, b2, then
     * b2c3, etc. The generation letter indexes the generation (a, b, c, …), so
     * ranks are plain decimals and >9 children is native (b10). Descendants do
     * NOT carry the progenitor's "a1" prefix — the chain starts fresh at "b".
     * Only bloodline descendants are numbered; children from all of a
     * descendant's marriages form one birth-order sequence.
     */
    private function buildDeVilliersNumbers(Person $person, string $number, int $depth): void
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

        // Children belong to the next generation; their letter is (depth+1)-th.
        $childLetter = chr(ord('a') + $depth + 1);
        // The progenitor's "a1" is not a prefix for its descendants.
        $prefix = $depth === 0 ? '' : $number;

        $rank = 1;
        foreach ($children->sortBy('birthday') as $child) {
            $this->buildDeVilliersNumbers($child, $prefix.$childLetter.$rank, $depth + 1);
            $rank++;
        }
    }

    public function render(): View
    {
        return view('livewire.devilliers-report');
    }
}
