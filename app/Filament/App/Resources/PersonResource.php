<?php

namespace App\Filament\App\Resources;

use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\PersonResource\Pages;
use App\Models\Person;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class PersonResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = ' Add A Person';

    protected static string | UnitEnum | null $navigationGroup = 'Person';

    // protected static ?string $tenantRelationshipName = 'People';

    #[\Override]
    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('givn')->label('First Name'),
                Forms\Components\TextInput::make('surn')->label('Last Name'),
                Forms\Components\Select::make('sex')
                    ->options([
                        'M' => 'Male',
                        'F' => 'Female',
                    ])
                    ->label('Sex'),
                Forms\Components\TextInput::make('child_in_family_id')->label('Child In Family ID'),
                Forms\Components\TextInput::make('description')->label('Description'),
                Forms\Components\TextInput::make('titl')->label('Title'),
                Forms\Components\TextInput::make('name')->label('Name'),
                Forms\Components\TextInput::make('appellative')->label('Appellative'),
                Forms\Components\TextInput::make('email')->label('Email'),
                Forms\Components\TextInput::make('phone')->label('Phone'),
                Forms\Components\DateTimePicker::make('birthday')->label('Birthday'),
                Forms\Components\DateTimePicker::make('deathday')->label('Deathday'),
                Forms\Components\DateTimePicker::make('burial_day')->label('Burial Day'),
                Forms\Components\TextInput::make('bank')->label('Bank'),
                Forms\Components\TextInput::make('bank_account')->label('Bank Account'),
                Forms\Components\TextInput::make('chan')->label('Chan'),
                Forms\Components\TextInput::make('rin')->label('Rin'),
                Forms\Components\TextInput::make('resn')->label('Resn'),
                Forms\Components\TextInput::make('rfn')->label('Rfn'),
                Forms\Components\TextInput::make('afn')->label('Afn'),
            ]);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('givn')->label('First Name'),
                Tables\Columns\TextColumn::make('surn')->label('Last Name'),
                Tables\Columns\TextColumn::make('sex')->label('Sex'),
                Tables\Columns\TextColumn::make('child_in_family_id')->label('Child In Family ID'),
                Tables\Columns\TextColumn::make('description')->label('Description'),
                Tables\Columns\TextColumn::make('titl')->label('Title'),
                Tables\Columns\TextColumn::make('name')->label('Name'),
                Tables\Columns\TextColumn::make('appellative')->label('Appellative'),
                Tables\Columns\TextColumn::make('email')->label('Email'),
                Tables\Columns\TextColumn::make('phone')->label('Phone'),
                Tables\Columns\TextColumn::make('birthday')->label('Birthday'),
                Tables\Columns\TextColumn::make('deathday')->label('Deathday'),
                Tables\Columns\TextColumn::make('burial_day')->label('Burial Day'),
                Tables\Columns\TextColumn::make('bank')->label('Bank'),
                Tables\Columns\TextColumn::make('bank_account')->label('Bank Account'),
                Tables\Columns\TextColumn::make('chan')->label('Chan'),
                Tables\Columns\TextColumn::make('rin')->label('Rin'),
                Tables\Columns\TextColumn::make('resn')->label('Resn'),
                Tables\Columns\TextColumn::make('rfn')->label('Rfn'),
                Tables\Columns\TextColumn::make('afn')->label('Afn'),
                Tables\Columns\TextColumn::make('created_at')->label('Created At')->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->label('Updated At')->sortable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    #[\Override]
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPeople::route('/'),
            'create' => Pages\CreatePerson::route('/create'),
            'edit'   => Pages\EditPerson::route('/{record}/edit'),
        ];
    }
}
