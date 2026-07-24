<?php

namespace App\Filament\App\Widgets;

use App\Filament\App\Resources\PersonResource;
use App\Models\Person;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PeopleWidget extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Recent People')
            ->query(Person::query()->latest())
            ->recordUrl(fn (Person $record): string => PersonResource::getUrl('edit', ['record' => $record]))
            ->columns([
                // 'fullname' resolves via Person::getFullnameAttribute (givn+surn, name fallback);
                // not sortable — it is a computed accessor, not a DB column.
                TextColumn::make('fullname')
                    ->label('Name')
                    ->searchable(['givn', 'surn']),
                TextColumn::make('sex')
                    ->label('Gender')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'M' => 'info',
                        'F' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('birthday')
                    ->label('Born')
                    ->date()
                    ->sortable(),
            ])
            ->paginated([5, 10, 25]);
    }
}
