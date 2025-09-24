<?php

namespace App\Filament\App\Resources\VirtualEventResource\Pages;

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
            Actions\Action::make('join_meeting')
                ->icon('heroicon-o-video-camera')
                ->color('success')
                ->url(fn () => $this->getRecord()->join_url ?? '#')
                ->openUrlInNewTab()
                ->visible(fn () => $this->getRecord()->canJoin() && !empty($this->getRecord()->join_url)),
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Schema $schema): Schema
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Event Details')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('title')
                                    ->size('lg')
                                    ->weight('bold')
                                    ->columnSpanFull(),
                                Infolists\Components\TextEntry::make('description')
                                    ->columnSpanFull(),
                                Infolists\Components\TextEntry::make('formatted_start_time')
                                    ->label('Start Time'),
                                Infolists\Components\TextEntry::make('formatted_end_time')
                                    ->label('End Time'),
                                Infolists\Components\TextEntry::make('duration_in_minutes')
                                    ->label('Duration')
                                    ->suffix(' minutes'),
                                Infolists\Components\TextEntry::make('timezone'),
                            ]),
                    ]),

                Infolists\Components\Section::make('Event Status & Settings')
                    ->schema([
                        Infolists\Components\Grid::make(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'draft' => 'gray',
                                        'published' => 'success',
                                        'started' => 'warning',
                                        'ended' => 'gray',
                                        'cancelled' => 'danger',
                                    }),
                                Infolists\Components\IconEntry::make('require_rsvp')
                                    ->label('Requires RSVP')
                                    ->boolean(),
                                Infolists\Components\IconEntry::make('allow_guests')
                                    ->label('Allows Guests')
                                    ->boolean(),
                            ]),
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('max_attendees')
                                    ->label('Maximum Attendees')
                                    ->placeholder('Unlimited'),
                                Infolists\Components\TextEntry::make('attendee_count')
                                    ->label('Current Attendees'),
                            ]),
                    ]),

                Infolists\Components\Section::make('Video Conferencing')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('platform')
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
                                Infolists\Components\TextEntry::make('meeting_id')
                                    ->label('Meeting ID')
                                    ->copyable(),
                            ]),
                        Infolists\Components\TextEntry::make('join_url')
                            ->label('Join URL')
                            ->url(fn ($state) => $state)
                            ->openUrlInNewTab()
                            ->copyable()
                            ->columnSpanFull()
                            ->visible(fn ($record) => !empty($record->join_url)),
                        Infolists\Components\TextEntry::make('meeting_password')
                            ->label('Meeting Password')
                            ->copyable()
                            ->visible(fn ($record) => !empty($record->meeting_password)),
                        Infolists\Components\TextEntry::make('instructions')
                            ->label('Special Instructions')
                            ->columnSpanFull()
                            ->visible(fn ($record) => !empty($record->instructions)),
                    ])
                    ->visible(fn ($record) => !empty($record->meeting_id) || !empty($record->join_url)),

                Infolists\Components\Section::make('Attendance Summary')
                    ->schema([
                        Infolists\Components\Grid::make(4)
                            ->schema([
                                Infolists\Components\TextEntry::make('attendee_count')
                                    ->label('Total Attendees')
                                    ->badge()
                                    ->color('gray'),
                                Infolists\Components\TextEntry::make('accepted_count')
                                    ->label('Accepted')
                                    ->badge()
                                    ->color('success'),
                                Infolists\Components\TextEntry::make('pending_attendees_count')
                                    ->label('Pending')
                                    ->badge()
                                    ->color('warning')
                                    ->state(fn ($record) => $record->pendingAttendees()->count()),
                                Infolists\Components\TextEntry::make('actual_attendee_count')
                                    ->label('Actually Attended')
                                    ->badge()
                                    ->color('primary')
                                    ->visible(fn ($record) => $record->is_past),
                            ]),
                    ]),

                Infolists\Components\Section::make('Event Management')
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('creator.name')
                                    ->label('Created By'),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label('Created At')
                                    ->dateTime(),
                            ]),
                    ]),
            ]);
    }
}
