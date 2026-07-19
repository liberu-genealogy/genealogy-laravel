<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\SmartMatchResource\Pages\ListSmartMatches;
use App\Filament\App\Resources\SmartMatchResource\Pages\ViewSmartMatch;
use App\Models\SmartMatch;
use App\Services\SmartMatchingService;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class SmartMatchResource extends AppResource
{
    #[\Override]
    protected static ?string $model = SmartMatch::class;

    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-magnifying-glass';

    #[\Override]
    protected static ?string $navigationLabel = 'Smart Matches';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = '🔍 Research & Analysis';

    #[\Override]
    protected static ?int $navigationSort = 4;

    #[\Override]
    public static function shouldRegisterNavigation(): bool
    {
        if (config('premium.enabled')) {
            return true;
        }

        return auth()->user()?->isPremium() ?? false;
    }

    #[\Override]
    public static function canAccess(): bool
    {
        if (config('premium.enabled')) {
            return true;
        }

        return Auth::user()?->isPremium() ?? false;
    }

    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('person.name')
                    ->label('Your Person')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('match_data.name')
                    ->label('Potential Match')
                    ->getStateUsing(fn (SmartMatch $record): string => $record->match_data['name'] ?? 'Unknown'),
                TextColumn::make('record_category')
                    ->label('Record Type')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'newspaper' => 'info',
                        'parish' => 'primary',
                        'census' => 'success',
                        'electoral' => 'warning',
                        'gro_index' => 'info',
                        'military' => 'danger',
                        'probate' => 'warning',
                        'poor_law' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => $state ? ucwords(str_replace('_', ' ', $state)) : 'General'),
                TextColumn::make('match_source')
                    ->label('Source')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'familysearch' => 'primary',
                        'ancestry' => 'success',
                        'myheritage' => 'warning',
                        'findmypast' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('confidence_percentage')
                    ->label('Confidence')
                    ->badge()
                    ->color(fn ($state): string => match (true) {
                        (float) str_replace('%', '', $state) >= 80 => 'success',
                        (float) str_replace('%', '', $state) >= 60 => 'warning',
                        default => 'danger',
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'reviewed' => 'primary',
                        'accepted' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Found')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'reviewed' => 'Reviewed',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                    ]),
                SelectFilter::make('match_source')
                    ->options([
                        'familysearch' => 'FamilySearch',
                        'ancestry' => 'Ancestry',
                        'myheritage' => 'MyHeritage',
                        'findmypast' => 'FindMyPast',
                    ]),
                SelectFilter::make('record_category')
                    ->label('Record Type')
                    ->options([
                        'newspaper' => 'Newspaper',
                        'parish' => 'Parish Record',
                        'census' => 'Census',
                        'electoral' => 'Electoral Register',
                        'gro_index' => 'GRO Index',
                        'military' => 'Military',
                        'probate' => 'Probate',
                        'poor_law' => 'Poor Law/Workhouse',
                        'vital' => 'Vital Records',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('accept')
                    ->label('Accept')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(fn (SmartMatch $record) => static::reviewMatch($record, 'accepted'))
                    ->visible(fn (SmartMatch $record): bool => $record->isPending() && static::collaborationTierPermits('update')),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->action(fn (SmartMatch $record) => static::reviewMatch($record, 'rejected'))
                    ->visible(fn (SmartMatch $record): bool => $record->isPending() && static::collaborationTierPermits('update')),
            ])
            ->headerActions([
                Action::make('find_matches')
                    ->label('Find New Matches')
                    ->icon('heroicon-o-magnifying-glass')
                    ->color('primary')
                    ->visible(fn (): bool => static::collaborationTierPermits('create'))
                    ->action(function () {
                        $matches = static::runMatchSearch();

                        Notification::make()
                            ->title('Smart Matching Complete')
                            ->body("Found {$matches->count()} potential matches!")
                            ->success()
                            ->send();

                        return redirect()->back();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Find Smart Matches')
                    ->modalDescription('This will search public genealogy databases for potential matches to your unknown ancestors. This may take a few minutes.')
                    ->modalSubmitActionLabel('Start Search'),
            ])
            ->toolbarActions([])
            ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id', Auth::id()));
    }

    /**
     * The guarded action bodies live as methods, not inline closures, so the
     * tier guard can be tested directly.
     *
     * This matters because Filament's ->visible() is not enforced when an
     * action is invoked: mountAction checks isDisabled(), never isVisible(), so
     * a crafted Livewire request reaches a hidden action's body. The
     * abort_unless here is the real server-side guard, and inline it could only
     * be reached through the full table-action machinery, which refuses hidden
     * actions in tests — leaving the guard untestable and, as an earlier review
     * found, untested. As a method it is called directly by
     * ActionClosureTierEnforcementTest.
     *
     * Reviewing writes the match's status — an edit, gated at the update tier.
     */
    public static function reviewMatch(SmartMatch $record, string $status): void
    {
        abort_unless(static::collaborationTierPermits('update'), 403);

        $record->update(['status' => $status, 'reviewed_at' => now()]);
    }

    /**
     * Running a search writes SmartMatch records, so it is a create.
     *
     * @return Collection<int, SmartMatch>
     */
    public static function runMatchSearch(): Collection
    {
        abort_unless(static::collaborationTierPermits('create'), 403);

        return app(SmartMatchingService::class)->findSmartMatches(Auth::user());
    }

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ListSmartMatches::route('/'),
            'view' => ViewSmartMatch::route('/{record}'),
        ];
    }
}
