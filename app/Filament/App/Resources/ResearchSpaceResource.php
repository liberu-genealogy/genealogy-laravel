<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ResearchSpaceResource\Pages\CreateResearchSpace;
use App\Filament\App\Resources\ResearchSpaceResource\Pages\EditResearchSpace;
use App\Filament\App\Resources\ResearchSpaceResource\Pages\ListResearchSpaces;
use App\Models\ResearchSpace;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ResearchSpaceResource extends AppResource
{
    #[\Override]
    protected static ?string $model = ResearchSpace::class;

    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = '📋 Research Workspace';

    #[\Override]
    protected static ?string $navigationLabel = 'Research Spaces';

    #[\Override]
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

    #[\Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('owner.name')->label('Owner')->sortable(),
                IconColumn::make('is_private')->boolean()->label('Private'),
                TextColumn::make('created_at')->label('Created')->dateTime(),
            ]);
    }

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ListResearchSpaces::route('/'),
            'create' => CreateResearchSpace::route('/create'),
            'edit' => EditResearchSpace::route('/{record}/edit'),
        ];
    }
}
