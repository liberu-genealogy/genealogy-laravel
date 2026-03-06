<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\FamilyEventResource\Pages\ListFamilyEvents;
use App\Filament\App\Resources\FamilyEventResource\Pages\CreateFamilyEvent;
use App\Filament\App\Resources\FamilyEventResource\Pages\EditFamilyEvent;
use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\FamilyEventResource\Pages;
use App\Models\FamilyEvent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class FamilyEventResource extends Resource
{
    use EventResourceTrait;

    protected static ?string $model = FamilyEvent::class;

    protected static ?string $navigationLabel = 'Family Events';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string | \UnitEnum | null $navigationGroup = '👥 Family Tree';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components(array_merge(
                [TextInput::make('family_id')->required()->numeric()],
                static::eventFormFields(),
                [
                    TextInput::make('places_id')->numeric(),
                    TextInput::make('husb')->numeric(),
                    TextInput::make('wife')->numeric(),
                ]
            ));
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns(array_merge(
                [
                    TextColumn::make('family_id')->numeric()->sortable(),
                    TextColumn::make('places_id')->numeric()->sortable(),
                ],
                static::eventTableColumns(),
                [
                    TextColumn::make('husb')->numeric()->sortable(),
                    TextColumn::make('wife')->numeric()->sortable(),
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
            'index'  => ListFamilyEvents::route('/'),
            'create' => CreateFamilyEvent::route('/create'),
            'edit'   => EditFamilyEvent::route('/{record}/edit'),
        ];
    }
}
