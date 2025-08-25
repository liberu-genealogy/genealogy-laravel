<?php

namespace App\Filament\App\Resources;

use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\SmartMatchResource\Pages;
use App\Models\SmartMatch;
use App\Services\SmartMatchingService;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SmartMatchResource extends Resource
{
    protected static ?string $model = SmartMatch::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationLabel = 'Smart Matches';

    protected static string | UnitEnum | null $navigationGroup =  'Research';

    protected static ?int $navigationSort = 4;

    public static function canAccess(): bool
    {
        return Auth::user()?->isPremium() ?? false;
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('accept')
                    ->label('Accept')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(fn (SmartMatch $record) => $record->update(['status' => 'accepted', 'reviewed_at' => now()]))
                    ->visible(fn (SmartMatch $record): bool => $record->isPending()),
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->action(fn (SmartMatch $record) => $record->update(['status' => 'rejected', 'reviewed_at' => now()]))
                    ->visible(fn (SmartMatch $record): bool => $record->isPending()),
            ])
            ->headerActions([
                Tables\Actions\Action::make('find_matches')
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
