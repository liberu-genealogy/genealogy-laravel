<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\CitationResource\Pages\ListCitations;
use App\Filament\App\Resources\CitationResource\Pages\CreateCitation;
use App\Filament\App\Resources\CitationResource\Pages\EditCitation;
use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\CitationResource\Pages;
use App\Models\Citation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class CitationResource extends Resource
{
    protected static ?string $model = Citation::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Citation';

    protected static string | \UnitEnum | null $navigationGroup = '🔍 Research & Analysis';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
        ->components([
            TextInput::make('name')
                ->required()
                ->maxLength(255),
            Textarea::make('description')
                ->required()
                ->columnSpanFull(),
            DateTimePicker::make('date'),
            TextInput::make('is_active')
                ->required()
                ->numeric(),
            TextInput::make('volume')
                ->required()
                ->numeric(),
            TextInput::make('page')
                ->required()
                ->numeric(),
            TextInput::make('confidence')
                ->required()
                ->numeric(),
            TextInput::make('source_id')
                ->required()
                ->numeric(),

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
                ->dateTime()
                ->sortable(),
            TextColumn::make('is_active')
                ->numeric()
                ->sortable(),
            TextColumn::make('volume')
                ->numeric()
                ->sortable(),
            TextColumn::make('page')
                ->numeric()
                ->sortable(),
            TextColumn::make('confidence')
                ->numeric()
                ->sortable(),
            TextColumn::make('source_id')
                ->numeric()
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
            'index'  => ListCitations::route('/'),
            'create' => CreateCitation::route('/create'),
            'edit'   => EditCitation::route('/{record}/edit'),
        ];
    }
}
