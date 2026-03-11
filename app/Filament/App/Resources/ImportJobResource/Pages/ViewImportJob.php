<?php

namespace App\Filament\App\Resources\ImportJobResource\Pages;

use App\Filament\App\Resources\ImportJobResource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Schema;

class ViewImportJob extends ViewRecord
{
    protected static string $resource = ImportJobResource::class;

    /** Auto-refresh every 3 seconds while import is in progress. */
    protected static ?string $pollingInterval = '3s';

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Import Status')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('slug')
                                    ->label('Import ID')
                                    ->copyable(),
                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'complete'   => 'success',
                                        'failed'     => 'danger',
                                        'processing' => 'info',
                                        'queue'      => 'warning',
                                        default      => 'gray',
                                    }),
                                TextEntry::make('progress')
                                    ->label('Progress')
                                    ->formatStateUsing(fn (int $state): string => $state . '%')
                                    ->color(fn (int $state): string => match (true) {
                                        $state === 100 => 'success',
                                        $state >= 50   => 'info',
                                        $state > 0     => 'warning',
                                        default        => 'gray',
                                    }),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('people_imported')
                                    ->label('People Imported')
                                    ->numeric(),
                                TextEntry::make('families_imported')
                                    ->label('Families Imported')
                                    ->numeric(),
                            ]),
                    ]),

                Section::make('Error Details')
                    ->schema([
                        TextEntry::make('error_message')
                            ->label('Error Message')
                            ->columnSpanFull()
                            ->color('danger')
                            ->placeholder('No errors'),
                    ])
                    ->collapsible(),

                Section::make('Timestamps')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Queued At')
                                    ->dateTime(),
                                TextEntry::make('updated_at')
                                    ->label('Last Updated')
                                    ->dateTime()
                                    ->since(),
                            ]),
                    ])
                    ->collapsible(),
            ]);
    }
}
