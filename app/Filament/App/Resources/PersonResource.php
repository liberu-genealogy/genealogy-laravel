<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
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
                Section::make('Basic Information')
                    ->description('Core identity and personal details')
                    ->icon('heroicon-o-user')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('photo_url')
                            ->image()
                            ->label('Profile Photo')
                            ->directory('persons')
                            ->disk('public')
                            ->columnSpanFull(),
                        TextInput::make('givn')->label('First Name'),
                        TextInput::make('surn')->label('Last Name'),
                        TextInput::make('titl')->label('Title'),
                        TextInput::make('appellative')->label('Appellative'),
                        TextInput::make('name')->label('Full Name'),
                        Select::make('sex')
                            ->options([
                                'M' => 'Male',
                                'F' => 'Female',
                            ])
                            ->label('Sex'),
                        TextInput::make('description')->label('Description')->columnSpanFull(),
                    ]),

                Section::make('Vital Records')
                    ->description('Birth, death, and burial information')
                    ->icon('heroicon-o-calendar')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('birthday')->label('Date of Birth'),
                        DateTimePicker::make('deathday')->label('Date of Death'),
                        DateTimePicker::make('burial_day')->label('Burial Date'),
                        TextInput::make('child_in_family_id')->label('Child in Family ID'),
                    ]),

                Section::make('Contact Information')
                    ->description('Email and phone details')
                    ->icon('heroicon-o-envelope')
                    ->columns(2)
                    ->schema([
                        TextInput::make('email')->label('Email')->email(),
                        TextInput::make('phone')->label('Phone'),
                    ]),

                Section::make('Record References')
                    ->description('Genealogy record identifiers and metadata')
                    ->icon('heroicon-o-document-text')
                    ->columns(3)
                    ->collapsed()
                    ->schema([
                        TextInput::make('rin')->label('RIN'),
                        TextInput::make('rfn')->label('RFN'),
                        TextInput::make('afn')->label('AFN'),
                        TextInput::make('resn')->label('Restriction'),
                        TextInput::make('chan')->label('Change Date'),
                        TextInput::make('bank')->label('Bank'),
                        TextInput::make('bank_account')->label('Bank Account'),
                    ]),
            ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo_url')->label('Photo')->disk('public')->height(40)->width(40)->circular(),
                TextColumn::make('givn')->label('First Name')->searchable()->sortable(),
                TextColumn::make('surn')->label('Last Name')->searchable()->sortable(),
                TextColumn::make('sex')->label('Sex')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'M' => 'info',
                        'F' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('birthday')->label('Born')->date('Y')->sortable(),
                TextColumn::make('deathday')->label('Died')->date('Y')->sortable(),
                TextColumn::make('email')->label('Email')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('phone')->label('Phone')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->label('Added')->since()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('sex')
                    ->options([
                        'M' => 'Male',
                        'F' => 'Female',
                    ]),
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
