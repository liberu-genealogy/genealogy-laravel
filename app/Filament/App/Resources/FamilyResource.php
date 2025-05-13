<?php

namespace App\Filament\App\Resources;

use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\FamilyResource\Pages;
use App\Models\Family;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class FamilyResource extends Resource
{
    protected static ?string $model = Family::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Families';
    protected static UnitEnum|string|null $navigationGroup = 'Family';

    #[\Override]
    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('description')
                ->maxLength(65535)
                ->columnSpanFull(),
                Forms\Components\TextInput::make('is_active')
                    ->numeric(),
                Forms\Components\TextInput::make('type_id')
                    ->numeric(),
                Forms\Components\TextInput::make('husband_id')
                    ->numeric(),
                Forms\Components\TextInput::make('wife_id')
                    ->numeric(),
                Forms\Components\TextInput::make('chan')
                    ->maxLength(255),
                Forms\Components\TextInput::make('nchi')
                    ->maxLength(255),
                Forms\Components\TextInput::make('rin')
                    ->maxLength(255),
            ]);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('is_active')
                ->numeric()
                ->sortable(),
                Tables\Columns\TextColumn::make('type_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('husband_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('wife_id')
                    ->numeric()
                    ->sortable(),
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
                Tables\Columns\TextColumn::make('chan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nchi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rin')
                    ->searchable(),
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
            'index'  => Pages\ListFamilies::route('/'),
            'create' => Pages\CreateFamily::route('/create'),
            'edit'   => Pages\EditFamily::route('/{record}/edit'),
        ];
    }
}
