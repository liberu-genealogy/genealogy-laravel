<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FamilyEventResource\Pages;
use App\Models\FamilyEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FamilyEventResource extends Resource
{
    protected static ?string $model = FamilyEvent::class;

    protected static ?string $navigationLabel = 'Family Events';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Family';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('family_id')
                ->required()
                ->numeric(),
                Forms\Components\TextInput::make('places_id')
                    ->numeric(),
                Forms\Components\Textarea::make('date')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('title')
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('converted_date')
                    ->maxLength(255),
                Forms\Components\TextInput::make('year')
                    ->numeric(),
                Forms\Components\TextInput::make('month')
                    ->numeric(),
                Forms\Components\TextInput::make('day')
                    ->numeric(),
                Forms\Components\TextInput::make('type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('plac')
                    ->maxLength(255),
                Forms\Components\TextInput::make('addr_id')
                    ->numeric(),
                Forms\Components\TextInput::make('phon')
                    ->maxLength(255),
                Forms\Components\Textarea::make('caus')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('age')
                    ->maxLength(255),
                Forms\Components\TextInput::make('agnc')
                    ->maxLength(255),
                Forms\Components\TextInput::make('husb')
                    ->numeric(),
                Forms\Components\TextInput::make('wife')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('family_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('places_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('converted_date')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('year')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('month')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('day')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('plac')
                    ->searchable(),
                Tables\Columns\TextColumn::make('addr_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('age')
                    ->searchable(),
                Tables\Columns\TextColumn::make('agnc')
                    ->searchable(),
                Tables\Columns\TextColumn::make('husb')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('wife')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFamilyEvents::route('/'),
            'create' => Pages\CreateFamilyEvent::route('/create'),
            'edit'   => Pages\EditFamilyEvent::route('/{record}/edit'),
        ];
    }
}
