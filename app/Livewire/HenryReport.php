<?php

namespace App\Livewire;

use App\Models\Person;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
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
class HenryReport extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ?array $data = [];

    public ?int $selectedPersonId = null;

    public bool $modified = false;

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
                Toggle::make('modified')
                    ->label('Modified Henry (parenthesise the 10th+ child)'),
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
        $this->modified = (bool) ($this->data['modified'] ?? false);
        $this->selectedPersonId = $personId ? (int) $personId : null;
        $this->reportData = [];

        if (! $personId) {
            return;
        }

        $person = Person::find($personId);

        if ($person) {
            $this->buildHenryNumbers($person, '1', 0);
        }
    }

    /**
     * Henry numbering: progenitor = "1"; each child appends its birth-order
     * rank to the parent's number. Only bloodline descendants are numbered;
     * a descendant's children from all marriages form one birth-order sequence.
     */
    private function buildHenryNumbers(Person $person, string $number, int $depth): void
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
            $this->buildHenryNumbers($child, $number.$this->formatRank($rank), $depth + 1);
            $rank++;
        }
    }

    /**
     * Rank → generation token. 1–9 are bare digits either way. For the 10th+
     * child: standard Henry uses letters (10 → X, 11 → A, 12 → B, …); Modified
     * Henry parenthesises the decimal (10 → (10)).
     */
    private function formatRank(int $rank): string
    {
        if ($rank <= 9) {
            return (string) $rank;
        }

        if ($this->modified) {
            return '('.$rank.')';
        }

        return $rank === 10 ? 'X' : chr(ord('A') + $rank - 11);
    }

    public function render(): View
    {
        return view('livewire.henry-report');
    }
}
