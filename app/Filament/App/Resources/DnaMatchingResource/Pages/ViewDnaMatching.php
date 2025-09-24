<?php

namespace App\Filament\App\Resources\DnaMatchingResource\Pages;

use App\Filament\App\Resources\DnaMatchingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\KeyValueEntry;

class ViewDnaMatching extends ViewRecord
{
    protected static string $resource = DnaMatchingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
    public function infolist(Schema $schema): Schema
    {
        return $infolist
            ->schema([
                Section::make('Match Overview')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('match_name')
                                    ->label('Match Name')
                                    ->size('lg')
                                    ->weight('bold'),
                                TextEntry::make('predicted_relationship')
                                    ->label('Predicted Relationship')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'Parent/Child' => 'success',
                                        'Full Sibling' => 'success',
                                        'Grandparent/Grandchild' => 'warning',
                                        'First Cousin' => 'info',
                                        'Second Cousin' => 'gray',
                                        default => 'gray',
                                    }),
                                TextEntry::make('confidence_level')
                                    ->label('Confidence Level')
                                    ->suffix('%')
                                    ->color(fn (?float $state): string => match (true) {
                                        $state >= 80 => 'success',
                                        $state >= 60 => 'warning',
                                        default => 'danger',
                                    })
                                    ->weight('bold'),
                            ]),
                    ]),

                Section::make('DNA Analysis Results')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('total_shared_cm')
                                    ->label('Total Shared cM')
                                    ->suffix(' cM')
                                    ->size('lg')
                                    ->weight('bold')
                                    ->color('primary'),
                                TextEntry::make('largest_cm_segment')
                                    ->label('Largest Segment')
                                    ->suffix(' cM')
                                    ->size('lg')
                                    ->weight('bold')
                                    ->color('primary'),
                                TextEntry::make('shared_segments_count')
                                    ->label('Shared Segments')
                                    ->numeric(),
                                TextEntry::make('match_quality_score')
                                    ->label('Quality Score')
                                    ->suffix('/100')
                                    ->color(fn (?float $state): string => match (true) {
                                        $state >= 80 => 'success',
                                        $state >= 60 => 'warning',
                                        default => 'danger',
                                    }),
                            ]),
                    ]),

                Section::make('Chromosome Breakdown')
                    ->schema([
                        KeyValueEntry::make('chromosome_breakdown')
                            ->label('')
                            ->keyLabel('Chromosome')
                            ->valueLabel('Shared cM')
                            ->formatStateUsing(function ($state) {
                                if (!is_array($state)) {
                                    return 'No chromosome data available';
                                }

                                $formatted = [];
                                foreach ($state as $chr => $data) {
                                    if (isset($data['total_cm']) && $data['total_cm'] > 0) {
                                        $formatted["Chr {$chr}"] = round($data['total_cm'], 2) . ' cM (' . $data['segment_count'] . ' segments)';
                                    }
                                }
                                return $formatted;
                            }),
                    ])
                    ->collapsible(),

                Section::make('Detailed Analysis Report')
                    ->schema([
                        TextEntry::make('detailed_report.analysis_notes')
                            ->label('Analysis Notes')
                            ->listWithLineBreaks()
                            ->formatStateUsing(function ($state) {
                                if (is_array($state)) {
                                    return implode("\n", $state);
                                }
                                return $state ?? 'No analysis notes available';
                            }),
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('detailed_report.shared_snps_count')
                                    ->label('Shared SNPs Count')
                                    ->numeric()
                                    ->formatStateUsing(fn ($state) => number_format($state ?? 0)),
                                TextEntry::make('analysis_date')
                                    ->label('Analysis Date')
                                    ->dateTime(),
                            ]),
                    ])
                    ->collapsible(),

                Section::make('Files and Visualization')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('file1')
                                    ->label('Discordant SNPs File')
                                    ->copyable(),
                                TextEntry::make('file2')
                                    ->label('Shared DNA File')
                                    ->copyable(),
                            ]),
                        ImageEntry::make('image')
                            ->label('DNA Match Visualization')
                            ->height(400)
                            ->width('100%'),
                    ])
                    ->collapsible(),

                Section::make('Technical Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('user_id')
                                    ->label('User ID')
                                    ->numeric(),
                                TextEntry::make('match_id')
                                    ->label('Match User ID')
                                    ->numeric(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Created At')
                                    ->dateTime(),
                                TextEntry::make('updated_at')
                                    ->label('Updated At')
                                    ->dateTime(),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }
}
