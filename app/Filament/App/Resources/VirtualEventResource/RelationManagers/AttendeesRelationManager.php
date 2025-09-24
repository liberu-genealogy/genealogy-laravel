<?php

namespace App\Filament\App\Resources\VirtualEventResource\RelationManagers;

use App\Models\VirtualEventAttendee;
use App\Models\User;
use App\Models\Person;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendeesRelationManager extends RelationManager
{
    protected static string $relationship = 'attendees';

    protected static ?string $title = 'Event Attendees';

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Attendee Information')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('User')
                            ->options(User::all()->pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $set('person_id', null);
                                    $set('guest_name', null);
                                    $set('guest_email', null);
                                }
                            }),
                        Forms\Components\Select::make('person_id')
                            ->label('Person (from Family Tree)')
                            ->options(Person::all()->pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $set('user_id', null);
                                    $set('guest_name', null);
                                    $set('guest_email', null);
                                }
                            })
                            ->visible(fn (callable $get) => !$get('user_id')),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('guest_name')
                                    ->label('Guest Name')
                                    ->maxLength(255)
                                    ->visible(fn (callable $get) => !$get('user_id') && !$get('person_id')),
                                Forms\Components\TextInput::make('guest_email')
                                    ->label('Guest Email')
                                    ->email()
                                    ->maxLength(255)
                                    ->visible(fn (callable $get) => !$get('user_id') && !$get('person_id')),
                            ]),
                    ]),

                Forms\Components\Section::make('RSVP & Participation')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('rsvp_status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'accepted' => 'Accepted',
                                        'declined' => 'Declined',
                                        'maybe' => 'Maybe',
                                    ])
                                    ->required()
                                    ->default('pending'),
                                Forms\Components\DateTimePicker::make('rsvp_date')
                                    ->label('RSVP Date')
                                    ->native(false),
                            ]),
                        Forms\Components\Textarea::make('rsvp_notes')
                            ->label('RSVP Notes')
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('is_host')
                                    ->label('Event Host'),
                                Forms\Components\Toggle::make('is_moderator')
                                    ->label('Event Moderator'),
                            ]),
                    ]),

                Forms\Components\Section::make('Attendance Tracking')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('attended')
                                    ->label('Actually Attended')
                                    ->live(),
                                Forms\Components\TextInput::make('duration_minutes')
                                    ->label('Duration (minutes)')
                                    ->numeric()
                                    ->visible(fn (callable $get) => $get('attended')),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\DateTimePicker::make('joined_at')
                                    ->label('Joined At')
                                    ->native(false)
                                    ->visible(fn (callable $get) => $get('attended')),
                                Forms\Components\DateTimePicker::make('left_at')
                                    ->label('Left At')
                                    ->native(false)
                                    ->visible(fn (callable $get) => $get('attended')),
                            ]),
                    ])
                    ->visible(fn () => $this->getOwnerRecord()->is_past || $this->getOwnerRecord()->is_active),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('display_name')
            ->columns([
                Tables\Columns\TextColumn::make('display_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('display_email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\BadgeColumn::make('rsvp_status')
                    ->label('RSVP')
                    ->colors([
                        'success' => 'accepted',
                        'danger' => 'declined',
                        'warning' => 'maybe',
                        'gray' => 'pending',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'accepted' => 'Accepted',
                        'declined' => 'Declined',
                        'maybe' => 'Maybe',
                        'pending' => 'Pending',
                        default => ucfirst($state),
                    }),
                Tables\Columns\TextColumn::make('rsvp_date')
                    ->label('RSVP Date')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('attended')
                    ->label('Attended')
                    ->boolean()
                    ->alignCenter()
                    ->visible(fn () => $this->getOwnerRecord()->is_past || $this->getOwnerRecord()->is_active),
                Tables\Columns\TextColumn::make('attendance_duration')
                    ->label('Duration')
                    ->visible(fn () => $this->getOwnerRecord()->is_past)
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_host')
                    ->label('Host')
                    ->boolean()
                    ->alignCenter()
                    ->toggleable(),
                Tables\Columns\IconColumn::make('is_moderator')
                    ->label('Moderator')
                    ->boolean()
                    ->alignCenter()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('rsvp_status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'declined' => 'Declined',
                        'maybe' => 'Maybe',
                    ]),
                Tables\Filters\TernaryFilter::make('attended')
                    ->label('Actually Attended')
                    ->visible(fn () => $this->getOwnerRecord()->is_past || $this->getOwnerRecord()->is_active),
                Tables\Filters\TernaryFilter::make('is_host')
                    ->label('Event Hosts'),
                Tables\Filters\TernaryFilter::make('is_moderator')
                    ->label('Event Moderators'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Attendee'),
                Tables\Actions\Action::make('bulk_invite')
                    ->label('Bulk Invite')
                    ->icon('heroicon-o-envelope')
                    ->color('primary')
                    ->form([
                        Forms\Components\Select::make('users')
                            ->label('Select Users')
                            ->multiple()
                            ->options(User::all()->pluck('name', 'id'))
                            ->searchable(),
                        Forms\Components\Select::make('people')
                            ->label('Select People from Family Tree')
                            ->multiple()
                            ->options(Person::all()->pluck('name', 'id'))
                            ->searchable(),
                        Forms\Components\TagsInput::make('guest_emails')
                            ->label('Guest Email Addresses')
                            ->placeholder('Enter email addresses'),
                    ])
                    ->action(function (array $data) {
                        $event = $this->getOwnerRecord();
                        $added = 0;

                        // Add selected users
                        if (!empty($data['users'])) {
                            foreach ($data['users'] as $userId) {
                                if (!$event->hasUser(User::find($userId))) {
                                    VirtualEventAttendee::create([
                                        'virtual_event_id' => $event->id,
                                        'user_id' => $userId,
                                        'rsvp_status' => 'pending',
                                    ]);
                                    $added++;
                                }
                            }
                        }

                        // Add selected people
                        if (!empty($data['people'])) {
                            foreach ($data['people'] as $personId) {
                                if (!$event->hasPerson(Person::find($personId))) {
                                    VirtualEventAttendee::create([
                                        'virtual_event_id' => $event->id,
                                        'person_id' => $personId,
                                        'rsvp_status' => 'pending',
                                    ]);
                                    $added++;
                                }
                            }
                        }

                        // Add guest emails
                        if (!empty($data['guest_emails'])) {
                            foreach ($data['guest_emails'] as $email) {
                                VirtualEventAttendee::create([
                                    'virtual_event_id' => $event->id,
                                    'guest_email' => $email,
                                    'guest_name' => explode('@', $email)[0],
                                    'rsvp_status' => 'pending',
                                ]);
                                $added++;
                            }
                        }

                        Notification::make()
                            ->title('Attendees Added')
                            ->body("Successfully added {$added} attendees to the event.")
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('accept')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(fn (VirtualEventAttendee $record) => $record->accept())
                    ->visible(fn (VirtualEventAttendee $record) => $record->rsvp_status !== 'accepted'),
                Tables\Actions\Action::make('decline')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->action(fn (VirtualEventAttendee $record) => $record->decline())
                    ->visible(fn (VirtualEventAttendee $record) => $record->rsvp_status !== 'declined'),
                Tables\Actions\Action::make('mark_attended')
                    ->icon('heroicon-o-user-check')
                    ->color('primary')
                    ->action(fn (VirtualEventAttendee $record) => $record->markAsAttended())
                    ->visible(fn (VirtualEventAttendee $record) => !$record->attended && ($this->getOwnerRecord()->is_past || $this->getOwnerRecord()->is_active)),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('accept_all')
                        ->label('Accept All')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->accept();
                            }
                            Notification::make()
                                ->title('RSVPs Updated')
                                ->body('All selected attendees have been marked as accepted.')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\BulkAction::make('mark_all_attended')
                        ->label('Mark All as Attended')
                        ->icon('heroicon-o-user-check')
                        ->color('primary')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->markAsAttended();
                            }
                            Notification::make()
                                ->title('Attendance Updated')
                                ->body('All selected attendees have been marked as attended.')
                                ->success()
                                ->send();
                        })
                        ->visible(fn () => $this->getOwnerRecord()->is_past || $this->getOwnerRecord()->is_active),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
