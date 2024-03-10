<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PersonResource\Pages;
use App\Models\Person;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PersonResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required()->label('Name'),
                DatePicker::make('birth_date')->label('Birth Date'),
                DatePicker::make('death_date')->label('Death Date'),
                Textarea::make('notes')->label('Notes'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable()->label('Name'),
                DateColumn::make('birth_date')->label('Birth Date'),
                DateColumn::make('death_date')->label('Death Date'),
                TextColumn::make('notes')->label('Notes'),
            ])
            ->filters([
                Tables\Filters\Filter::make('name')->query(fn ($query, $data) => $query->where('name', 'like', "%{$data}%")),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index'  => Pages\ListPeople::route('/'),
            'create' => Pages\CreatePerson::route('/create'),
            'edit'   => Pages\EditPerson::route('/{record}/edit'),
        ];
    }
}
