<?php

namespace App\Modules\Admin\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Modules\Admin\Filament\Resources\TypeResource\Pages\ListTypes;
use App\Modules\Admin\Filament\Resources\TypeResource\Pages\CreateType;
use App\Modules\Admin\Filament\Resources\TypeResource\Pages\EditType;
use App\Models\Type;

class TypeResource extends Resource
{
    #[\Override]
    protected static ?string $model = Type::class;

    #[\Override]
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-tag';

    #[\Override]
    protected static string | \UnitEnum | null $navigationGroup = 'Administration';

    #[\Override]
    protected static ?string $navigationLabel = 'Types';

    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                TextInput::make('is_active')
                    ->required()
                    ->numeric(),
            ]);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->limit(50)
                    ->tooltip(fn($record) => $record->description),
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
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
                TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All types')
                    ->trueLabel('Active types')
                    ->falseLabel('Inactive types'),
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
            'index' => ListTypes::route('/'),
            'create' => CreateType::route('/create'),
            'edit' => EditType::route('/{record}/edit'),
        ];
    }
}
