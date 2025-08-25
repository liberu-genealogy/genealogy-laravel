<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\SmartMatchResource\Pages;
use App\Models\SmartMatch;
use App\Services\SmartMatchingService;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SmartMatchResource extends Resource
{
    protected static ?string $model = SmartMatch::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationLabel = 'Smart Matches';

    protected static ?string $navigationGroup = 'Research';
    
    protected static ?int $navigationSort = 4;

    public static function canAccess(): bool
    {
        return Auth::user()->isPremium();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

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
                TextColumn::make('match_source')
                    ->label('Source')
                    ->badge()
                    ->colors([
                        'primary' => 'familysearch',
                        'success' => 'ancestry',
                        'warning' => 'myheritage',
                        'info' => 'findmypast',
                    ]),
                TextColumn::make('confidence_percentage')
                    ->label('Confidence')
                    ->badge()
                    ->colors([
                        'success' => fn ($state): bool => (float) str_replace('%', '', $state) >= 80,
                        'warning' => fn ($state): bool => (float) str_replace('%', '', $state) >= 60,
                        'danger' => fn ($state): bool => (float) str_replace('%', '', $state) < 60,
                    ]),
                BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'pending',
                        'primary' => 'reviewed',
                        'success' => 'accepted',
                        'danger' => 'rejected',
                    ]),
                TextColumn::make('created_at')
                    ->label('Found')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'reviewed' => 'Reviewed',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                    ]),
                Tables\Filters\SelectFilter::make('match_source')
                    ->options([
                        'familysearch' => 'FamilySearch',
                        'ancestry' => 'Ancestry',
                        'myheritage' => 'MyHeritage',
                        'findmypast' => 'FindMyPast',
                    ]),
            ])
            ->actions([
                Action::make('view_details')
                    ->label('View Details')
                    ->icon('heroicon-o-eye')
                    ->url(fn (SmartMatch $record): string => route('filament.app.resources.smart-matches.view', $record)),
                Action::make('accept')
                    ->label('Accept')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(fn (SmartMatch $record) => $record->update(['status' => 'accepted', 'reviewed_at' => now()]))
                    ->visible(fn (SmartMatch $record): bool => $record->isPending()),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->action(fn (SmartMatch $record) => $record->update(['status' => 'rejected', 'reviewed_at' => now()]))
                    ->visible(fn (SmartMatch $record): bool => $record->isPending()),
            ])
            ->headerActions([
                Action::make('find_matches')
                    ->label('Find New Matches')
                    ->icon('heroicon-o-magnifying-glass')
                    ->color('primary')
                    ->action(function () {
                        $service = app(SmartMatchingService::class);
                        $matches = $service->findSmartMatches(Auth::user());
                        
                        \Filament\Notifications\Notification::make()
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
            ->bulkActions([])
            ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id', Auth::id()));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSmartMatches::route('/'),
            'view' => Pages\ViewSmartMatch::route('/{record}'),
        ];
    }
}