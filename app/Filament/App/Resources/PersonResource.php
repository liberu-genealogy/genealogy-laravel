<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Enums\PedigreeType;
use App\Filament\App\Resources\PersonResource\Pages\CreatePerson;
use App\Filament\App\Resources\PersonResource\Pages\EditPerson;
use App\Filament\App\Resources\PersonResource\Pages\ListPeople;
// Namespace import: getRelations() refers to these as RelationManagers\Foo::class,
// which without this resolves to App\Filament\App\Resources\RelationManagers\Foo —
// a directory that does not exist. ::class does not autoload, so the bad name was
// only discovered when Filament tried to instantiate it.
use App\Filament\App\Resources\PersonResource\RelationManagers;
use App\Models\Family;
use App\Models\Person;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Override;

class PersonResource extends AppResource
{
    #[Override]
    protected static ?string $model = Person::class;

    #[Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-plus';

    #[Override]
    protected static ?string $navigationLabel = 'People';

    #[Override]
    protected static string|\UnitEnum|null $navigationGroup = '👥 Family Tree';

    #[Override]
    protected static ?int $navigationSort = 1;

    // protected static ?string $tenantRelationshipName = 'People';

    /** "Parent1 & Parent2 (#id)" for a family option, mirroring FamilyResource's parent labels. */
    protected static function familyOptionLabel(Family $f): string
    {
        $names = array_filter([
            $f->husband ? trim("{$f->husband->givn} {$f->husband->surn}") : null,
            $f->wife ? trim("{$f->wife->givn} {$f->wife->surn}") : null,
        ]);

        return ($names === [] ? 'Unknown parents' : implode(' & ', $names))." (#{$f->id})";
    }

    /**
     * Searchable picker for the child-in-family link, labelled by parent names (not a raw id).
     * Optional — a person need not be a child in any family. Writes families.id to the
     * people.child_in_family_id FK. Search matches either parent's name, or a bare family id.
     */
    protected static function childInFamilySelect(): Select
    {
        return Select::make('child_in_family_id')
            ->label('Child in Family')
            ->helperText('The family this person is a child of. Search by a parent\'s name.')
            ->searchable()
            ->getSearchResultsUsing(static fn (string $search): array => Family::query()
                ->with(['husband', 'wife'])
                ->where(function ($q) use ($search): void {
                    $name = static fn ($sub) => $sub->where('givn', 'like', "%{$search}%")
                        ->orWhere('surn', 'like', "%{$search}%");
                    $q->whereHas('husband', $name)->orWhereHas('wife', $name);
                    if (ctype_digit($search)) {
                        $q->orWhere('id', (int) $search);
                    }
                })
                ->limit(50)
                ->get()
                ->mapWithKeys(static fn (Family $f): array => [$f->id => self::familyOptionLabel($f)])
                ->all())
            ->getOptionLabelUsing(static fn ($value): ?string => ($f = Family::with(['husband', 'wife'])->find($value))
                ? self::familyOptionLabel($f)
                : null);
    }

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->description('Core identity and personal details')
                    ->icon('heroicon-o-user')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('photo_url')
                            ->image()
                            ->label('Profile Photo')
                            ->directory('persons')
                            ->disk('public')
                            ->columnSpanFull(),
                        // A person must have at least one name. Enforced once, on givn: it is
                        // required only when both surn and the legacy `name` are empty, so any
                        // single populated name field satisfies the rule.
                        TextInput::make('givn')
                            ->label('First Name')
                            ->requiredWithoutAll('surn,name')
                            ->validationMessages(['required_without_all' => 'Enter at least a first name, last name, or full name.']),
                        TextInput::make('surn')->label('Last Name'),
                        TextInput::make('titl')->label('Title'),
                        TextInput::make('appellative')->label('Appellative'),
                        TextInput::make('name')->label('Full Name'),
                        Select::make('sex')
                            ->options(Person::SEX_OPTIONS)
                            ->label('Sex'),
                        TextInput::make('description')->label('Description')->columnSpanFull(),
                    ]),

                Section::make('Vital Records')
                    ->description('Birth, death, and burial information')
                    ->icon('heroicon-o-calendar')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('birthday')->label('Date of Birth'),
                        DateTimePicker::make('deathday')->label('Date of Death'),
                        DateTimePicker::make('burial_day')->label('Burial Date'),
                        self::childInFamilySelect(),
                        Select::make('pedigree')
                            ->options(PedigreeType::options())
                            ->label('Pedigree')
                            ->placeholder('Biological')
                            ->helperText('Link type to the child-in-family. Leave blank for biological.'),
                    ]),

                Section::make('Contact Information')
                    ->description('Email and phone details')
                    ->icon('heroicon-o-envelope')
                    ->columns(2)
                    ->schema([
                        TextInput::make('email')->label('Email')->email(),
                        TextInput::make('phone')->label('Phone'),
                    ]),

                Section::make('Record References')
                    ->description('Genealogy record identifiers and metadata')
                    ->icon('heroicon-o-document-text')
                    ->columns(3)
                    ->collapsed()
                    ->schema([
                        TextInput::make('rin')->label('RIN'),
                        TextInput::make('rfn')->label('RFN'),
                        TextInput::make('afn')->label('AFN'),
                        TextInput::make('resn')->label('Restriction'),
                        TextInput::make('chan')->label('Change Date'),
                        TextInput::make('bank')->label('Bank'),
                        TextInput::make('bank_account')->label('Bank Account'),
                    ]),
            ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo_url')->label('Photo')->disk('public')->height(40)->width(40)->circular(),
                TextColumn::make('givn')->label('First Name')->searchable()->sortable(),
                TextColumn::make('surn')->label('Last Name')->searchable()->sortable(),
                TextColumn::make('sex')->label('Sex')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'M' => 'info',
                        'F' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('birthday')->label('Born')->date('Y')->sortable(),
                TextColumn::make('deathday')->label('Died')->date('Y')->sortable(),
                TextColumn::make('email')->label('Email')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('phone')->label('Phone')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->label('Added')->since()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('sex')
                    ->options(Person::SEX_OPTIONS),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    #[Override]
    public static function getRelations(): array
    {
        return [
            RelationManagers\PhotosRelationManager::class,
            RelationManagers\AssociationsRelationManager::class,
            RelationManagers\SourcesRelationManager::class,
        ];
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ListPeople::route('/'),
            'create' => CreatePerson::route('/create'),
            'edit' => EditPerson::route('/{record}/edit'),
        ];
    }
}
