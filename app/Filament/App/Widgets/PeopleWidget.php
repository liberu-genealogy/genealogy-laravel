<?php

namespace App\Filament\App\Widgets;

use App\Models\Person;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class PeopleWidget extends BaseWidget
{
    protected function getTableQuery(): Builder
    {
        $hundredYearsAgo = Carbon::now()->subYears(100)->toDateString();

        return Person::query()
            ->where('birthday', '<=', $hundredYearsAgo)
            ->withoutGlobalScope('team')
            ->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('fullname')
                ->label('Name')
                ->searchable(['givn', 'surn'])
                ->sortable(),
            Tables\Columns\TextColumn::make('sex')
                ->label('Gender'),
            Tables\Columns\TextColumn::make('birthday')
                ->date()
                ->sortable(),
            Tables\Columns\TextColumn::make('deathday')
                ->date()
                ->sortable()
                ->label('Death Date'),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make(),
        ];
    }
}