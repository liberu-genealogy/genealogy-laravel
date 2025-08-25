<?php

namespace App\Filament\App\Resources;

use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\DuplicateCheckResource\Pages;
use App\Models\DuplicateCheck;
use App\Services\DuplicateCheckerService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DuplicateCheckResource extends Resource
{

    protected static ?string $model = DuplicateCheck::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationLabel = 'Duplicate Checker';

    protected static string | UnitEnum | null $navigationGroup =  'Research';

    protected static ?int $navigationSort = 3;

    public static function canAccess(): bool
    {
        return Auth::user()?->isPremium() ?? false;
    }

    public static function form(Schema $form): Schema
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
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
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
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(fn (DuplicateCheck $record): bool => $record->isCompleted()),
            ])
            ->headerActions([
                Tables\Actions\Action::make('run_check')
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
