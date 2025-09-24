<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ModuleResource\Pages;
use App\Modules\ModuleManager;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ModuleResource extends Resource
{
    protected static ?string $model = null; // We don't use a traditional model

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $navigationGroup = 'System';

    protected static ?string $navigationLabel = 'Modules';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->disabled(),
                Forms\Components\TextInput::make('version')
                    ->disabled(),
                Forms\Components\Textarea::make('description')
                    ->disabled(),
                Forms\Components\TagsInput::make('dependencies')
                    ->disabled(),
                Forms\Components\Toggle::make('enabled')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(static::getEloquentQuery())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Module Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('version')
                    ->label('Version')
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->tooltip(function ($record) {
                        return $record['description'];
                    }),
                Tables\Columns\TagsColumn::make('dependencies')
                    ->label('Dependencies'),
                Tables\Columns\IconColumn::make('enabled')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('enabled')
                    ->label('Status')
                    ->placeholder('All modules')
                    ->trueLabel('Enabled modules')
                    ->falseLabel('Disabled modules'),
            ])
            ->actions([
                Tables\Actions\Action::make('toggle')
                    ->label(fn ($record) => $record['enabled'] ? 'Disable' : 'Enable')
                    ->icon(fn ($record) => $record['enabled'] ? 'heroicon-o-pause' : 'heroicon-o-play')
                    ->color(fn ($record) => $record['enabled'] ? 'warning' : 'success')
                    ->requiresConfirmation()
                    ->modalHeading(fn ($record) => ($record['enabled'] ? 'Disable' : 'Enable') . ' Module')
                    ->modalDescription(fn ($record) => 'Are you sure you want to ' .
                        ($record['enabled'] ? 'disable' : 'enable') . ' the ' . $record['name'] . ' module?')
                    ->action(function ($record) {
                        $moduleManager = app(ModuleManager::class);

                        try {
                            if ($record['enabled']) {
                                $moduleManager->disable($record['name']);
                                \Filament\Notifications\Notification::make()
                                    ->title('Module Disabled')
                                    ->body("The {$record['name']} module has been disabled.")
                                    ->success()
                                    ->send();
                            } else {
                                $moduleManager->enable($record['name']);
                                \Filament\Notifications\Notification::make()
                                    ->title('Module Enabled')
                                    ->body("The {$record['name']} module has been enabled.")
                                    ->success()
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Error')
                                ->body('Failed to toggle module: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                Tables\Actions\Action::make('info')
                    ->label('Info')
                    ->icon('heroicon-o-information-circle')
                    ->color('info')
                    ->modalHeading(fn ($record) => $record['name'] . ' Module Information')
                    ->modalContent(function ($record) {
                        return view('filament.admin.resources.module-resource.info-modal', [
                            'module' => $record
                        ]);
                    }),
            ])
            ->bulkActions([
                // No bulk actions for modules
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListModules::route('/'),
        ];
    }

    /**
     * Get the Eloquent query for modules.
     */
    public static function getEloquentQuery(): Builder
    {
        // Create a fake query builder that returns module data
        $moduleManager = app(ModuleManager::class);
        $modules = $moduleManager->getAllModulesInfo();

        // Convert to a collection and create a fake query
        $collection = collect($modules)->map(function ($module) {
            return (object) $module;
        });

        // Return a custom query builder
        return new class($collection) extends Builder {
            protected $modules;

            public function __construct($modules)
            {
                $this->modules = $modules;
            }

            public function get($columns = ['*'])
            {
                return $this->modules;
            }

            public function paginate($perPage = 15, $columns = ['*'], $pageName = 'page', $page = null)
            {
                return new \Illuminate\Pagination\LengthAwarePaginator(
                    $this->modules->forPage($page ?? 1, $perPage),
                    $this->modules->count(),
                    $perPage,
                    $page ?? 1,
                    [
                        'path' => request()->url(),
                        'pageName' => $pageName,
                    ]
                );
            }

            public function where($column, $operator = null, $value = null, $boolean = 'and')
            {
                if ($column === 'enabled' && $value !== null) {
                    $this->modules = $this->modules->filter(function ($module) use ($value) {
                        return $module->enabled === (bool) $value;
                    });
                }
                return $this;
            }

            public function orderBy($column, $direction = 'asc')
            {
                $this->modules = $this->modules->sortBy($column, SORT_REGULAR, $direction === 'desc');
                return $this;
            }

            // Add other necessary methods as needed
            public function __call($method, $parameters)
            {
                return $this;
            }
        };
    }

    public static function canCreate(): bool
    {
        return false; // Modules are created via artisan command
    }

    public static function canEdit($record): bool
    {
        return false; // Modules are managed via toggle actions
    }

    public static function canDelete($record): bool
    {
        return false; // Modules are not deleted through the UI
    }
}
