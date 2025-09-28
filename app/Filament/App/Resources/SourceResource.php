<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\SourceResource\Pages\ListSources;
use App\Filament\App\Resources\SourceResource\Pages\CreateSource;
use App\Filament\App\Resources\SourceResource\Pages\EditSource;
use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\SourceResource\Pages;
use App\Models\Source;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class SourceResource extends Resource
{
    protected static ?string $model = Source::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Sources';

    protected static string | \UnitEnum | null $navigationGroup = '🔍 Research & Analysis';

    protected static ?int $navigationSort = 1;

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                ->maxLength(255),
                Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                TextInput::make('date')
                    ->maxLength(255),
                TextInput::make('is_active')
                    ->numeric(),
                TextInput::make('author_id')
                    ->numeric(),
                TextInput::make('repository_id')
                    ->numeric(),
                TextInput::make('publication_id')
                    ->numeric(),
                TextInput::make('type_id')
                    ->numeric(),
                TextInput::make('sour')
                    ->maxLength(255),
                Textarea::make('titl')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                TextInput::make('auth')
                    ->maxLength(255),
                TextInput::make('data')
                    ->maxLength(255),
                Textarea::make('text')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Textarea::make('publ')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                TextInput::make('abbr')
                    ->maxLength(255),
                TextInput::make('group')
                    ->maxLength(255),
                TextInput::make('gid')
                    ->numeric(),
                TextInput::make('quay')
                    ->maxLength(255),
                Textarea::make('page')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                TextInput::make('rin')
                    ->maxLength(255),
                TextInput::make('note')
                    ->maxLength(255),
            ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->searchable(),
                TextColumn::make('date')
                    ->searchable(),
                TextColumn::make('is_active')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('author_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('repository_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('publication_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('type_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sour')
                    ->searchable(),
                TextColumn::make('auth')
                    ->searchable(),
                TextColumn::make('data')
                    ->searchable(),
                TextColumn::make('abbr')
                    ->searchable(),
                TextColumn::make('group')
                    ->searchable(),
                TextColumn::make('gid')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('quay')
                    ->searchable(),
                TextColumn::make('rin')
                    ->searchable(),
                TextColumn::make('note')
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
            'index'  => ListSources::route('/'),
            'create' => CreateSource::route('/create'),
            'edit'   => EditSource::route('/{record}/edit'),
        ];
    }
}
