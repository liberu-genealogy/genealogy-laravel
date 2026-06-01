<?php

namespace App\Filament\Admin\Resources\ModuleResource\Pages;

use Filament\Actions\Action;
use App\Modules\ModuleManager;
use App\Filament\Admin\Resources\ModuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewModule extends ViewRecord
{
    #[\Override]
    protected static string $resource = ModuleResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            Action::make('toggle')
                ->label(fn (): string => $this->record->enabled ? 'Disable' : 'Enable')
                ->icon(fn (): string => $this->record->enabled ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                ->color(fn (): string => $this->record->enabled ? 'danger' : 'success')
                ->action(function (): void {
                    $moduleManager = app(ModuleManager::class);
                    
                    if ($this->record->enabled) {
                        $moduleManager->disable($this->record->name);
                    } else {
                        $moduleManager->enable($this->record->name);
                    }
                    
                    $this->redirect(static::getResource()::getUrl('index'));
                })
                ->requiresConfirmation(),
        ];
    }
}
