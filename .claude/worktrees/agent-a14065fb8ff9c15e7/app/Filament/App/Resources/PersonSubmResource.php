<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\PersonSubmResource\Pages\ListPersonSubms;
use App\Filament\App\Resources\PersonSubmResource\Pages\CreatePersonSubm;
use App\Filament\App\Resources\PersonSubmResource\Pages\EditPersonSubm;
use BackedEnum;
use App\Filament\App\Resources\PersonSubmResource\Pages;
use App\Models\PersonSubm;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\App\Resources\AppResource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class PersonSubmResource extends AppResource
{
    #[\Override]
    protected static ?string $model = PersonSubm::class;

    #[\Override]
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-paper-airplane';
    #[\Override]
    protected static ?string $navigationLabel = 'Person Submissions';
    #[\Override]
    protected static string | \UnitEnum | null $navigationGroup = '👥 Family Tree';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('group')
                    ->maxLength(255),
                TextInput::make('gid')
                    ->numeric(),
                TextInput::make('subm')
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
                TextColumn::make('subm')
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

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index'  => ListPersonSubms::route('/'),
            'create' => CreatePersonSubm::route('/create'),
            'edit'   => EditPersonSubm::route('/{record}/edit'),
        ];
    }
}
