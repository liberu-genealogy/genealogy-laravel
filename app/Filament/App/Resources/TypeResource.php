<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\TypeResource\Pages\ListTypes;
use App\Filament\App\Resources\TypeResource\Pages\CreateType;
use App\Filament\App\Resources\TypeResource\Pages\EditType;
use BackedEnum;
use App\Filament\App\Resources\TypeResource\Pages;
use App\Models\Type;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class TypeResource extends Resource
{
    use NameDescriptionActiveResourceTrait;

    protected static ?string $model = Type::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = '🛠️ Data Management';

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
            'index'  => ListTypes::route('/'),
            'create' => CreateType::route('/create'),
            'edit'   => EditType::route('/{record}/edit'),
        ];
    }
}
