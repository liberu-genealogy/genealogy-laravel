<?php

namespace App\Filament\App\Resources\VirtualEventResource\RelationManagers;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Forms\Components\TagsInput;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteBulkAction;
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

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Attendee Information')
                    ->schema([
                        Select::make('user_id')
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
                        Select::make('person_id')
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
                        Grid::make(2)
                            ->schema([
                                TextInput::make('guest_name')
                                    ->label('Guest Name')
                                    ->maxLength(255)
                                    ->visible(fn (callable $get) => !$get('user_id') && !$get('person_id')),
                                TextInput::make('guest_email')
                                    ->label('Guest Email')
                                    ->email()
                                    ->maxLength(255)
                                    ->visible(fn (callable $get) => !$get('user_id') && !$get('person_id')),
                            ]),
                    ]),

                Section::make('RSVP & Participation')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('rsvp_status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'accepted' => 'Accepted',
                                        'declined' => 'Declined',
                                        'maybe' => 'Maybe',
                                    ])
                                    ->required()
                                    ->default('pending'),
                                DateTimePicker::make('rsvp_date')
                                    ->label('RSVP Date')
                                    ->native(false),
                            ]),
                        Textarea::make('rsvp_notes')
                            ->label('RSVP Notes')
                            ->rows(2)
                            ->columnSpanFull(),
                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_host')
                                    ->label('Event Host'),
                                Toggle::make('is_moderator')
                                    ->label('Event Moderator'),
                            ]),
                    ]),

                Section::make('Attendance Tracking')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('attended')
                                    ->label('Actually Attended')
                                    ->live(),
                                TextInput::make('duration_minutes')
                                    ->label('Duration (minutes)')
                                    ->numeric()
                                    ->visible(fn (callable $get) => $get('attended')),
                            ]),
                        Grid::make(2)
                            ->schema([
                                DateTimePicker::make('joined_at')
                                    ->label('Joined At')
                                    ->native(false)
                                    ->visible(fn (callable $get) => $get('attended')),
                                DateTimePicker::make('left_at')
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
                TextColumn::make('display_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('display_email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),
                BadgeColumn::make('rsvp_status')
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
                TextColumn::make('rsvp_date')
                    ->label('RSVP Date')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                IconColumn::make('attended')
                    ->label('Attended')
                    ->boolean()
                    ->alignCenter()
                    ->visible(fn () => $this->getOwnerRecord()->is_past || $this->getOwnerRecord()->is_active),
                TextColumn::make('attendance_duration')
                    ->label('Duration')
                    ->visible(fn () => $this->getOwnerRecord()->is_past)
                    ->toggleable(),
                IconColumn::make('is_host')
                    ->label('Host')
                    ->boolean()
                    ->alignCenter()
                    ->toggleable(),
                IconColumn::make('is_moderator')
                    ->label('Moderator')
                    ->boolean()
                    ->alignCenter()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('rsvp_status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'declined' => 'Declined',
                        'maybe' => 'Maybe',
                    ]),
                TernaryFilter::make('attended')
                    ->label('Actually Attended')
                    ->visible(fn () => $this->getOwnerRecord()->is_past || $this->getOwnerRecord()->is_active),
                TernaryFilter::make('is_host')
                    ->label('Event Hosts'),
                TernaryFilter::make('is_moderator')
                    ->label('Event Moderators'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Attendee'),
                Action::make('bulk_invite')
                    ->label('Bulk Invite')
                    ->icon('heroicon-o-envelope')
                    ->color('primary')
                    ->schema([
                        Select::make('users')
                            ->label('Select Users')
                            ->multiple()
                            ->options(User::all()->pluck('name', 'id'))
                            ->searchable(),
                        Select::make('people')
                            ->label('Select People from Family Tree')
                            ->multiple()
                            ->options(Person::all()->pluck('name', 'id'))
                            ->searchable(),
                        TagsInput::make('guest_emails')
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
            ->recordActions([
                Action::make('accept')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(fn (VirtualEventAttendee $record) => $record->accept())
                    ->visible(fn (VirtualEventAttendee $record) => $record->rsvp_status !== 'accepted'),
                Action::make('decline')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->action(fn (VirtualEventAttendee $record) => $record->decline())
                    ->visible(fn (VirtualEventAttendee $record) => $record->rsvp_status !== 'declined'),
                Action::make('mark_attended')
                    ->icon('heroicon-o-user-check')
                    ->color('primary')
                    ->action(fn (VirtualEventAttendee $record) => $record->markAsAttended())
                    ->visible(fn (VirtualEventAttendee $record) => !$record->attended && ($this->getOwnerRecord()->is_past || $this->getOwnerRecord()->is_active)),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('accept_all')
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
                    BulkAction::make('mark_all_attended')
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
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
