<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PersonEventResource\Pages;
use App\Filament\Resources\PersonEventResource\RelationManagers;
use App\Models\PersonEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PersonEventResource extends Resource
{
    protected static ?string $model = PersonEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                Forms\Components\Textarea::make('attr')
                    ->maxLength(65535)
                    ->columnSpanFull(),
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
                Forms\Components\TextInput::make('adop')
                    ->maxLength(255),
                Forms\Components\TextInput::make('adop_famc')
                    ->maxLength(255),
                Forms\Components\TextInput::make('birt_famc')
                    ->maxLength(255),
                Forms\Components\TextInput::make('person_id')
                    ->numeric(),
                Forms\Components\TextInput::make('title')
                    ->maxLength(255),
                Forms\Components\TextInput::make('date')
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->maxLength(255),
                Forms\Components\TextInput::make('places_id')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
                Tables\Columns\TextColumn::make('adop')
                    ->searchable(),
                Tables\Columns\TextColumn::make('adop_famc')
                    ->searchable(),
                Tables\Columns\TextColumn::make('birt_famc')
                    ->searchable(),
                Tables\Columns\TextColumn::make('person_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('places_id')
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
            'index' => Pages\ListPersonEvents::route('/'),
            'create' => Pages\CreatePersonEvent::route('/create'),
            'edit' => Pages\EditPersonEvent::route('/{record}/edit'),
        ];
    }
}
