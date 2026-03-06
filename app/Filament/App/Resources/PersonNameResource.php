<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\PersonNameResource\Pages\ListPersonNames;
use App\Filament\App\Resources\PersonNameResource\Pages\CreatePersonName;
use App\Filament\App\Resources\PersonNameResource\Pages\EditPersonName;
use BackedEnum;
use App\Filament\App\Resources\PersonNameResource\Pages;
use App\Models\PersonName;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class PersonNameResource extends Resource
{
    use PersonNameResourceTrait;

    protected static ?string $model = PersonName::class;

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
            'index'  => ListPersonNames::route('/'),
            'create' => CreatePersonName::route('/create'),
            'edit'   => EditPersonName::route('/{record}/edit'),
        ];
    }
}
