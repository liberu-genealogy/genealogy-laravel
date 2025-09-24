<?php

namespace App\Filament\App\Resources\VirtualEventResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use App\Filament\App\Resources\VirtualEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Schemas\Schema;

class ViewVirtualEvent extends ViewRecord
{
    protected static string $resource = VirtualEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('join_meeting')
                ->icon('heroicon-o-video-camera')
                ->color('success')
                ->url(fn () => $this->getRecord()->join_url ?? '#')
                ->openUrlInNewTab()
                ->visible(fn () => $this->getRecord()->canJoin() && !empty($this->getRecord()->join_url)),
            EditAction::make(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $infolist
            ->schema([
                Section::make('Event Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('title')
                                    ->size('lg')
                                    ->weight('bold')
                                    ->columnSpanFull(),
                                TextEntry::make('description')
                                    ->columnSpanFull(),
                                TextEntry::make('formatted_start_time')
                                    ->label('Start Time'),
                                TextEntry::make('formatted_end_time')
                                    ->label('End Time'),
                                TextEntry::make('duration_in_minutes')
                                    ->label('Duration')
                                    ->suffix(' minutes'),
                                TextEntry::make('timezone'),
                            ]),
                    ]),

                Section::make('Event Status & Settings')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'draft' => 'gray',
                                        'published' => 'success',
                                        'started' => 'warning',
                                        'ended' => 'gray',
                                        'cancelled' => 'danger',
                                    }),
                                IconEntry::make('require_rsvp')
                                    ->label('Requires RSVP')
                                    ->boolean(),
                                IconEntry::make('allow_guests')
                                    ->label('Allows Guests')
                                    ->boolean(),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('max_attendees')
                                    ->label('Maximum Attendees')
                                    ->placeholder('Unlimited'),
                                TextEntry::make('attendee_count')
                                    ->label('Current Attendees'),
                            ]),
                    ]),

                Section::make('Video Conferencing')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('platform')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'zoom' => 'primary',
                                        'google_meet' => 'success',
                                        'teams' => 'warning',
                                        'custom' => 'gray',
                                    })
                                    ->formatStateUsing(fn (string $state): string => match ($state) {
                                        'zoom' => 'Zoom',
                                        'google_meet' => 'Google Meet',
                                        'teams' => 'Microsoft Teams',
                                        'custom' => 'Custom Platform',
                                        default => ucfirst($state),
                                    }),
                                TextEntry::make('meeting_id')
                                    ->label('Meeting ID')
                                    ->copyable(),
                            ]),
                        TextEntry::make('join_url')
                            ->label('Join URL')
                            ->url(fn ($state) => $state)
                            ->openUrlInNewTab()
                            ->copyable()
                            ->columnSpanFull()
                            ->visible(fn ($record) => !empty($record->join_url)),
                        TextEntry::make('meeting_password')
                            ->label('Meeting Password')
                            ->copyable()
                            ->visible(fn ($record) => !empty($record->meeting_password)),
                        TextEntry::make('instructions')
                            ->label('Special Instructions')
                            ->columnSpanFull()
                            ->visible(fn ($record) => !empty($record->instructions)),
                    ])
                    ->visible(fn ($record) => !empty($record->meeting_id) || !empty($record->join_url)),

                Section::make('Attendance Summary')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('attendee_count')
                                    ->label('Total Attendees')
                                    ->badge()
                                    ->color('gray'),
                                TextEntry::make('accepted_count')
                                    ->label('Accepted')
                                    ->badge()
                                    ->color('success'),
                                TextEntry::make('pending_attendees_count')
                                    ->label('Pending')
                                    ->badge()
                                    ->color('warning')
                                    ->state(fn ($record) => $record->pendingAttendees()->count()),
                                TextEntry::make('actual_attendee_count')
                                    ->label('Actually Attended')
                                    ->badge()
                                    ->color('primary')
                                    ->visible(fn ($record) => $record->is_past),
                            ]),
                    ]),

                Section::make('Event Management')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('creator.name')
                                    ->label('Created By'),
                                TextEntry::make('created_at')
                                    ->label('Created At')
                                    ->dateTime(),
                            ]),
                    ]),
            ]);
    }
}
