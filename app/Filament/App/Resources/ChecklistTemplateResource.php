<?php

namespace App\Filament\App\Resources;

use BackedEnum;
use UnitEnum;
use App\Filament\App\Resources\ChecklistTemplateResource\Pages;
use App\Filament\App\Resources\ChecklistTemplateResource\RelationManagers;
use App\Models\ChecklistTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChecklistTemplateResource extends Resource
{
   
    protected static ?string $model = ChecklistTemplate::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Checklist Templates';

    protected static string | UnitEnum | null $navigationGroup = '📋 Research Management';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Template Information')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $context, $state, callable $set) {
                                        if ($context === 'create') {
                                            $set('description', "Research checklist for {$state}");
                                        }
                                    }),
                                Forms\Components\Select::make('category')
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
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('difficulty_level')
                                    ->options([
                                        'beginner' => 'Beginner',
                                        'intermediate' => 'Intermediate',
                                        'advanced' => 'Advanced',
                                    ])
                                    ->required()
                                    ->default('beginner'),
                                Forms\Components\TextInput::make('estimated_time')
                                    ->numeric()
                                    ->suffix('minutes')
                                    ->label('Estimated Time'),
                                Forms\Components\Hidden::make('created_by')
                                    ->default(auth()->id()),
                            ]),
                    ]),

                Forms\Components\Section::make('Template Settings')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Toggle::make('is_public')
                                    ->label('Make Public')
                                    ->helperText('Allow other users to use this template'),
                                Forms\Components\Toggle::make('is_default')
                                    ->label('Default Template')
                                    ->helperText('Show as a recommended template')
                                    ->visible(fn () => auth()->user()->can('manage_default_templates')),
                            ]),
                        Forms\Components\TagsInput::make('tags')
                            ->placeholder('Add tags to help categorize this template')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Checklist Items')
                    ->schema([
                        Forms\Components\Repeater::make('templateItems')
                            ->relationship()
                            ->schema([
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->required()
                                            ->columnSpan(2),
                                        Forms\Components\TextInput::make('order')
                                            ->numeric()
                                            ->default(function (callable $get) {
                                                $items = $get('../../templateItems') ?? [];
                                                return count($items) + 1;
                                            }),
                                    ]),
                                Forms\Components\Textarea::make('description')
                                    ->rows(2)
                                    ->columnSpanFull(),
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\Select::make('category')
                                            ->options([
                                                'research' => 'Research',
                                                'documentation' => 'Documentation',
                                                'verification' => 'Verification',
                                                'analysis' => 'Analysis',
                                                'follow_up' => 'Follow-up',
                                            ])
                                            ->default('research'),
                                        Forms\Components\TextInput::make('estimated_time')
                                            ->numeric()
                                            ->suffix('minutes')
                                            ->label('Est. Time'),
                                        Forms\Components\Toggle::make('is_required')
                                            ->label('Required'),
                                    ]),
                                Forms\Components\TagsInput::make('resources')
                                    ->placeholder('Add helpful resources (URLs, document names, etc.)')
                                    ->columnSpanFull(),
                                Forms\Components\TagsInput::make('tips')
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('category')
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
                Tables\Columns\TextColumn::make('difficulty_level')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'beginner' => 'success',
                        'intermediate' => 'warning',
                        'advanced' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                Tables\Columns\TextColumn::make('templateItems_count')
                    ->counts('templateItems')
                    ->label('Items')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('estimated_time')
                    ->suffix(' min')
                    ->label('Est. Time')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_public')
                    ->boolean()
                    ->label('Public')
                    ->alignCenter(),
                Tables\Columns\IconColumn::make('is_default')
                    ->boolean()
                    ->label('Default')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Created By')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
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
                Tables\Filters\SelectFilter::make('difficulty_level')
                    ->options([
                        'beginner' => 'Beginner',
                        'intermediate' => 'Intermediate',
                        'advanced' => 'Advanced',
                    ]),
                Tables\Filters\TernaryFilter::make('is_public')
                    ->label('Public Templates'),
                Tables\Filters\TernaryFilter::make('is_default')
                    ->label('Default Templates'),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('duplicate')
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
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TemplateItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChecklistTemplates::route('/'),
            'create' => Pages\CreateChecklistTemplate::route('/create'),
            'view' => Pages\ViewChecklistTemplate::route('/{record}'),
            'edit' => Pages\EditChecklistTemplate::route('/{record}/edit'),
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
