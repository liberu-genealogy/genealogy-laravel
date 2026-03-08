<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ImportJobResource\Pages\ListImportJobs;
use App\Models\ImportJob;
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
                    ->copyable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'complete' => 'success',
                        'failed'   => 'danger',
                        'queue'    => 'warning',
                        default    => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Started At')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->recordActions([])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListImportJobs::route('/'),
        ];
    }
}
