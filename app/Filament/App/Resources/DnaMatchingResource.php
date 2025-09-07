<?php

namespace App\Filament\App\Resources;

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

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $navigationLabel = 'DNA Matches';

    protected static string | UnitEnum | null $navigationGroup = 'DNA Analysis';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('user_id')
                            ->required()
                            ->numeric(),
                        Forms\Components\TextInput::make('match_id')
                            ->numeric(),
                        Forms\Components\TextInput::make('match_name')
                            ->maxLength(255),
                        Forms\Components\FileUpload::make('image')
                            ->image(),
                    ]),

                Forms\Components\Section::make('DNA Analysis Results')
                    ->schema([
                        Forms\Components\TextInput::make('total_shared_cm')
                            ->numeric()
                            ->suffix('cM')
                            ->label('Total Shared centiMorgans'),
                        Forms\Components\TextInput::make('largest_cm_segment')
                            ->numeric()
                            ->suffix('cM')
                            ->label('Largest Segment'),
                        Forms\Components\TextInput::make('confidence_level')
                            ->numeric()
                            ->suffix('%')
                            ->label('Confidence Level'),
                        Forms\Components\TextInput::make('predicted_relationship')
                            ->maxLength(255)
                            ->label('Predicted Relationship'),
                        Forms\Components\TextInput::make('shared_segments_count')
                            ->numeric()
                            ->label('Shared Segments Count'),
                        Forms\Components\TextInput::make('match_quality_score')
                            ->numeric()
                            ->suffix('/100')
                            ->label('Match Quality Score'),
                    ]),

                Forms\Components\Section::make('Files')
                    ->schema([
                        Forms\Components\TextInput::make('file1')
                            ->maxLength(255)
                            ->label('DNA File 1'),
                        Forms\Components\TextInput::make('file2')
                            ->maxLength(255)
                            ->label('DNA File 2'),
                    ]),

                Forms\Components\Section::make('Detailed Analysis')
                    ->schema([
                        Forms\Components\Textarea::make('detailed_report')
                            ->label('Detailed Report')
                            ->rows(5)
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('chromosome_breakdown')
                            ->label('Chromosome Breakdown')
                            ->rows(5)
                            ->columnSpanFull(),
                        Forms\Components\DateTimePicker::make('analysis_date')
                            ->label('Analysis Date'),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('match_name')
                    ->searchable()
                    ->sortable()
                    ->label('Match Name'),
                Tables\Columns\TextColumn::make('predicted_relationship')
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
                Tables\Columns\TextColumn::make('total_shared_cm')
                    ->numeric()
                    ->sortable()
                    ->suffix(' cM')
                    ->label('Shared cM'),
                Tables\Columns\TextColumn::make('largest_cm_segment')
                    ->numeric()
                    ->sortable()
                    ->suffix(' cM')
                    ->label('Largest Segment'),
                Tables\Columns\TextColumn::make('confidence_level')
                    ->numeric()
                    ->sortable()
                    ->suffix('%')
                    ->label('Confidence')
                    ->color(fn (?float $state): string => match (true) {
                        $state >= 80 => 'success',
                        $state >= 60 => 'warning',
                        default => 'danger',
                    }),
                Tables\Columns\TextColumn::make('match_quality_score')
                    ->numeric()
                    ->sortable()
                    ->suffix('/100')
                    ->label('Quality Score')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('shared_segments_count')
                    ->numeric()
                    ->sortable()
                    ->label('Segments')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\ImageColumn::make('image')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('analysis_date')
                    ->dateTime()
                    ->sortable()
                    ->label('Analysis Date')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('match_id')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('file1')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('file2')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Filters\SelectFilter::make('predicted_relationship')
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
                Tables\Filters\Filter::make('high_confidence')
                    ->query(fn ($query) => $query->where('confidence_level', '>=', 80))
                    ->label('High Confidence (≥80%)'),
                Tables\Filters\Filter::make('close_matches')
                    ->query(fn ($query) => $query->where('total_shared_cm', '>=', 100))
                    ->label('Close Matches (≥100 cM)'),
            ])
            ->actions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
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
            'index'  => Pages\ListDnaMatchings::route('/'),
            'create' => Pages\CreateDnaMatching::route('/create'),
            'view'   => Pages\ViewDnaMatching::route('/{record}'),
            'edit'   => Pages\EditDnaMatching::route('/{record}/edit'),
        ];
    }
}
