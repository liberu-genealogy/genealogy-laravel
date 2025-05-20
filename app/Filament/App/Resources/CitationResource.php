<?php

namespace App\Filament\App\Resources;

use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\CitationResource\Pages;
use App\Models\Citation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class CitationResource extends Resource
{
    protected static ?string $model = Citation::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Citation';

    protected static string | UnitEnum | null $navigationGroup = 'Author';

    #[\Override]
    public static function form(Schema $form): Schema
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('description')
                ->required()
                ->columnSpanFull(),
            Forms\Components\DateTimePicker::make('date'),
            Forms\Components\TextInput::make('is_active')
                ->required()
                ->numeric(),
            Forms\Components\TextInput::make('volume')
                ->required()
                ->numeric(),
            Forms\Components\TextInput::make('page')
                ->required()
                ->numeric(),
            Forms\Components\TextInput::make('confidence')
                ->required()
                ->numeric(),
            Forms\Components\TextInput::make('source_id')
                ->required()
                ->numeric(),

        ]);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')
                ->searchable(),
            Tables\Columns\TextColumn::make('date')
                ->dateTime()
                ->sortable(),
            Tables\Columns\TextColumn::make('is_active')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('volume')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('page')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('confidence')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('source_id')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    #[\Override]
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCitations::route('/'),
            'create' => Pages\CreateCitation::route('/create'),
            'edit'   => Pages\EditCitation::route('/{record}/edit'),
        ];
    }
}
