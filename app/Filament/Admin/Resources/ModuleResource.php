<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ModuleResource\Pages\ListModules;
use App\Models\Module;
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
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class ModuleResource extends Resource
{
    // A placeholder model (no `modules` table) that is never queried — rows come
    // from ModuleManager via ->records(). It exists only so Filament's table
    // plumbing has a model to reference for labels, keys and authorization.
    #[\Override]
    protected static ?string $model = Module::class;

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
            // Rows come from ModuleManager as arrays via the native records()
            // data source; the placeholder model is never queried for them.
            ->records(static::getModuleRecords(...))
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

    /**
     * Build the table's array records from ModuleManager, applying the table's
     * current filter/search/sort, and return a paginator (so Filament reads the
     * count from total() rather than building an Eloquent query it has no model
     * for). Keyed by module name so row actions resolve the right module.
     *
     * @param  array<string, array{value: mixed}>|null  $filters
     * @return LengthAwarePaginator<int, array<string, mixed>>
     */
    public static function getModuleRecords(
        ?string $sortColumn,
        ?string $sortDirection,
        ?array $filters,
        ?string $search,
        int $page,
        int|string $recordsPerPage,
    ): LengthAwarePaginator {
        $modules = collect(app(ModuleManager::class)->getAllModulesInfo());

        $enabled = $filters['enabled']['value'] ?? null;
        if ($enabled !== null && $enabled !== '') {
            $modules = $modules->where('enabled', filter_var($enabled, FILTER_VALIDATE_BOOLEAN));
        }

        if (filled($search)) {
            $needle = Str::lower($search);
            $modules = $modules->filter(
                fn (array $module): bool => str_contains(Str::lower((string) $module['name']), $needle)
            );
        }

        if (filled($sortColumn)) {
            $modules = $modules->sortBy($sortColumn, SORT_REGULAR, $sortDirection === 'desc');
        }

        $perPage = is_numeric($recordsPerPage) ? (int) $recordsPerPage : max($modules->count(), 1);

        return new LengthAwarePaginator(
            $modules->forPage($page, $perPage),
            $modules->count(),
            $perPage,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath()],
        );
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
