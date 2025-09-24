<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\FamilyResource\Pages\ListFamilies;
use App\Filament\App\Resources\FamilyResource\Pages\CreateFamily;
use App\Filament\App\Resources\FamilyResource\Pages\EditFamily;
use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\FamilyResource\Pages;
use App\Models\Family;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class FamilyResource extends Resource
{
    protected static ?string $model = Family::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Families';
    protected static string | \UnitEnum | null $navigationGroup = 'Family Tree';
    protected static ?int $navigationSort = 2;

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('description')
                ->maxLength(65535)
                ->columnSpanFull(),
                TextInput::make('is_active')
                    ->numeric(),
                TextInput::make('type_id')
                    ->numeric(),
                TextInput::make('husband_id')
                    ->numeric(),
                TextInput::make('wife_id')
                    ->numeric(),
                TextInput::make('chan')
                    ->maxLength(255),
                TextInput::make('nchi')
                    ->maxLength(255),
                TextInput::make('rin')
                    ->maxLength(255),
            ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('is_active')
                ->numeric()
                ->sortable(),
                TextColumn::make('type_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('husband_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('wife_id')
                    ->numeric()
                    ->sortable(),
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
                TextColumn::make('chan')
                    ->searchable(),
                TextColumn::make('nchi')
                    ->searchable(),
                TextColumn::make('rin')
                    ->searchable(),
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
            'index'  => ListFamilies::route('/'),
            'create' => CreateFamily::route('/create'),
            'edit'   => EditFamily::route('/{record}/edit'),
        ];
    }
}
