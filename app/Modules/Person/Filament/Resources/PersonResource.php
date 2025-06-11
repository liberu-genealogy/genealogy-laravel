<?php

namespace App\Modules\Person\Filament\Resources;

use App\Models\Person;
use App\Models\Family;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PersonResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationGroup = 'Genealogy';

    protected static ?string $navigationLabel = 'Persons';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('givn')
                                    ->label('Given Name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('surn')
                                    ->label('Surname')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        Forms\Components\Select::make('sex')
                            ->options([
                                'M' => 'Male',
                                'F' => 'Female',
                                'U' => 'Unknown',
                            ])
                            ->default('U'),
                        Forms\Components\Textarea::make('description')
                            ->maxLength(1000),
                    ]),
                Forms\Components\Section::make('Life Events')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('birthday')
                                    ->label('Birth Date'),
                                Forms\Components\DatePicker::make('deathday')
                                    ->label('Death Date'),
                            ]),
                        Forms\Components\DatePicker::make('burial_day')
                            ->label('Burial Date'),
                    ]),
                Forms\Components\Section::make('Family Relationships')
                    ->schema([
                        Forms\Components\Select::make('child_in_family_id')
                            ->label('Child in Family')
                            ->options(Family::with(['husband', 'wife'])->get()->mapWithKeys(function ($family) {
                                $husbandName = $family->husband ? $family->husband->fullname() : 'Unknown';
                                $wifeName = $family->wife ? $family->wife->fullname() : 'Unknown';
                                return [$family->id => "{$husbandName} & {$wifeName}"];
                            }))
                            ->searchable(),
                    ]),
                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\TextInput::make('gid')
                            ->label('GEDCOM ID')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
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
                Tables\Columns\TextColumn::make('fullname')
                    ->label('Name')
                    ->getStateUsing(fn ($record) => $record->fullname())
                    ->searchable(['givn', 'surn'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('sex')
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
                Tables\Columns\TextColumn::make('birthday')
                    ->label('Birth')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deathday')
                    ->label('Death')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_living')
                    ->label('Living')
                    ->boolean()
                    ->getStateUsing(fn ($record) => !$record->deathday),
                Tables\Columns\TextColumn::make('childInFamily.husband.fullname')
                    ->label('Father')
                    ->limit(20),
                Tables\Columns\TextColumn::make('childInFamily.wife.fullname')
                    ->label('Mother')
                    ->limit(20),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('sex')
                    ->options([
                        'M' => 'Male',
                        'F' => 'Female',
                        'U' => 'Unknown',
                    ]),
                Tables\Filters\TernaryFilter::make('is_living')
                    ->label('Living Status')
                    ->placeholder('All persons')
                    ->trueLabel('Living')
                    ->falseLabel('Deceased')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNull('deathday'),
                        false: fn (Builder $query) => $query->whereNotNull('deathday'),
                    ),
                Tables\Filters\Filter::make('has_birth_date')
                    ->label('Has Birth Date')
                    ->query(fn (Builder $query) => $query->whereNotNull('birthday')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Modules\Person\Filament\Resources\PersonResource\Pages\ListPersons::route('/'),
            'create' => \App\Modules\Person\Filament\Resources\PersonResource\Pages\CreatePerson::route('/create'),
            'edit' => \App\Modules\Person\Filament\Resources\PersonResource\Pages\EditPerson::route('/{record}/edit'),
        ];
    }
}