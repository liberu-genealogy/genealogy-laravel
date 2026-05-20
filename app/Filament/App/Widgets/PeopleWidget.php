<?php

namespace App\Filament\App\Widgets;

use Filament\Tables\Columns\TextColumn;
use App\Models\Person;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class PeopleWidget extends BaseWidget
{
    protected function getTableQuery(): Builder
    {
        return Person::query()->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('fullname')
                ->label('Name')
                ->searchable(['givn', 'surn'])
                ->sortable(),
            TextColumn::make('sex')
                ->label('Gender'),
            TextColumn::make('birthday')
                ->date()
                ->sortable(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            // Tables\Actions\ViewAction::make(),
        ];
    }
}