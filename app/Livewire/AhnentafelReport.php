<?php

namespace App\Livewire;

use App\Models\Family;
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
use Livewire\Component;

/**
 * @property Schema $form
 */
class AhnentafelReport extends Component implements HasActions, HasForms
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

        $person = Person::with(['childInFamily.husband', 'childInFamily.wife'])->find($personId);

        if ($person) {
            $this->buildAhnentafelNumbers($person, 1);
            ksort($this->reportData);
        }
    }

    /**
     * Ahnentafel numbering: subject = 1, father = 2n, mother = 2n+1.
     * Keyed by ahnentafel number (NOT person id) so that under pedigree
     * collapse one real person legitimately occupies several numbers — the
     * number identifies a tree position, not a person (see numbering ref).
     */
    private function buildAhnentafelNumbers(?Person $person, int $number): void
    {
        if (! $person) {
            return;
        }

        $this->reportData[$number] = [
            'number' => $number,
            'person_id' => $person->id,
            'name' => $person->fullname(),
            'givn' => $person->givn,
            'surn' => $person->surn,
            'sex' => $person->sex,
            // birthday/deathday cast to datetime at runtime, but larastan reads
            // them as the raw string column — Carbon::parse is correct for both.
            'birth_date' => $person->birthday ? Carbon::parse($person->birthday)->format('d M Y') : null,
            'death_date' => $person->deathday ? Carbon::parse($person->deathday)->format('d M Y') : null,
            // GEDCOM columns: birth_place/death_place are NOT columns; the
            // import writes birthday_plac / deathday_plac (guarded by a test).
            'birth_place' => $person->birthday_plac ?? '',
            'death_place' => $person->deathday_plac ?? '',
        ];

        /** @var Family|null $family */
        $family = $person->childInFamily;

        if ($family) {
            if ($family->husband) {
                $this->buildAhnentafelNumbers($family->husband, $number * 2);
            }
            if ($family->wife) {
                $this->buildAhnentafelNumbers($family->wife, $number * 2 + 1);
            }
        }
    }

    public function clearReport(): void
    {
        $this->selectedPersonId = null;
        $this->reportData = [];
        $this->form->fill();
    }

    public function render(): View
    {
        return view('livewire.ahnentafel-report');
    }
}
