<?php

namespace App\Modules\Sources\Filament\Resources;

use App\Models\Repository;
use App\Models\Source;
use App\Modules\Sources\Filament\Resources\SourceResource\Pages\CreateSource;
use App\Modules\Sources\Filament\Resources\SourceResource\Pages\EditSource;
use App\Modules\Sources\Filament\Resources\SourceResource\Pages\ListSources;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SourceResource extends Resource
{
    #[\Override]
    protected static ?string $model = Source::class;

    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = 'Research';

    #[\Override]
    protected static ?string $navigationLabel = 'Sources';

    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                TextInput::make('author')
                    ->maxLength(255),
                TextInput::make('publisher')
                    ->maxLength(255),
                DatePicker::make('publication_date'),
                Select::make('repository_id')
                    ->label('Repository')
                    ->options(Repository::pluck('name', 'id'))
                    ->searchable(),
                TextInput::make('call_number')
                    ->maxLength(255),
                TextInput::make('url')
                    ->url()
                    ->maxLength(500),
                Textarea::make('description')
                    ->maxLength(1000),
            ]);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('author')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('publisher')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('publication_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('repository.name')
                    ->label('Repository')
                    ->sortable(),
                IconColumn::make('has_url')
                    ->label('Online')
                    ->boolean()
                    ->getStateUsing(fn ($record): bool => ! empty($record->url)),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('repository_id')
                    ->label('Repository')
                    ->options(Repository::pluck('name', 'id')),
                TernaryFilter::make('has_url')
                    ->label('Has URL')
                    ->placeholder('All sources')
                    ->trueLabel('With URL')
                    ->falseLabel('Without URL')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('url'),
                        false: fn (Builder $query) => $query->whereNull('url'),
                    ),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ListSources::route('/'),
            'create' => CreateSource::route('/create'),
            'edit' => EditSource::route('/{record}/edit'),
        ];
    }
}
