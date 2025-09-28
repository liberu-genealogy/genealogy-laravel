<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\SubmResource\Pages\ListSubms;
use App\Filament\App\Resources\SubmResource\Pages\CreateSubm;
use App\Filament\App\Resources\SubmResource\Pages\EditSubm;
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

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = '\ud83d\udee0\ufe0f Data Management';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('group')
                    ->maxLength(255),
                TextInput::make('gid')
                    ->numeric(),
                TextInput::make('name')
                    ->maxLength(255),
                TextInput::make('addr_id')
                    ->numeric(),
                TextInput::make('rin')
                    ->maxLength(255),
                TextInput::make('rfn')
                    ->maxLength(255),
                TextInput::make('lang')
                    ->maxLength(255),
                TextInput::make('phon')
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                TextInput::make('fax')
                    ->maxLength(255),
                TextInput::make('www')
                    ->maxLength(255),
            ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group')
                    ->searchable(),
                TextColumn::make('gid')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('addr_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rin')
                    ->searchable(),
                TextColumn::make('rfn')
                    ->searchable(),
                TextColumn::make('lang')
                    ->searchable(),
                TextColumn::make('phon')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('fax')
                    ->searchable(),
                TextColumn::make('www')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index'  => ListSubms::route('/'),
            'create' => CreateSubm::route('/create'),
            'edit'   => EditSubm::route('/{record}/edit'),
        ];
    }
}
