<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ChecklistTemplateResource\Pages\CreateChecklistTemplate;
use App\Filament\App\Resources\ChecklistTemplateResource\Pages\EditChecklistTemplate;
use App\Filament\App\Resources\ChecklistTemplateResource\Pages\ListChecklistTemplates;
use App\Filament\App\Resources\ChecklistTemplateResource\Pages\ViewChecklistTemplate;
use App\Filament\App\Resources\ChecklistTemplateResource\RelationManagers\TemplateItemsRelationManager;
use App\Models\ChecklistTemplate;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChecklistTemplateResource extends AppResource
{
    #[\Override]
    protected static ?string $model = ChecklistTemplate::class;

    #[\Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    #[\Override]
    protected static ?string $navigationLabel = 'Checklist Templates';

    #[\Override]
    protected static string|\UnitEnum|null $navigationGroup = '📋 Research Management';

    #[\Override]
    protected static ?int $navigationSort = 1;

    #[\Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Template Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $context, $state, callable $set): void {
                                        if ($context === 'create') {
                                            $set('description', "Research checklist for {$state}");
                                        }
                                    }),
                                Select::make('category')
                                    ->options([
                                        'general' => 'General Research',
                                        'vital_records' => 'Vital Records',
                                        'census' => 'Census Research',
                                        'immigration' => 'Immigration Records',
                                        'military' => 'Military Records',
                                        'land_records' => 'Land Records',
                                        'probate' => 'Probate Records',
                                        'church' => 'Church Records',
                                        'newspaper' => 'Newspaper Research',
                                        'dna' => 'DNA Research',
                                        'family_history' => 'Family History',
                                        'verification' => 'Source Verification',
                                    ])
                                    ->required()
                                    ->default('general'),
                            ]),
                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                        Grid::make(3)
                            ->schema([
                                Select::make('difficulty_level')
                                    ->options([
                                        'beginner' => 'Beginner',
                                        'intermediate' => 'Intermediate',
                                        'advanced' => 'Advanced',
                                    ])
                                    ->required()
                                    ->default('beginner'),
                                TextInput::make('estimated_time')
                                    ->numeric()
                                    ->suffix('minutes')
                                    ->label('Estimated Time'),
                                Hidden::make('created_by')
                                    ->default(auth()->id()),
                            ]),
                    ]),

                Section::make('Template Settings')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_public')
                                    ->label('Make Public')
                                    ->helperText('Allow other users to use this template'),
                                Toggle::make('is_default')
                                    ->label('Default Template')
                                    ->helperText('Show as a recommended template')
                                    ->visible(fn () => auth()->user()->can('manage_default_templates')),
                            ]),
                        TagsInput::make('tags')
                            ->placeholder('Add tags to help categorize this template')
                            ->columnSpanFull(),
                    ]),

                Section::make('Checklist Items')
                    ->schema([
                        Repeater::make('templateItems')
                            ->relationship()
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextInput::make('title')
                                            ->required()
                                            ->columnSpan(2),
                                        TextInput::make('order')
                                            ->numeric()
                                            ->default(function (callable $get): int {
                                                $items = $get('../../templateItems') ?? [];

                                                return count($items) + 1;
                                            }),
                                    ]),
                                Textarea::make('description')
                                    ->rows(2)
                                    ->columnSpanFull(),
                                Grid::make(3)
                                    ->schema([
                                        Select::make('category')
                                            ->options([
                                                'research' => 'Research',
                                                'documentation' => 'Documentation',
                                                'verification' => 'Verification',
                                                'analysis' => 'Analysis',
                                                'follow_up' => 'Follow-up',
                                            ])
                                            ->default('research'),
                                        TextInput::make('estimated_time')
                                            ->numeric()
                                            ->suffix('minutes')
                                            ->label('Est. Time'),
                                        Toggle::make('is_required')
                                            ->label('Required'),
                                    ]),
                                TagsInput::make('resources')
                                    ->placeholder('Add helpful resources (URLs, document names, etc.)')
                                    ->columnSpanFull(),
                                TagsInput::make('tips')
                                    ->placeholder('Add helpful tips and notes')
                                    ->columnSpanFull(),
                            ])
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                            ->addActionLabel('Add Checklist Item')
                            ->reorderable('order')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'vital_records' => 'success',
                        'census' => 'info',
                        'immigration' => 'warning',
                        'military' => 'danger',
                        'dna' => 'purple',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => str_replace('_', ' ', title_case($state))),
                TextColumn::make('difficulty_level')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'beginner' => 'success',
                        'intermediate' => 'warning',
                        'advanced' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                TextColumn::make('templateItems_count')
                    ->counts('templateItems')
                    ->label('Items')
                    ->alignCenter(),
                TextColumn::make('estimated_time')
                    ->suffix(' min')
                    ->label('Est. Time')
                    ->alignCenter()
                    ->sortable(),
                IconColumn::make('is_public')
                    ->boolean()
                    ->label('Public')
                    ->alignCenter(),
                IconColumn::make('is_default')
                    ->boolean()
                    ->label('Default')
                    ->alignCenter(),
                TextColumn::make('creator.name')
                    ->label('Created By')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options([
                        'general' => 'General Research',
                        'vital_records' => 'Vital Records',
                        'census' => 'Census Research',
                        'immigration' => 'Immigration Records',
                        'military' => 'Military Records',
                        'land_records' => 'Land Records',
                        'probate' => 'Probate Records',
                        'church' => 'Church Records',
                        'newspaper' => 'Newspaper Research',
                        'dna' => 'DNA Research',
                        'family_history' => 'Family History',
                        'verification' => 'Source Verification',
                    ]),
                SelectFilter::make('difficulty_level')
                    ->options([
                        'beginner' => 'Beginner',
                        'intermediate' => 'Intermediate',
                        'advanced' => 'Advanced',
                    ]),
                TernaryFilter::make('is_public')
                    ->label('Public Templates'),
                TernaryFilter::make('is_default')
                    ->label('Default Templates'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    Action::make('duplicate')
                        ->icon('heroicon-o-document-duplicate')
                        ->visible(fn (): bool => static::collaborationTierPermits('create'))
                        ->action(fn (ChecklistTemplate $record) => redirect()->route(
                            'filament.app.resources.checklist-templates.edit',
                            static::duplicateTemplate($record),
                        ))
                        ->requiresConfirmation()
                        ->modalHeading('Duplicate Template')
                        ->modalDescription('This will create a copy of this template that you can modify.'),
                    DeleteAction::make(),
                    RestoreAction::make(),
                    ForceDeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    /**
     * The guarded body as a method so the tier check is testable — Filament
     * does not enforce ->visible() on invocation, so abort_unless is the real
     * guard. Duplicating creates a new template and its items, so it is gated at
     * the create tier.
     */
    public static function duplicateTemplate(ChecklistTemplate $record): ChecklistTemplate
    {
        abort_unless(static::collaborationTierPermits('create'), 403);

        $newTemplate = $record->replicate();
        $newTemplate->name = $record->name.' (Copy)';
        $newTemplate->is_default = false;
        $newTemplate->created_by = auth()->id();
        $newTemplate->save();

        foreach ($record->templateItems as $item) {
            $newItem = $item->replicate();
            $newItem->checklist_template_id = $newTemplate->id;
            $newItem->save();
        }

        return $newTemplate;
    }

    #[\Override]
    public static function getRelations(): array
    {
        return [
            TemplateItemsRelationManager::class,
        ];
    }

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index' => ListChecklistTemplates::route('/'),
            'create' => CreateChecklistTemplate::route('/create'),
            'view' => ViewChecklistTemplate::route('/{record}'),
            'edit' => EditChecklistTemplate::route('/{record}/edit'),
        ];
    }

    #[\Override]
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
