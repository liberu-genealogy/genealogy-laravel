<?php

namespace App\Filament\App\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use App\Filament\App\Resources\AuthorResource\Pages\ListAuthors;
use App\Filament\App\Resources\AuthorResource\Pages\CreateAuthor;
use App\Filament\App\Resources\AuthorResource\Pages\EditAuthor;
use App\Models\Author;
use Filament\Forms;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class AuthorResource extends Resource
{
    use NameDescriptionActiveResourceTrait;

    protected static ?string $model = Author::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Author';

    protected static string | \UnitEnum | null $navigationGroup = '🔍 Research & Analysis';

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
            'index'  => ListAuthors::route('/'),
            'create' => CreateAuthor::route('/create'),
            'edit'   => EditAuthor::route('/{record}/edit'),
        ];
    }
}
