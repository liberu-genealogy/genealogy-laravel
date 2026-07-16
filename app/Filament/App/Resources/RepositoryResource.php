<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\RepositoryResource\Pages\CreateRepository;
use App\Filament\App\Resources\RepositoryResource\Pages\EditRepository;
use App\Filament\App\Resources\RepositoryResource\Pages\ListRepositories;
use App\Models\Repository;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Override;

final class RepositoryResource extends AppResource
{
    #[Override]
    protected static ?string $model = Repository::class;

    #[Override]
    protected static ?string $navigationLabel = 'Repository';

    #[Override]
    protected static string|\UnitEnum|null $navigationGroup = '🔍 Research & Analysis';

    #[Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-library';

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
                Select::make('type_id')
                    ->relationship('type', 'name')
                    ->searchable()
                    ->preload(),
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

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ListRepositories::route('/'),
            'create' => CreateRepository::route('/create'),
            'edit' => EditRepository::route('/{record}/edit'),
        ];
    }
}
