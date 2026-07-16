<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ModuleResource\Pages\ListModules;
use App\Modules\ModuleManager;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ModuleResource extends Resource
{
    #[\Override]
    protected static ?string $model = null; // We don't use a traditional model

    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-puzzle-piece';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = '🛠️ System';

    #[\Override]
    protected static ?string $navigationLabel = 'Modules';

    #[\Override]
    protected static ?int $navigationSort = 10;

    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->disabled(),
                TextInput::make('version')
                    ->disabled(),
                Textarea::make('description')
                    ->disabled(),
                TagsInput::make('dependencies')
                    ->disabled(),
                Toggle::make('enabled')
                    ->required(),
            ]);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return $table
            ->query(static::getEloquentQuery())
            ->columns([
                TextColumn::make('name')
                    ->label('Module Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('version')
                    ->label('Version')
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record['description']),
                TagsColumn::make('dependencies')
                    ->label('Dependencies'),
                IconColumn::make('enabled')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                TernaryFilter::make('enabled')
                    ->label('Status')
                    ->placeholder('All modules')
                    ->trueLabel('Enabled modules')
                    ->falseLabel('Disabled modules'),
            ])
            ->recordActions([
                Action::make('toggle')
                    ->label(fn ($record): string => $record['enabled'] ? 'Disable' : 'Enable')
                    ->icon(fn ($record): string => $record['enabled'] ? 'heroicon-o-pause' : 'heroicon-o-play')
                    ->color(fn ($record): string => $record['enabled'] ? 'warning' : 'success')
                    ->requiresConfirmation()
                    ->modalHeading(fn ($record): string => ($record['enabled'] ? 'Disable' : 'Enable').' Module')
                    ->modalDescription(fn ($record): string => 'Are you sure you want to '.
                        ($record['enabled'] ? 'disable' : 'enable').' the '.$record['name'].' module?')
                    ->action(function (array $record): void {
                        $moduleManager = app(ModuleManager::class);

                        try {
                            if ($record['enabled']) {
                                $moduleManager->disable($record['name']);
                                Notification::make()
                                    ->title('Module Disabled')
                                    ->body("The {$record['name']} module has been disabled.")
                                    ->success()
                                    ->send();
                            } else {
                                $moduleManager->enable($record['name']);
                                Notification::make()
                                    ->title('Module Enabled')
                                    ->body("The {$record['name']} module has been enabled.")
                                    ->success()
                                    ->send();
                            }
                        } catch (Exception $e) {
                            Notification::make()
                                ->title('Error')
                                ->body('Failed to toggle module: '.$e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                Action::make('info')
                    ->label('Info')
                    ->icon('heroicon-o-information-circle')
                    ->color('info')
                    ->modalHeading(fn ($record): string => $record['name'].' Module Information')
                    ->modalContent(fn ($record) => view('filament.admin.resources.module-resource.info-modal', [
                        'module' => $record,
                    ])),
            ])
            ->toolbarActions([
                // No bulk actions for modules
            ]);
    }

    #[\Override]
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ListModules::route('/'),
        ];
    }

    /**
     * Get the Eloquent query for modules.
     */
    #[\Override]
    public static function getEloquentQuery(): Builder
    {
        // Create a fake query builder that returns module data
        $moduleManager = app(ModuleManager::class);
        $modules = $moduleManager->getAllModulesInfo();

        // Convert to a collection and create a fake query
        $collection = collect($modules)->map(fn ($module) => (object) $module);

        // Return a custom query builder
        return new class($collection) extends Builder
        {
            public function __construct(protected $modules) {}

            public function get($columns = ['*'])
            {
                return $this->modules;
            }

            public function paginate($perPage = 15, $columns = ['*'], $pageName = 'page', $page = null, $total = null)
            {
                return new LengthAwarePaginator(
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
                    $this->modules = $this->modules->filter(fn ($module) => $module->enabled === (bool) $value);
                }

                return $this;
            }

            public function orderBy($column, $direction = 'asc'): self
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

    #[\Override]
    public static function canCreate(): bool
    {
        return false; // Modules are created via artisan command
    }

    #[\Override]
    public static function canEdit($record): bool
    {
        return false; // Modules are managed via toggle actions
    }

    #[\Override]
    public static function canDelete($record): bool
    {
        return false; // Modules are not deleted through the UI
    }
}
