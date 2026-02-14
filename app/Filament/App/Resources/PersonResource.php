<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\PersonResource\Pages\ListPeople;
use App\Filament\App\Resources\PersonResource\Pages\CreatePerson;
use App\Filament\App\Resources\PersonResource\Pages\EditPerson;
use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\PersonResource\Pages;
use App\Models\Person;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;

class PersonResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationLabel = 'People';

    protected static string | \UnitEnum | null $navigationGroup = '👥 Family Tree';

    protected static ?int $navigationSort = 1;

    // protected static ?string $tenantRelationshipName = 'People';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('givn')->label('First Name'),
                TextInput::make('surn')->label('Last Name'),
                Select::make('sex')
                    ->options([
                        'M' => 'Male',
                        'F' => 'Female',
                    ])
                    ->label('Sex'),
                TextInput::make('child_in_family_id')->label('Child In Family ID'),
                TextInput::make('description')->label('Description'),
                TextInput::make('titl')->label('Title'),
                TextInput::make('name')->label('Name'),
                TextInput::make('appellative')->label('Appellative'),
                TextInput::make('email')->label('Email'),
                TextInput::make('phone')->label('Phone'),
                DateTimePicker::make('birthday')->label('Birthday'),
                DateTimePicker::make('deathday')->label('Deathday'),
                FileUpload::make('photo_url')
                    ->image()
                    ->label('Profile Photo')
                    ->directory('persons')
                    ->disk('public'),
                DateTimePicker::make('burial_day')->label('Burial Day'),
                TextInput::make('bank')->label('Bank'),
                TextInput::make('bank_account')->label('Bank Account'),
                TextInput::make('chan')->label('Chan'),
                TextInput::make('rin')->label('Rin'),
                TextInput::make('resn')->label('Resn'),
                TextInput::make('rfn')->label('Rfn'),
                TextInput::make('afn')->label('Afn'),
            ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('givn')->label('First Name'),
                TextColumn::make('surn')->label('Last Name'),
                TextColumn::make('sex')->label('Sex'),
                TextColumn::make('child_in_family_id')->label('Child In Family ID'),
                TextColumn::make('description')->label('Description'),
                TextColumn::make('titl')->label('Title'),
                TextColumn::make('name')->label('Name'),
                TextColumn::make('appellative')->label('Appellative'),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('phone')->label('Phone'),
                TextColumn::make('birthday')->label('Birthday'),
                TextColumn::make('deathday')->label('Deathday'),
                ImageColumn::make('photo_url')->label('Photo')->disk('public')->height(40)->width(40),
                TextColumn::make('burial_day')->label('Burial Day'),
                TextColumn::make('bank')->label('Bank'),
                TextColumn::make('bank_account')->label('Bank Account'),
                TextColumn::make('chan')->label('Chan'),
                TextColumn::make('rin')->label('Rin'),
                TextColumn::make('resn')->label('Resn'),
                TextColumn::make('rfn')->label('Rfn'),
                TextColumn::make('afn')->label('Afn'),
                TextColumn::make('created_at')->label('Created At')->sortable(),
                TextColumn::make('updated_at')->label('Updated At')->sortable(),

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
            RelationManagers\PhotosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPeople::route('/'),
            'create' => CreatePerson::route('/create'),
            'edit'   => EditPerson::route('/{record}/edit'),
        ];
    }
}
