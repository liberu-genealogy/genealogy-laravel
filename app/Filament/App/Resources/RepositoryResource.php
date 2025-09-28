<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\RepositoryResource\Pages\ListRepositories;
use App\Filament\App\Resources\RepositoryResource\Pages\CreateRepository;
use App\Filament\App\Resources\RepositoryResource\Pages\EditRepository;
use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\RepositoryResource\Pages;
use App\Models\Repository;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

final class RepositoryResource extends Resource
{
    protected static ?string $model = Repository::class;

    protected static ?string $navigationLabel = 'Repository';

    protected static string | \UnitEnum | null $navigationGroup = '🔍 Research & Analysis';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

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
                Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                DateTimePicker::make('date'),
                TextInput::make('is_active')
                    ->numeric(),
                TextInput::make('type_id')
                    ->numeric(),
                TextInput::make('repo')
                    ->maxLength(255),
                TextInput::make('addr_id')
                    ->numeric(),
                TextInput::make('rin')
                    ->maxLength(255),
                TextInput::make('phon')
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                TextInput::make('fax')
                    ->maxLength(255),
                TextInput::make('www')
                    ->maxLength(255)
                    ->url(),
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
                TextColumn::make('date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('is_active')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('type_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('repo')
                    ->searchable(),
                TextColumn::make('addr_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rin')
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
            ->filters([])
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRepositories::route('/'),
            'create' => CreateRepository::route('/create'),
            'edit' => EditRepository::route('/{record}/edit'),
        ];
    }
}
