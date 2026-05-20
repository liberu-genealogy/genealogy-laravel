<?php

namespace App\Filament\Admin\Resources\ModuleResource\Pages;

use Filament\Actions\Action;
use Cache;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Artisan;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Admin\Resources\ModuleResource;
use App\Modules\ModuleManager;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListModules extends ListRecords
{
    protected static string $resource = ModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Refresh Modules')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    // Clear module cache and reload
                    Cache::forget('modules');
                    
                    Notification::make()
                        ->title('Modules Refreshed')
                        ->body('Module information has been refreshed.')
                        ->success()
                        ->send();
                }),
            Action::make('create_module')
                ->label('Create Module')
                ->icon('heroicon-o-plus')
                ->schema([
                    TextInput::make('name')
                        ->label('Module Name')
                        ->required()
                        ->rules(['alpha_dash'])
                        ->helperText('Use PascalCase (e.g., MyModule)'),
                    Textarea::make('description')
                        ->label('Description')
                        ->required(),
                ])
                ->action(function (array $data) {
                    try {
                        Artisan::call('module create', [
                            'name' => $data['name']
                        ]);

                        Notification::make()
                            ->title('Module Created')
                            ->body("The {$data['name']} module has been created successfully.")
                            ->success()
                            ->send();
                    } catch (Exception $e) {
                        Notification::make()
                            ->title('Error')
                            ->body('Failed to create module: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

    /**
     * Override to use custom data source.
     */
    protected function getTableQuery(): Builder
    {
        return ModuleResource::getEloquentQuery();
    }
}