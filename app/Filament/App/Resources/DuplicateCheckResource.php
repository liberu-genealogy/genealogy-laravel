<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\DuplicateCheckResource\Pages;
use App\Models\DuplicateCheck;
use App\Services\DuplicateCheckerService;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DuplicateCheckResource extends Resource
{
    protected static ?string $model = DuplicateCheck::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationLabel = 'Duplicate Checker';

    protected static ?string $navigationGroup = 'Research';
    
    protected static ?int $navigationSort = 3;

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
                TextColumn::make('created_at')
                    ->label('Run Date')
                    ->dateTime()
                    ->sortable(),
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                        'danger' => 'failed',
                    ]),
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
            ->actions([
                Action::make('view_results')
                    ->label('View Results')
                    ->icon('heroicon-o-eye')
                    ->url(fn (DuplicateCheck $record): string => route('filament.app.resources.duplicate-checks.view', $record))
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
            ->bulkActions([])
            ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id', Auth::id()));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDuplicateChecks::route('/'),
            'view' => Pages\ViewDuplicateCheck::route('/{record}'),
        ];
    }
}