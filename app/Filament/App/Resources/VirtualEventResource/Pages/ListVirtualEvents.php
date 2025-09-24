<?php

namespace App\Filament\App\Resources\VirtualEventResource\Pages;

use App\Filament\App\Resources\VirtualEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListVirtualEvents extends ListRecords
{
    protected static string $resource = VirtualEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Events'),
            'upcoming' => Tab::make('Upcoming')
                ->modifyQueryUsing(fn (Builder $query) => $query->upcoming())
                ->badge(fn () => $this->getModel()::upcoming()->count()),
            'active' => Tab::make('Active')
                ->modifyQueryUsing(fn (Builder $query) => $query->active())
                ->badge(fn () => $this->getModel()::active()->count()),
            'past' => Tab::make('Past')
                ->modifyQueryUsing(fn (Builder $query) => $query->past())
                ->badge(fn () => $this->getModel()::past()->count()),
            'draft' => Tab::make('Drafts')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'draft'))
                ->badge(fn () => $this->getModel()::where('status', 'draft')->count()),
        ];
    }
}