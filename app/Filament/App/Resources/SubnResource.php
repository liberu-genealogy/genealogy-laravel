<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\SubnResource\Pages\CreateSubn;
use App\Filament\App\Resources\SubnResource\Pages\EditSubn;
use App\Filament\App\Resources\SubnResource\Pages\ListSubns;
use App\Models\Subn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Override;

class SubnResource extends AppResource
{
    #[Override]
    protected static ?string $model = Subn::class;

    #[Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-inbox-arrow-down';

    #[Override]
    protected static ?string $navigationLabel = 'Submissions';

    #[Override]
    protected static string|\UnitEnum|null $navigationGroup = '🛠️ Data & Import';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('subm')
                    ->maxLength(255),
                TextInput::make('famf')
                    ->maxLength(255),
                TextInput::make('temp')
                    ->maxLength(255),
                TextInput::make('ance')
                    ->maxLength(255),
                TextInput::make('desc')
                    ->maxLength(255),
                TextInput::make('ordi')
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
                TextColumn::make('subm')
                    ->searchable(),
                TextColumn::make('famf')
                    ->searchable(),
                TextColumn::make('temp')
                    ->searchable(),
                TextColumn::make('ance')
                    ->searchable(),
                TextColumn::make('desc')
                    ->searchable(),
                TextColumn::make('ordi')
                    ->searchable(),
                TextColumn::make('rin')
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

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ListSubns::route('/'),
            'create' => CreateSubn::route('/create'),
            'edit' => EditSubn::route('/{record}/edit'),
        ];
    }
}
