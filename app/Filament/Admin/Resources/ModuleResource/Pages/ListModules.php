<?php

namespace App\Filament\Admin\Resources\ModuleResource\Pages;

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
            Actions\Action::make('refresh')
                ->label('Refresh Modules')
                ->icon('heroicon-o-arrow-path')
                ->action(function () {
                    // Clear module cache and reload
                    \Cache::forget('modules');
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Modules Refreshed')
                        ->body('Module information has been refreshed.')
                        ->success()
                        ->send();
                }),
            Actions\Action::make('create_module')
                ->label('Create Module')
                ->icon('heroicon-o-plus')
                ->form([
                    \Filament\Forms\Components\TextInput::make('name')
                        ->label('Module Name')
                        ->required()
                        ->rules(['alpha_dash'])
                        ->helperText('Use PascalCase (e.g., MyModule)'),
                    \Filament\Forms\Components\Textarea::make('description')
                        ->label('Description')
                        ->required(),
                ])
                ->action(function (array $data) {
                    try {
                        \Artisan::call('module create', [
                            'name' => $data['name']
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Module Created')
                            ->body("The {$data['name']} module has been created successfully.")
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        \Filament\Notifications\Notification::make()
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
    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return ModuleResource::getEloquentQuery();
    }
}