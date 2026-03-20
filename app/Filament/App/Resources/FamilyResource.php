<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\FamilyResource\Pages\ListFamilies;
use App\Filament\App\Resources\FamilyResource\Pages\CreateFamily;
use App\Filament\App\Resources\FamilyResource\Pages\EditFamily;
use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\FamilyResource\Pages;
use App\Models\Family;
use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\App\Resources\AppResource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class FamilyResource extends AppResource
{
    protected static ?string $model = Family::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Families';
    protected static string | \UnitEnum | null $navigationGroup = '👥 Family Tree';
    protected static ?int $navigationSort = 2;

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Family Members')
                    ->description('Identify the husband and wife in this family unit')
                    ->icon('heroicon-o-users')
                    ->columns(2)
                    ->schema([
                        TextInput::make('husband_id')
                            ->label('Husband ID')
                            ->numeric(),
                        TextInput::make('wife_id')
                            ->label('Wife ID')
                            ->numeric(),
                        TextInput::make('nchi')
                            ->label('Number of Children')
                            ->maxLength(255),
                        TextInput::make('type_id')
                            ->label('Family Type')
                            ->numeric(),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),

                Section::make('Notes')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Textarea::make('description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),

                Section::make('Record References')
                    ->icon('heroicon-o-hashtag')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextInput::make('chan')
                            ->label('Change Date')
                            ->maxLength(255),
                        TextInput::make('rin')
                            ->label('RIN')
                            ->maxLength(255),
                    ]),
            ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('husband_id')
                    ->label('Husband ID')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('wife_id')
                    ->label('Wife ID')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('nchi')
                    ->label('Children')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListFamilies::route('/'),
            'create' => CreateFamily::route('/create'),
            'edit'   => EditFamily::route('/{record}/edit'),
        ];
    }
}
