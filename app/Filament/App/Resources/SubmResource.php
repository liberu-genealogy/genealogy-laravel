<?php

namespace App\Filament\App\Resources;

use BackedEnum;
use App\Filament\App\Resources\SubmResource\Pages;
use App\Models\Subm;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class SubmResource extends Resource
{
    protected static ?string $model = Subm::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    #[\Override]
    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('group')
                    ->maxLength(255),
                Forms\Components\TextInput::make('gid')
                    ->numeric(),
                Forms\Components\TextInput::make('name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('addr_id')
                    ->numeric(),
                Forms\Components\TextInput::make('rin')
                    ->maxLength(255),
                Forms\Components\TextInput::make('rfn')
                    ->maxLength(255),
                Forms\Components\TextInput::make('lang')
                    ->maxLength(255),
                Forms\Components\TextInput::make('phon')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('fax')
                    ->maxLength(255),
                Forms\Components\TextInput::make('www')
                    ->maxLength(255),
            ]);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('group')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gid')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('addr_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rfn')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fax')
                    ->searchable(),
                Tables\Columns\TextColumn::make('www')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    #[\Override]
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSubms::route('/'),
            'create' => Pages\CreateSubm::route('/create'),
            'edit'   => Pages\EditSubm::route('/{record}/edit'),
        ];
    }
}
