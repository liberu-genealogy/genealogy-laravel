<?php

namespace App\Filament\App\Resources\ChecklistTemplateResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TemplateItemsRelationManager extends RelationManager
{
    protected string $relationship = 'templateItems';

    protected ?string $title = 'Checklist Items';

   public static function form(Schema $form): Schema
       {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('order')
                            ->numeric()
                            ->default(fn () => $this->getOwnerRecord()->templateItems()->max('order') + 1),
                    ]),
                Forms\Components\Textarea::make('description')
                    ->rows(3)
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
                            ->label('Estimated Time'),
                        Forms\Components\Toggle::make('is_required')
                            ->label('Required Item'),
                    ]),
                Forms\Components\TagsInput::make('resources')
                    ->placeholder('Add helpful resources (URLs, document names, etc.)')
                    ->columnSpanFull(),
                Forms\Components\TagsInput::make('tips')
                    ->placeholder('Add helpful tips and notes')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->sortable()
                    ->width(60),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                Tables\Columns\TextColumn::make('estimated_time')
                    ->suffix(' min')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_required')
                    ->boolean()
                    ->alignCenter(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'research' => 'Research',
                        'documentation' => 'Documentation',
                        'verification' => 'Verification',
                        'analysis' => 'Analysis',
                        'follow_up' => 'Follow-up',
                    ]),
                Tables\Filters\TernaryFilter::make('is_required')
                    ->label('Required Items'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('order')
            ->defaultSort('order');
    }
}
