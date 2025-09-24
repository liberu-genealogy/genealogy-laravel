<?php

namespace App\Filament\App\Resources;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use App\Filament\App\Resources\ChecklistTemplateResource\RelationManagers\TemplateItemsRelationManager;
use App\Filament\App\Resources\ChecklistTemplateResource\Pages\ListChecklistTemplates;
use App\Filament\App\Resources\ChecklistTemplateResource\Pages\CreateChecklistTemplate;
use App\Filament\App\Resources\ChecklistTemplateResource\Pages\ViewChecklistTemplate;
use App\Filament\App\Resources\ChecklistTemplateResource\Pages\EditChecklistTemplate;
use BackedEnum;
use UnitEnum;
use App\Filament\App\Resources\ChecklistTemplateResource\Pages;
use App\Filament\App\Resources\ChecklistTemplateResource\RelationManagers;
use App\Models\ChecklistTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChecklistTemplateResource extends Resource
{
   
    protected static ?string $model = ChecklistTemplate::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Checklist Templates';

    protected static string | \UnitEnum | null $navigationGroup = '📋 Research Management';

    protected static ?int $navigationSort = 1;

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
                                    ->afterStateUpdated(function (string $context, $state, callable $set) {
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
                                            ->default(function (callable $get) {
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
                        ->action(function (ChecklistTemplate $record) {
                            $newTemplate = $record->replicate();
                            $newTemplate->name = $record->name . ' (Copy)';
                            $newTemplate->is_default = false;
                            $newTemplate->created_by = auth()->id();
                            $newTemplate->save();

                            foreach ($record->templateItems as $item) {
                                $newItem = $item->replicate();
                                $newItem->checklist_template_id = $newTemplate->id;
                                $newItem->save();
                            }

                            return redirect()->route('filament.app.resources.checklist-templates.edit', $newTemplate);
                        })
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

    public static function getRelations(): array
    {
        return [
            TemplateItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListChecklistTemplates::route('/'),
            'create' => CreateChecklistTemplate::route('/create'),
            'view' => ViewChecklistTemplate::route('/{record}'),
            'edit' => EditChecklistTemplate::route('/{record}/edit'),
        ];
    }

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
