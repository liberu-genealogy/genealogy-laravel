<?php

namespace App\Modules\Person\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\Filter;
use App\Modules\Person\Filament\Resources\PersonResource\Pages\ListPersons;
use App\Modules\Person\Filament\Resources\PersonResource\Pages\CreatePerson;
use App\Modules\Person\Filament\Resources\PersonResource\Pages\EditPerson;
use App\Models\Person;
use App\Models\Family;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PersonResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user';

    protected static string | \UnitEnum | null $navigationGroup = 'Genealogy';

    protected static ?string $navigationLabel = 'Persons';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('givn')
                                    ->label('Given Name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('surn')
                                    ->label('Surname')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        Select::make('sex')
                            ->options([
                                'M' => 'Male',
                                'F' => 'Female',
                                'U' => 'Unknown',
                            ])
                            ->default('U'),
                        Textarea::make('description')
                            ->maxLength(1000),
                    ]),
                Section::make('Life Events')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                DatePicker::make('birthday')
                                    ->label('Birth Date'),
                                DatePicker::make('deathday')
                                    ->label('Death Date'),
                            ]),
                        DatePicker::make('burial_day')
                            ->label('Burial Date'),
                    ]),
                Section::make('Family Relationships')
                    ->schema([
                        Select::make('child_in_family_id')
                            ->label('Child in Family')
                            ->options(Family::with(['husband', 'wife'])->get()->mapWithKeys(function ($family) {
                                $husbandName = $family->husband ? $family->husband->fullname() : 'Unknown';
                                $wifeName = $family->wife ? $family->wife->fullname() : 'Unknown';
                                return [$family->id => "{$husbandName} & {$wifeName}"];
                            }))
                            ->searchable(),
                    ]),
                Section::make('Additional Information')
                    ->schema([
                        TextInput::make('gid')
                            ->label('GEDCOM ID')
                            ->maxLength(255),
                        TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                    ])
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fullname')
                    ->label('Name')
                    ->getStateUsing(fn ($record) => $record->fullname())
                    ->searchable(['givn', 'surn'])
                    ->sortable(),
                TextColumn::make('sex')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'M' => 'blue',
                        'F' => 'pink',
                        'U' => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'M' => 'Male',
                        'F' => 'Female',
                        'U' => 'Unknown',
                    }),
                TextColumn::make('birthday')
                    ->label('Birth')
                    ->date()
                    ->sortable(),
                TextColumn::make('deathday')
                    ->label('Death')
                    ->date()
                    ->sortable(),
                IconColumn::make('is_living')
                    ->label('Living')
                    ->boolean()
                    ->getStateUsing(fn ($record) => !$record->deathday),
                TextColumn::make('childInFamily.husband.fullname')
                    ->label('Father')
                    ->limit(20),
                TextColumn::make('childInFamily.wife.fullname')
                    ->label('Mother')
                    ->limit(20),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('sex')
                    ->options([
                        'M' => 'Male',
                        'F' => 'Female',
                        'U' => 'Unknown',
                    ]),
                TernaryFilter::make('is_living')
                    ->label('Living Status')
                    ->placeholder('All persons')
                    ->trueLabel('Living')
                    ->falseLabel('Deceased')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNull('deathday'),
                        false: fn (Builder $query) => $query->whereNotNull('deathday'),
                    ),
                Filter::make('has_birth_date')
                    ->label('Has Birth Date')
                    ->query(fn (Builder $query) => $query->whereNotNull('birthday')),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPersons::route('/'),
            'create' => CreatePerson::route('/create'),
            'edit' => EditPerson::route('/{record}/edit'),
        ];
    }
}
