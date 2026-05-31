<?php

namespace App\Filament\App\Resources;

use Filament\Schemas\Schema;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use App\Filament\App\Resources\DuplicateCheckResource\Pages\ListDuplicateChecks;
use App\Filament\App\Resources\DuplicateCheckResource\Pages\ViewDuplicateCheck;
use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\DuplicateCheckResource\Pages;
use App\Models\DuplicateCheck;
use App\Services\DuplicateCheckerService;
use Filament\Forms;
use App\Filament\App\Resources\AppResource;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DuplicateCheckResource extends AppResource
{

    #[\Override]
    protected static ?string $model = DuplicateCheck::class;

    #[\Override]
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-duplicate';

    #[\Override]
    protected static ?string $navigationLabel = 'Duplicate Checker';

    #[\Override]
    protected static string | \UnitEnum | null $navigationGroup =  '🔍 Research & Analysis';

    #[\Override]
    protected static ?int $navigationSort = 3;

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
                TextColumn::make('created_at')
                    ->label('Run Date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'running' => 'primary',
                        'completed' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('duplicates_found')
                    ->label('Duplicates Found')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('completed_at')
                    ->label('Completed')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->recordActions([
                ViewAction::make()
                    ->visible(fn (DuplicateCheck $record): bool => $record->isCompleted()),
            ])
            ->headerActions([
                Action::make('run_check')
                    ->label('Run Duplicate Check')
                    ->icon('heroicon-o-play')
                    ->color('primary')
                    ->action(function () {
                        $service = app(DuplicateCheckerService::class);
                        $service->runDuplicateCheck(Auth::user());

                        return redirect()->back();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Run Duplicate Check')
                    ->modalDescription('This will scan your family tree for potential duplicate people. This may take a few minutes.')
                    ->modalSubmitActionLabel('Start Check'),
            ])
            ->toolbarActions([])
            ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id', Auth::id()));
    }

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ListDuplicateChecks::route('/'),
            'view' => ViewDuplicateCheck::route('/{record}'),
        ];
    }
}
