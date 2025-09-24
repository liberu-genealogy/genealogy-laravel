<?php

namespace App\Modules\Sources\Filament\Resources;

use App\Models\Source;
use App\Models\Repository;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SourceResource extends Resource
{
    protected static ?string $model = Source::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Research';

    protected static ?string $navigationLabel = 'Sources';

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('author')
                    ->maxLength(255),
                Forms\Components\TextInput::make('publisher')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('publication_date'),
                Forms\Components\Select::make('repository_id')
                    ->label('Repository')
                    ->options(Repository::pluck('name', 'id'))
                    ->searchable(),
                Forms\Components\TextInput::make('call_number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('url')
                    ->url()
                    ->maxLength(500),
                Forms\Components\Textarea::make('description')
                    ->maxLength(1000),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('author')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('publisher')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('publication_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('repository.name')
                    ->label('Repository')
                    ->sortable(),
                Tables\Columns\IconColumn::make('has_url')
                    ->label('Online')
                    ->boolean()
                    ->getStateUsing(fn ($record) => !empty($record->url)),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('repository_id')
                    ->label('Repository')
                    ->options(Repository::pluck('name', 'id')),
                Tables\Filters\TernaryFilter::make('has_url')
                    ->label('Has URL')
                    ->placeholder('All sources')
                    ->trueLabel('With URL')
                    ->falseLabel('Without URL')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('url'),
                        false: fn (Builder $query) => $query->whereNull('url'),
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Modules\Sources\Filament\Resources\SourceResource\Pages\ListSources::route('/'),
            'create' => \App\Modules\Sources\Filament\Resources\SourceResource\Pages\CreateSource::route('/create'),
            'edit' => \App\Modules\Sources\Filament\Resources\SourceResource\Pages\EditSource::route('/{record}/edit'),
        ];
    }
}
