<?php

namespace App\Filament\App\Resources;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\DnaMatchingResource\Pages\ListDnaMatchings;
use App\Filament\App\Resources\DnaMatchingResource\Pages\CreateDnaMatching;
use App\Filament\App\Resources\DnaMatchingResource\Pages\ViewDnaMatching;
use App\Filament\App\Resources\DnaMatchingResource\Pages\EditDnaMatching;
use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\DnaMatchingResource\Pages;
use App\Models\DnaMatching;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class DnaMatchingResource extends Resource
{
    protected static bool $isScopedToTenant = false;

    protected static ?string $model = DnaMatching::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $navigationLabel = 'DNA Matches';

    protected static string | \UnitEnum | null $navigationGroup = '\ud83e\uddec DNA & Genetics';

    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return config('premium.enabled') && (auth()->user()?->isPremium() ?? false);
    }

       public static function form(Schema $schema): Schema
       {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('user_id')
                            ->required()
                            ->numeric(),
                        TextInput::make('match_id')
                            ->numeric(),
                        TextInput::make('match_name')
                            ->maxLength(255),
                        FileUpload::make('image')
                            ->image(),
                    ]),

                Section::make('DNA Analysis Results')
                    ->schema([
                        TextInput::make('total_shared_cm')
                            ->numeric()
                            ->suffix('cM')
                            ->label('Total Shared centiMorgans'),
                        TextInput::make('largest_cm_segment')
                            ->numeric()
                            ->suffix('cM')
                            ->label('Largest Segment'),
                        TextInput::make('confidence_level')
                            ->numeric()
                            ->suffix('%')
                            ->label('Confidence Level'),
                        TextInput::make('predicted_relationship')
                            ->maxLength(255)
                            ->label('Predicted Relationship'),
                        TextInput::make('shared_segments_count')
                            ->numeric()
                            ->label('Shared Segments Count'),
                        TextInput::make('match_quality_score')
                            ->numeric()
                            ->suffix('/100')
                            ->label('Match Quality Score'),
                    ]),

                Section::make('Files')
                    ->schema([
                        TextInput::make('file1')
                            ->maxLength(255)
                            ->label('DNA File 1'),
                        TextInput::make('file2')
                            ->maxLength(255)
                            ->label('DNA File 2'),
                    ]),

                Section::make('Detailed Analysis')
                    ->schema([
                        Textarea::make('detailed_report')
                            ->label('Detailed Report')
                            ->rows(5)
                            ->columnSpanFull(),
                        Textarea::make('chromosome_breakdown')
                            ->label('Chromosome Breakdown')
                            ->rows(5)
                            ->columnSpanFull(),
                        DateTimePicker::make('analysis_date')
                            ->label('Analysis Date'),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('match_name')
                    ->searchable()
                    ->sortable()
                    ->label('Match Name'),
                TextColumn::make('predicted_relationship')
                    ->searchable()
                    ->sortable()
                    ->label('Relationship')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Parent/Child' => 'success',
                        'Full Sibling' => 'success',
                        'Grandparent/Grandchild' => 'warning',
                        'First Cousin' => 'info',
                        'Second Cousin' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('total_shared_cm')
                    ->numeric()
                    ->sortable()
                    ->suffix(' cM')
                    ->label('Shared cM'),
                TextColumn::make('largest_cm_segment')
                    ->numeric()
                    ->sortable()
                    ->suffix(' cM')
                    ->label('Largest Segment'),
                TextColumn::make('confidence_level')
                    ->numeric()
                    ->sortable()
                    ->suffix('%')
                    ->label('Confidence')
                    ->color(fn (?float $state): string => match (true) {
                        $state >= 80 => 'success',
                        $state >= 60 => 'warning',
                        default => 'danger',
                    }),
                TextColumn::make('match_quality_score')
                    ->numeric()
                    ->sortable()
                    ->suffix('/100')
                    ->label('Quality Score')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('shared_segments_count')
                    ->numeric()
                    ->sortable()
                    ->label('Segments')
                    ->toggleable(isToggledHiddenByDefault: true),
                ImageColumn::make('image')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('analysis_date')
                    ->dateTime()
                    ->sortable()
                    ->label('Analysis Date')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('match_id')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('file1')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('file2')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                SelectFilter::make('predicted_relationship')
                    ->options([
                        'Parent/Child' => 'Parent/Child',
                        'Full Sibling' => 'Full Sibling',
                        'Grandparent/Grandchild' => 'Grandparent/Grandchild',
                        'Aunt/Uncle or Half Sibling' => 'Aunt/Uncle or Half Sibling',
                        'First Cousin' => 'First Cousin',
                        'First Cousin Once Removed' => 'First Cousin Once Removed',
                        'Second Cousin' => 'Second Cousin',
                        'Third Cousin' => 'Third Cousin',
                        'Distant Cousin' => 'Distant Cousin',
                    ])
                    ->label('Relationship'),
                Filter::make('high_confidence')
                    ->query(fn ($query) => $query->where('confidence_level', '>=', 80))
                    ->label('High Confidence (≥80%)'),
                Filter::make('close_matches')
                    ->query(fn ($query) => $query->where('total_shared_cm', '>=', 100))
                    ->label('Close Matches (≥100 cM)'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('total_shared_cm', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListDnaMatchings::route('/'),
            'create' => CreateDnaMatching::route('/create'),
            'view'   => ViewDnaMatching::route('/{record}'),
            'edit'   => EditDnaMatching::route('/{record}/edit'),
        ];
    }
}
