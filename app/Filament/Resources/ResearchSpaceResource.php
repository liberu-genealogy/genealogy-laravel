<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Tables\Table;
use App\Filament\Resources\ResearchSpaceResource\Pages\ListResearchSpaces;
use App\Filament\Resources\ResearchSpaceResource\Pages\CreateResearchSpace;
use App\Filament\Resources\ResearchSpaceResource\Pages\EditResearchSpace;
use App\Filament\Resources\ResearchSpaceResource\Pages;
use App\Models\ResearchSpace;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class ResearchSpaceResource extends Resource
{
    protected static ?string $model = ResearchSpace::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-users';

    protected static string | \UnitEnum | null $navigationGroup = 'Collaboration';

    protected static ?string $navigationLabel = 'Research Spaces';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
            'index' => ListResearchSpaces::route('/'),
            'create' => CreateResearchSpace::route('/create'),
            'edit' => EditResearchSpace::route('/{record}/edit'),
        ];
    }
}
