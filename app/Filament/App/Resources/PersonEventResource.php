<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\PersonEventResource\Pages\ListPersonEvents;
use App\Filament\App\Resources\PersonEventResource\Pages\CreatePersonEvent;
use App\Filament\App\Resources\PersonEventResource\Pages\EditPersonEvent;
use BackedEnum;
use App\Filament\App\Resources\PersonEventResource\Pages;
use App\Models\PersonEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class PersonEventResource extends Resource
{
    use EventResourceTrait;

    protected static ?string $model = PersonEvent::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = '👥 Family Tree';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components(array_merge(
                static::eventFormFields(),
                [
                    TextInput::make('attr')->maxLength(65535)->columnSpanFull(),
                    TextInput::make('adop')->maxLength(255),
                    TextInput::make('adop_famc')->maxLength(255),
                    TextInput::make('birt_famc')->maxLength(255),
                    TextInput::make('person_id')->numeric(),
                ]
            ));
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns(array_merge(
                static::eventTableColumns(),
                [
                    TextColumn::make('adop')->searchable(),
                    TextColumn::make('adop_famc')->searchable(),
                    TextColumn::make('birt_famc')->searchable(),
                    TextColumn::make('person_id')->numeric()->sortable(),
                ]
            ))
            ->filters([])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
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
            'index'  => ListPersonEvents::route('/'),
            'create' => CreatePersonEvent::route('/create'),
            'edit'   => EditPersonEvent::route('/{record}/edit'),
        ];
    }
}
