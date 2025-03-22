<?php

namespace App\Filament\App\Resources;

use App\Models\SourceRepo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

final class SourceRepoResource extends Resource
{
    protected static ?string $model = SourceRepo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('group')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('gid')
                    ->required()
                    ->numeric()
                    ->rules(['integer', 'min:0']),
                Forms\Components\TextInput::make('repo_id')
                    ->required()
                    ->maxLength(255)
                    ->rules(['exists:repositories,id']),
                Forms\Components\Textarea::make('caln')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('group')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gid')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('repo_id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index'  => Pages\ListSourceRepos::route('/'),
            'create' => Pages\CreateSourceRepo::route('/create'),
            'edit'   => Pages\EditSourceRepo::route('/{record}/edit'),
        ];
    }
}