<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\FamilyEventResource\Pages\ListFamilyEvents;
use App\Filament\App\Resources\FamilyEventResource\Pages\CreateFamilyEvent;
use App\Filament\App\Resources\FamilyEventResource\Pages\EditFamilyEvent;
use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\FamilyEventResource\Pages;
use App\Models\FamilyEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class FamilyEventResource extends Resource
{
    protected static ?string $model = FamilyEvent::class;

    protected static ?string $navigationLabel = 'Family Events';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = 'Family';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('family_id')
                ->required()
                ->numeric(),
                TextInput::make('places_id')
                    ->numeric(),
                Textarea::make('date')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                TextInput::make('title')
                    ->maxLength(255),
                Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                TextInput::make('converted_date')
                    ->maxLength(255),
                TextInput::make('year')
                    ->numeric(),
                TextInput::make('month')
                    ->numeric(),
                TextInput::make('day')
                    ->numeric(),
                TextInput::make('type')
                    ->maxLength(255),
                TextInput::make('plac')
                    ->maxLength(255),
                TextInput::make('addr_id')
                    ->numeric(),
                TextInput::make('phon')
                    ->maxLength(255),
                Textarea::make('caus')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                TextInput::make('age')
                    ->maxLength(255),
                TextInput::make('agnc')
                    ->maxLength(255),
                TextInput::make('husb')
                    ->numeric(),
                TextInput::make('wife')
                    ->numeric(),
            ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('family_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('places_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('converted_date')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('year')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('month')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('day')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('plac')
                    ->searchable(),
                TextColumn::make('addr_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('phon')
                    ->searchable(),
                TextColumn::make('age')
                    ->searchable(),
                TextColumn::make('agnc')
                    ->searchable(),
                TextColumn::make('husb')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('wife')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListFamilyEvents::route('/'),
            'create' => CreateFamilyEvent::route('/create'),
            'edit'   => EditFamilyEvent::route('/{record}/edit'),
        ];
    }
}
