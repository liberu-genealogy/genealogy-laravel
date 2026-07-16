<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources\ImportJobResource\Pages;

use App\Filament\Admin\Resources\ImportJobResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewImportJob extends ViewRecord
{
    #[\Override]
    protected static string $resource = ImportJobResource::class;

    /** Auto-refresh while an import is in progress. */
    protected static ?string $pollingInterval = '5s';

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [];
    }

    #[\Override]
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
                                TextEntry::make('team.name')
                                    ->label('Team')
                                    ->placeholder('—'),
                                TextEntry::make('user_id')
                                    ->label('User ID'),
                            ]),
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (?string $state): string => match ($state) {
                                        'complete'   => 'success',
                                        'failed'     => 'danger',
                                        'processing' => 'info',
                                        'queue'      => 'warning',
                                        default      => 'gray',
                                    }),
                                TextEntry::make('progress')
                                    ->formatStateUsing(fn (int $state): string => $state . '%'),
                                TextEntry::make('created_at')
                                    ->label('Queued At')
                                    ->dateTime(),
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
            ]);
    }
}
