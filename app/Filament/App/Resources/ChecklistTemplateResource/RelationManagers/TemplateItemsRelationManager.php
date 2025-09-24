<?php

namespace App\Filament\App\Resources\ChecklistTemplateResource\RelationManagers;

use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TagsInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Schema;

class TemplateItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'templateItems';

    protected static ?string $title = 'Checklist Items';

   public function form(Schema $schema): Schema
       {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->columnSpan(2),
                        TextInput::make('order')
                            ->numeric()
                            ->default(fn () => $this->getOwnerRecord()->templateItems()->max('order') + 1),
                    ]),
                Textarea::make('description')
                    ->rows(3)
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
                            ->label('Estimated Time'),
                        Toggle::make('is_required')
                            ->label('Required Item'),
                    ]),
                TagsInput::make('resources')
                    ->placeholder('Add helpful resources (URLs, document names, etc.)')
                    ->columnSpanFull(),
                TagsInput::make('tips')
                    ->placeholder('Add helpful tips and notes')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('order')
                    ->sortable()
                    ->width(60),
                TextColumn::make('title')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('category')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                TextColumn::make('estimated_time')
                    ->suffix(' min')
                    ->alignCenter()
                    ->sortable(),
                IconColumn::make('is_required')
                    ->boolean()
                    ->alignCenter(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options([
                        'research' => 'Research',
                        'documentation' => 'Documentation',
                        'verification' => 'Verification',
                        'analysis' => 'Analysis',
                        'follow_up' => 'Follow-up',
                    ]),
                TernaryFilter::make('is_required')
                    ->label('Required Items'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('order')
            ->defaultSort('order');
    }
}
