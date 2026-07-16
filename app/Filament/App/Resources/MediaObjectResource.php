<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Enums\MediaType;
use App\Filament\App\Resources\MediaObjectResource\Pages\CreateMediaObject;
use App\Filament\App\Resources\MediaObjectResource\Pages\EditMediaObject;
use App\Filament\App\Resources\MediaObjectResource\Pages\ListMediaObjects;
use App\Models\MediaObject;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Override;

class MediaObjectResource extends AppResource
{
    #[Override]
    protected static ?string $model = MediaObject::class;

    #[Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    #[Override]
    protected static ?string $navigationLabel = 'Photos & Documents';

    #[Override]
    protected static string|\UnitEnum|null $navigationGroup = '📁 Media & Documents';

    #[Override]
    protected static ?int $navigationSort = 1;

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('media_type')
                    ->options(MediaType::options())
                    ->native(false),
                FileUpload::make('file_path')
                    ->label('File')
                    ->disk('private')
                    ->directory('media-objects')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf'])
                    ->maxSize(10240),
                TextInput::make('gid')
                    ->numeric(),
                TextInput::make('group')
                    ->maxLength(255),
                TextInput::make('titl')
                    ->maxLength(255),
                TextInput::make('obje_id')
                    ->maxLength(255),
                TextInput::make('rin')
                    ->maxLength(255),
            ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('media_type')
                    ->badge()
                    ->searchable(),
                TextColumn::make('gid')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('group')
                    ->searchable(),
                TextColumn::make('titl')
                    ->searchable(),
                TextColumn::make('obje_id')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('rin')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    #[Override]
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ListMediaObjects::route('/'),
            'create' => CreateMediaObject::route('/create'),
            'edit' => EditMediaObject::route('/{record}/edit'),
        ];
    }
}
