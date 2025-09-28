<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\SourceRepoResource\Pages\ListSourceRepos;
use App\Filament\App\Resources\SourceRepoResource\Pages\CreateSourceRepo;
use App\Filament\App\Resources\SourceRepoResource\Pages\EditSourceRepo;
use BackedEnum;
use App\Filament\App\Resources\SourceRepoResource\Pages;
use App\Models\SourceRepo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

final class SourceRepoResource extends Resource
{
    protected static ?string $model = SourceRepo::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = '\ud83d\udd0d Research & Analysis';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('group')
                    ->required()
                    ->maxLength(255),
                TextInput::make('gid')
                    ->required()
                    ->numeric(),
                TextInput::make('repo_id')
                    ->required()
                    ->maxLength(255),
                Textarea::make('caln')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
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
                TextColumn::make('repo_id')
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
            'index' => ListSourceRepos::route('/'),
            'create' => CreateSourceRepo::route('/create'),
            'edit' => EditSourceRepo::route('/{record}/edit'),
        ];
    }
}
