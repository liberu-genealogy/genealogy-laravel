<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResearchSpaceResource\Pages;
use App\Models\ResearchSpace;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;

class ResearchSpaceResource extends Resource
{
    protected static ?string $model = ResearchSpace::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Collaboration';

    protected static ?string $navigationLabel = 'Research Spaces';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Details')
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('description')
                                    ->rows(4),
                                Toggle::make('is_private')
                                    ->label('Private')
                                    ->default(true),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('owner.name')->label('Owner')->sortable(),
                TextColumn::make('is_private')->boolean()->label('Private'),
                TextColumn::make('created_at')->label('Created')->dateTime(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResearchSpaces::route('/'),
            'create' => Pages\CreateResearchSpace::route('/create'),
            'edit' => Pages\EditResearchSpace::route('/{record}/edit'),
        ];
    }
}
