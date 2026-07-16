<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Enums\PedigreeType;
use App\Filament\App\Resources\PersonResource\Pages\CreatePerson;
use App\Filament\App\Resources\PersonResource\Pages\EditPerson;
use App\Filament\App\Resources\PersonResource\Pages\ListPeople;
use App\Models\Person;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Override;

class PersonResource extends AppResource
{
    #[Override]
    protected static ?string $model = Person::class;

    #[Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-plus';

    #[Override]
    protected static ?string $navigationLabel = 'People';

    #[Override]
    protected static string|\UnitEnum|null $navigationGroup = '👥 Family Tree';

    #[Override]
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
                            ->options(Person::SEX_OPTIONS)
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
                        Select::make('pedigree')
                            ->options(PedigreeType::options())
                            ->label('Pedigree')
                            ->placeholder('Biological')
                            ->helperText('Link type to the child-in-family. Leave blank for biological.'),
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
                    ->options(Person::SEX_OPTIONS),
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

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ListPeople::route('/'),
            'create' => CreatePerson::route('/create'),
            'edit' => EditPerson::route('/{record}/edit'),
        ];
    }
}
