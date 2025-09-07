<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\VirtualEventResource\Pages;
use App\Filament\App\Resources\VirtualEventResource\RelationManagers;
use App\Models\VirtualEvent;
use App\Services\VideoConferencingService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;

class VirtualEventResource extends Resource
{
    protected static ?string $model = VirtualEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationLabel = 'Virtual Events';

    protected static ?string $navigationGroup = '👥 Family Reunions';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Event Information')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('description')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),
                    ]),

                Forms\Components\Section::make('Schedule & Settings')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\DateTimePicker::make('start_time')
                                    ->required()
                                    ->native(false)
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state && !$get('end_time')) {
                                            $set('end_time', Carbon::parse($state)->addHours(2));
                                        }
                                    }),
                                Forms\Components\DateTimePicker::make('end_time')
                                    ->required()
                                    ->native(false)
                                    ->after('start_time'),
                                Forms\Components\Select::make('timezone')
                                    ->options([
                                        'UTC' => 'UTC',
                                        'America/New_York' => 'Eastern Time',
                                        'America/Chicago' => 'Central Time',
                                        'America/Denver' => 'Mountain Time',
                                        'America/Los_Angeles' => 'Pacific Time',
                                        'Europe/London' => 'London',
                                        'Europe/Paris' => 'Paris',
                                        'Asia/Tokyo' => 'Tokyo',
                                        'Australia/Sydney' => 'Sydney',
                                    ])
                                    ->required()
                                    ->default('UTC')
                                    ->searchable(),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'published' => 'Published',
                                        'started' => 'Started',
                                        'ended' => 'Ended',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->required()
                                    ->default('draft'),
                                Forms\Components\TextInput::make('max_attendees')
                                    ->numeric()
                                    ->label('Maximum Attendees')
                                    ->helperText('Leave empty for unlimited'),
                            ]),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('require_rsvp')
                                    ->label('Require RSVP')
                                    ->default(true),
                                Forms\Components\Toggle::make('allow_guests')
                                    ->label('Allow Guest Attendees')
                                    ->default(false),
                            ]),
                    ]),

                Forms\Components\Section::make('Video Conferencing')
                    ->schema([
                        Forms\Components\Select::make('platform')
                            ->options(function () {
                                $service = app(VideoConferencingService::class);
                                $platforms = $service->getAvailablePlatforms();

                                return collect($platforms)
                                    ->filter(fn($platform) => $platform['enabled'])
                                    ->mapWithKeys(fn($platform, $key) => [$key => $platform['name']])
                                    ->toArray();
                            })
                            ->required()
                            ->default('zoom')
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                // Clear meeting data when platform changes
                                $set('meeting_url', null);
                                $set('join_url', null);
                                $set('meeting_password', null);
                            }),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('host_email')
                                    ->email()
                                    ->label('Host Email')
                                    ->helperText('Email of the meeting host (defaults to event creator)')
                                    ->default(fn() => auth()->user()->email),
                                Forms\Components\TextInput::make('meeting_password')
                                    ->label('Meeting Password')
                                    ->helperText('Auto-generated when meeting is created')
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),
                        Forms\Components\Grid::make(1)
                            ->schema([
                                Forms\Components\TextInput::make('meeting_url')
                                    ->label('Meeting URL')
                                    ->url()
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->visible(fn($get) => $get('platform') === 'custom'),
                                Forms\Components\TextInput::make('join_url')
                                    ->label('Join URL')
                                    ->url()
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->columnSpanFull()
                                    ->visible(fn($get) => !empty($get('join_url'))),
                            ]),
                        Forms\Components\Textarea::make('instructions')
                            ->label('Special Instructions')
                            ->rows(3)
                            ->helperText('Additional instructions for attendees')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Hidden::make('created_by')
                    ->default(auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'draft',
                        'success' => 'published',
                        'warning' => 'started',
                        'danger' => 'cancelled',
                        'gray' => 'ended',
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('platform')
                    ->badge()
                    ->colors([
                        'primary' => 'zoom',
                        'success' => 'google_meet',
                        'warning' => 'teams',
                        'gray' => 'custom',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'zoom' => 'Zoom',
                        'google_meet' => 'Google Meet',
                        'teams' => 'Teams',
                        'custom' => 'Custom',
                        default => ucfirst($state),
                    }),
                Tables\Columns\TextColumn::make('formatted_start_time')
                    ->label('Start Time')
                    ->sortable('start_time'),
                Tables\Columns\TextColumn::make('attendee_count')
                    ->label('Attendees')
                    ->alignCenter()
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('accepted_count')
                    ->label('Accepted')
                    ->alignCenter()
                    ->badge()
                    ->color('primary'),
                Tables\Columns\IconColumn::make('require_rsvp')
                    ->label('RSVP')
                    ->boolean()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Created By')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'started' => 'Started',
                        'ended' => 'Ended',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('platform')
                    ->options([
                        'zoom' => 'Zoom',
                        'google_meet' => 'Google Meet',
                        'teams' => 'Teams',
                        'custom' => 'Custom',
                    ]),
                Tables\Filters\Filter::make('upcoming')
                    ->query(fn (Builder $query): Builder => $query->upcoming())
                    ->label('Upcoming Events'),
                Tables\Filters\Filter::make('past')
                    ->query(fn (Builder $query): Builder => $query->past())
                    ->label('Past Events'),
            ])
            ->actions([
                Tables\Actions\Action::make('join')
                    ->icon('heroicon-o-video-camera')
                    ->color('success')
                    ->url(fn (VirtualEvent $record): string => $record->join_url ?? '#')
                    ->openUrlInNewTab()
                    ->visible(fn (VirtualEvent $record): bool => $record->canJoin() && !empty($record->join_url)),
                Tables\Actions\Action::make('create_meeting')
                    ->icon('heroicon-o-plus-circle')
                    ->color('primary')
                    ->action(function (VirtualEvent $record) {
                        try {
                            $service = app(VideoConferencingService::class);
                            $service->createMeeting($record);

                            Notification::make()
                                ->title('Meeting Created')
                                ->body('Video conference meeting has been created successfully.')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Meeting Creation Failed')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(fn (VirtualEvent $record): bool => empty($record->meeting_id) && $record->platform !== 'custom'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('start_time', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AttendeesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVirtualEvents::route('/'),
            'create' => Pages\CreateVirtualEvent::route('/create'),
            'edit' => Pages\EditVirtualEvent::route('/{record}/edit'),
            'view' => Pages\ViewVirtualEvent::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::upcoming()->published()->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}