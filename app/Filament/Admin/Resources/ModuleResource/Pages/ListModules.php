<?php

namespace App\Filament\Admin\Resources\ModuleResource\Pages;

use App\Filament\Admin\Resources\ModuleResource;
use Artisan;
use Cache;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;

class ListModules extends ListRecords
{
    #[\Override]
    protected static string $resource = ModuleResource::class;

    /**
     * Module rows are arrays (from ->records()), not Eloquent Models, so disable
     * the framework's default row-click record action/URL — both are
     * type-hinted Model and would fatal on an array record. The toggle/info row
     * actions still work.
     */
    #[\Override]
    protected function makeTable(): Table
    {
        return parent::makeTable()
            ->recordAction(null)
            ->recordUrl(null);
    }

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Refresh Modules')
                ->icon('heroicon-o-arrow-path')
                ->action(function (): void {
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
                ->action(function (array $data): void {
                    try {
                        Artisan::call('module create', [
                            'name' => $data['name'],
                        ]);

                        Notification::make()
                            ->title('Module Created')
                            ->body("The {$data['name']} module has been created successfully.")
                            ->success()
                            ->send();
                    } catch (Exception $e) {
                        Notification::make()
                            ->title('Error')
                            ->body('Failed to create module: '.$e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }
}
