<?php

namespace App\Filament\Admin\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Admin\Resources\SiteSettingsResource\Pages\ListSiteSettings;
use App\Filament\Admin\Resources\SiteSettingsResource\Pages\CreateSiteSettings;
use App\Filament\Admin\Resources\SiteSettingsResource\Pages\EditSiteSettings;
use App\Filament\Admin\Resources\SiteSettingsResource\Pages;
use App\Models\SiteSettings;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class SiteSettingsResource extends Resource
{
    protected static ?string $model = SiteSettings::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Site Settings';

    protected static string | \UnitEnum | null $navigationGroup = '⚙️ Settings';

    protected static ?int $navigationSort = 1;

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->maxLength(255),
                TextInput::make('currency')
                    ->maxLength(255),
                TextInput::make('default_language')
                    ->maxLength(255),
                TextInput::make('address')
                    ->maxLength(255),
                TextInput::make('country')
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                TextInput::make('phone_01')
                    ->maxLength(255),
                TextInput::make('phone_02')
                    ->maxLength(255),
                TextInput::make('phone_03')
                    ->maxLength(255),
                TextInput::make('facebook')
                    ->maxLength(255),
                TextInput::make('twitter')
                    ->maxLength(255),
                TextInput::make('github')
                    ->maxLength(255),
                TextInput::make('youtube')
                    ->maxLength(255),
            ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('currency')
                    ->searchable(),
                TextColumn::make('default_language')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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

    public static function getPages(): array
    {
        return [
            'index'  => ListSiteSettings::route('/'),
            'create' => CreateSiteSettings::route('/create'),
            'edit'   => EditSiteSettings::route('/{record}/edit'),
        ];
    }
}
