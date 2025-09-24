<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\ChanResource\Pages\ListChans;
use App\Filament\App\Resources\ChanResource\Pages\CreateChan;
use App\Filament\App\Resources\ChanResource\Pages\EditChan;
use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\ChanResource\Pages;
use App\Models\Chan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class ChanResource extends Resource
{
    protected static ?string $model = Chan::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = 'Person';

    protected static ?string $navigationLabel = ' Chan';

    // protected static ?string $tenantRelationshipName = 'team';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
        ->components([
            TextInput::make('group')
                ->maxLength(255),
            TextInput::make('gid')
                ->numeric(),
            TextInput::make('date')
                ->maxLength(255),
            TextInput::make('time')
                ->maxLength(255),
        ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('group')
                ->searchable(),
            TextColumn::make('gid')
                ->numeric()
                ->sortable(),
            TextColumn::make('date')
                ->searchable(),
            TextColumn::make('time')
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
            'index'  => ListChans::route('/'),
            'create' => CreateChan::route('/create'),
            'edit'   => EditChan::route('/{record}/edit'),
        ];
    }
}
