<?php

namespace App\Filament\App\Resources;

use App\Models\Repository;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

final class RepositoryResource extends Resource
{
    protected static ?string $model = Repository::class;

    protected static ?string $navigationLabel = 'Repository';

    protected static ?string $navigationGroup = 'Author';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('group')
                    ->maxLength(255),
                Forms\Components\TextInput::make('gid')
                    ->numeric()
                    ->rules(['integer', 'min:0']),
                Forms\Components\TextInput::make('name')
                    ->maxLength(255)
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('date'),
                Forms\Components\Toggle::make('is_active')
                    ->default(true),
                Forms\Components\TextInput::make('type_id')
                    ->numeric()
                    ->rules(['integer', 'exists:types,id']),
                Forms\Components\TextInput::make('repo')
                    ->maxLength(255),
                Forms\Components\TextInput::make('addr_id')
                    ->numeric()
                    ->rules(['integer', 'exists:addresses,id']),
                Forms\Components\TextInput::make('rin')
                    ->maxLength(255),
                Forms\Components\TextInput::make('phon')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('fax')
                    ->maxLength(255),
                Forms\Components\TextInput::make('www')
                    ->url()
                    ->maxLength(255),
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
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('is_active')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('repo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('addr_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phon')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fax')
                    ->searchable(),
                Tables\Columns\TextColumn::make('www')
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
            'index'  => Pages\ListRepositories::route('/'),
            'create' => Pages\CreateRepository::route('/create'),
            'edit'   => Pages\EditRepository::route('/{record}/edit'),
        ];
    }
}