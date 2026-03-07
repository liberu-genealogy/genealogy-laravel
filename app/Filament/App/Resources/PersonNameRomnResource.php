<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\PersonNameRomnResource\Pages\ListPersonNameRomns;
use App\Filament\App\Resources\PersonNameRomnResource\Pages\CreatePersonNameRomn;
use App\Filament\App\Resources\PersonNameRomnResource\Pages\EditPersonNameRomn;
use BackedEnum;
use App\Filament\App\Resources\PersonNameRomnResource\Pages;
use App\Models\PersonNameRomn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class PersonNameRomnResource extends Resource
{
    use PersonNameResourceTrait;

    protected static ?string $model = PersonNameRomn::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = '\ud83d\udc65 Family Tree';

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
            'index'  => ListPersonNameRomns::route('/'),
            'create' => CreatePersonNameRomn::route('/create'),
            'edit'   => EditPersonNameRomn::route('/{record}/edit'),
        ];
    }
}
