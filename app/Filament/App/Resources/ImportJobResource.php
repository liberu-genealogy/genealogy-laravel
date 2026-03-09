<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ImportJobResource\Pages\ListImportJobs;
use App\Filament\App\Resources\ImportJobResource\Pages\ViewImportJob;
use App\Models\ImportJob;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Override;

class ImportJobResource extends AppResource
{
    protected static ?string $model = ImportJob::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Import Logs';

    protected static string|\UnitEnum|null $navigationGroup = '🛠️ Data Management';

    protected static ?int $navigationSort = 11;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', auth()->id())
            ->latest();
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('slug')
                    ->label('Import ID')
                    ->searchable()
                    ->copyable()
                    ->limit(16),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'complete'   => 'success',
                        'failed'     => 'danger',
                        'processing' => 'info',
                        'queue'      => 'warning',
                        default      => 'gray',
                    }),
                TextColumn::make('progress')
                    ->label('Progress')
                    ->formatStateUsing(fn (int $state): string => $state . '%')
                    ->color(fn (int $state): string => match (true) {
                        $state === 100 => 'success',
                        $state >= 50   => 'info',
                        $state > 0     => 'warning',
                        default        => 'gray',
                    }),
                TextColumn::make('people_imported')
                    ->label('People')
                    ->numeric()
                    ->default(0)
                    ->toggleable(),
                TextColumn::make('families_imported')
                    ->label('Families')
                    ->numeric()
                    ->default(0)
                    ->toggleable(),
                TextColumn::make('error_message')
                    ->label('Error')
                    ->limit(60)
                    ->tooltip(fn (?string $state): string => $state ?? '')
                    ->color('danger')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Queued At')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListImportJobs::route('/'),
            'view'  => ViewImportJob::route('/{record}'),
        ];
    }
}
