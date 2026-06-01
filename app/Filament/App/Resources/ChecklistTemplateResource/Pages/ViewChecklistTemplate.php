<?php

namespace App\Filament\App\Resources\ChecklistTemplateResource\Pages;

use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Grid;
use App\Filament\App\Resources\ChecklistTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;

class ViewChecklistTemplate extends ViewRecord
{
    #[\Override]
    protected static string $resource = ChecklistTemplateResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('use_template')
                ->label('Use This Template')
                ->icon('heroicon-o-plus')
                ->color('success')
                ->url(fn (): string => route('filament.app.pages.create-user-checklist', ['template' => $this->getRecord()->id]))
                ->openUrlInNewTab(),
        ];
    }

    #[\Override]
    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Template Information')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('name')
                                    ->size('lg')
                                    ->weight('bold'),
                                TextEntry::make('category')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'vital_records' => 'success',
                                        'census' => 'info',
                                        'immigration' => 'warning',
                                        'military' => 'danger',
                                        'dna' => 'purple',
                                        default => 'gray',
                                    })
                                    ->formatStateUsing(fn (string $state): string => str_replace('_', ' ', title_case($state))),
                                TextEntry::make('difficulty_level')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'beginner' => 'success',
                                        'intermediate' => 'warning',
                                        'advanced' => 'danger',
                                        default => 'gray',
                                    })
                                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                            ]),
                        TextEntry::make('description')
                            ->columnSpanFull(),
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('estimated_time')
                                    ->suffix(' minutes')
                                    ->label('Estimated Time'),
                                TextEntry::make('templateItems_count')
                                    ->counts('templateItems')
                                    ->label('Total Items'),
                                IconEntry::make('is_public')
                                    ->boolean()
                                    ->label('Public Template'),
                                IconEntry::make('is_default')
                                    ->boolean()
                                    ->label('Default Template'),
                            ]),
                        TextEntry::make('tags')
                            ->badge()
                            ->columnSpanFull()
                            ->visible(fn ($state): bool => !empty($state)),
                    ]),

                Section::make('Checklist Items')
                    ->schema([
                        RepeatableEntry::make('templateItems')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextEntry::make('title')
                                            ->weight('bold')
                                            ->columnSpan(2),
                                        TextEntry::make('estimated_time')
                                            ->suffix(' min')
                                            ->label('Est. Time')
                                            ->alignCenter(),
                                    ]),
                                TextEntry::make('description')
                                    ->columnSpanFull()
                                    ->visible(fn ($state): bool => !empty($state)),
                                Grid::make(3)
                                    ->schema([
                                        TextEntry::make('category')
                                            ->badge()
                                            ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                                        IconEntry::make('is_required')
                                            ->boolean()
                                            ->label('Required'),
                                        TextEntry::make('order')
                                            ->label('Order')
                                            ->alignCenter(),
                                    ]),
                                TextEntry::make('resources')
                                    ->badge()
                                    ->label('Resources')
                                    ->columnSpanFull()
                                    ->visible(fn ($state): bool => !empty($state)),
                                TextEntry::make('tips')
                                    ->badge()
                                    ->label('Tips')
                                    ->columnSpanFull()
                                    ->visible(fn ($state): bool => !empty($state)),
                            ])
                            ->contained(false),
                    ]),

                Section::make('Template Statistics')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('creator.name')
                                    ->label('Created By'),
                                TextEntry::make('created_at')
                                    ->dateTime()
                                    ->label('Created At'),
                                TextEntry::make('userChecklists_count')
                                    ->counts('userChecklists')
                                    ->label('Times Used'),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }
}
