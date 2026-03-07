<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\PersonNameFoneResource\Pages\ListPersonNameFones;
use App\Filament\App\Resources\PersonNameFoneResource\Pages\CreatePersonNameFone;
use App\Filament\App\Resources\PersonNameFoneResource\Pages\EditPersonNameFone;
use BackedEnum;
use App\Filament\App\Resources\PersonNameFoneResource\Pages;
use App\Models\PersonNameFone;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class PersonNameFoneResource extends Resource
{
    use PersonNameResourceTrait;

    protected static ?string $model = PersonNameFone::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = '👥 Family Tree';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return static::baseForm($schema);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return static::baseTable($table);
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
            'index'  => ListPersonNameFones::route('/'),
            'create' => CreatePersonNameFone::route('/create'),
            'edit'   => EditPersonNameFone::route('/{record}/edit'),
        ];
    }
}
