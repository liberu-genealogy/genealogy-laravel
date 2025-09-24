<?php

namespace App\Filament\App\Resources;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Actions\Action;
use Exception;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\VirtualEventResource\RelationManagers\AttendeesRelationManager;
use App\Filament\App\Resources\VirtualEventResource\Pages\ListVirtualEvents;
use App\Filament\App\Resources\VirtualEventResource\Pages\CreateVirtualEvent;
use App\Filament\App\Resources\VirtualEventResource\Pages\EditVirtualEvent;
use App\Filament\App\Resources\VirtualEventResource\Pages\ViewVirtualEvent;
use App\Filament\App\Resources\VirtualEventResource\Pages;
use App\Filament\App\Resources\VirtualEventResource\RelationManagers;
use App\Models\VirtualEvent;
use App\Services\VideoConferencingService;
use Filament\Forms;
use Filament\Schemas\Schema;
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

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationLabel = 'Virtual Events';

    protected static string | \UnitEnum | null $navigationGroup = '👥 Family Reunions';

    protected static ?int $navigationSort = 1;
 public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Event Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                Textarea::make('description')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),
                    ]),

                Section::make('Schedule & Settings')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                DateTimePicker::make('start_time')
                                    ->required()
                                    ->native(false)
                                    ->live()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state && !$get('end_time')) {
                                            $set('end_time', Carbon::parse($state)->addHours(2));
                                        }
                                    }),
                                DateTimePicker::make('end_time')
                                    ->required()
                                    ->native(false)
                                    ->after('start_time'),
                                Select::make('timezone')
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
                        Grid::make(2)
                            ->schema([
                                Select::make('status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'published' => 'Published',
                                        'started' => 'Started',
                                        'ended' => 'Ended',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->required()
                                    ->default('draft'),
                                TextInput::make('max_attendees')
                                    ->numeric()
                                    ->label('Maximum Attendees')
                                    ->helperText('Leave empty for unlimited'),
                            ]),
                        Grid::make(2)
                            ->schema([
                                Toggle::make('require_rsvp')
                                    ->label('Require RSVP')
                                    ->default(true),
                                Toggle::make('allow_guests')
                                    ->label('Allow Guest Attendees')
                                    ->default(false),
                            ]),
                    ]),

                Section::make('Video Conferencing')
                    ->schema([
                        Select::make('platform')
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
                        Grid::make(2)
                            ->schema([
                                TextInput::make('host_email')
                                    ->email()
                                    ->label('Host Email')
                                    ->helperText('Email of the meeting host (defaults to event creator)')
                                    ->default(fn() => auth()->user()->email),
                                TextInput::make('meeting_password')
                                    ->label('Meeting Password')
                                    ->helperText('Auto-generated when meeting is created')
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),
                        Grid::make(1)
                            ->schema([
                                TextInput::make('meeting_url')
                                    ->label('Meeting URL')
                                    ->url()
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->visible(fn($get) => $get('platform') === 'custom'),
                                TextInput::make('join_url')
                                    ->label('Join URL')
                                    ->url()
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->columnSpanFull()
                                    ->visible(fn($get) => !empty($get('join_url'))),
                            ]),
                        Textarea::make('instructions')
                            ->label('Special Instructions')
                            ->rows(3)
                            ->helperText('Additional instructions for attendees')
                            ->columnSpanFull(),
                    ]),

                Hidden::make('created_by')
                    ->default(auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'draft',
                        'success' => 'published',
                        'warning' => 'started',
                        'danger' => 'cancelled',
                        'gray' => 'ended',
                    ])
                    ->sortable(),
                TextColumn::make('platform')
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
                TextColumn::make('formatted_start_time')
                    ->label('Start Time')
                    ->sortable('start_time'),
                TextColumn::make('attendee_count')
                    ->label('Attendees')
                    ->alignCenter()
                    ->badge()
                    ->color('success'),
                TextColumn::make('accepted_count')
                    ->label('Accepted')
                    ->alignCenter()
                    ->badge()
                    ->color('primary'),
                IconColumn::make('require_rsvp')
                    ->label('RSVP')
                    ->boolean()
                    ->alignCenter(),
                TextColumn::make('creator.name')
                    ->label('Created By')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'started' => 'Started',
                        'ended' => 'Ended',
                        'cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('platform')
                    ->options([
                        'zoom' => 'Zoom',
                        'google_meet' => 'Google Meet',
                        'teams' => 'Teams',
                        'custom' => 'Custom',
                    ]),
                Filter::make('upcoming')
                    ->query(fn (Builder $query): Builder => $query->upcoming())
                    ->label('Upcoming Events'),
                Filter::make('past')
                    ->query(fn (Builder $query): Builder => $query->past())
                    ->label('Past Events'),
            ])
            ->recordActions([
                Action::make('join')
                    ->icon('heroicon-o-video-camera')
                    ->color('success')
                    ->url(fn (VirtualEvent $record): string => $record->join_url ?? '#')
                    ->openUrlInNewTab()
                    ->visible(fn (VirtualEvent $record): bool => $record->canJoin() && !empty($record->join_url)),
                Action::make('create_meeting')
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
                        } catch (Exception $e) {
                            Notification::make()
                                ->title('Meeting Creation Failed')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(fn (VirtualEvent $record): bool => empty($record->meeting_id) && $record->platform !== 'custom'),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('start_time', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            AttendeesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVirtualEvents::route('/'),
            'create' => CreateVirtualEvent::route('/create'),
            'edit' => EditVirtualEvent::route('/{record}/edit'),
            'view' => ViewVirtualEvent::route('/{record}'),
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
