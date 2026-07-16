<?php

declare(strict_types=1);

namespace App\Filament\App\Pages;

use App\Models\Person;
use App\Modules\Core\Services\TreeService;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class RelationshipCalculatorReport extends Page implements HasForms
{
    use InteractsWithForms;

    #[\Override]
    protected string $view = 'filament.app.pages.relationship-calculator-report';

    #[\Override]
    protected static ?string $title = 'Relationship Calculator';

    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    #[\Override]
    protected static ?string $navigationLabel = 'Relationship Calculator';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = '📄 Reports';

    public ?array $data = [];

    public ?string $relationship = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        $people = Person::query()
            ->orderBy('surn')
            ->orderBy('givn')
            ->get()
            ->mapWithKeys(fn (Person $p): array => [$p->id => $p->fullname()])
            ->toArray();

        return $schema
            ->schema([
                Select::make('person1_id')
                    ->label('First person')
                    ->options($people)
                    ->searchable()
                    ->required(),
                Select::make('person2_id')
                    ->label('Second person')
                    ->options($people)
                    ->searchable()
                    ->required(),
            ])
            ->statePath('data');
    }

    public function calculate(): void
    {
        $data = $this->form->getState();

        $person1 = Person::find($data['person1_id']);
        $person2 = Person::find($data['person2_id']);

        if (! $person1 || ! $person2) {
            Notification::make()->title('Please select two people.')->danger()->send();

            return;
        }

        $this->relationship = app(TreeService::class)->calculateRelationship($person1, $person2);
    }
}
