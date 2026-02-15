<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\RecordTypeResource\Pages;
use App\Models\RecordType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\KeyValue;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ToggleColumn;

class RecordTypeResource extends Resource
{
    protected static ?string $model = RecordType::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationLabel = 'Record Types';

    protected static ?string $navigationGroup = '⚙️ Settings';

    protected static ?int $navigationSort = 90;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('URL-friendly identifier'),
                        Select::make('category')
                            ->required()
                            ->options([
                                'vital' => 'Vital Records',
                                'census' => 'Census',
                                'newspaper' => 'Newspaper',
                                'parish' => 'Parish Records',
                                'military' => 'Military',
                                'land' => 'Land & Property',
                                'probate' => 'Probate',
                                'immigration' => 'Immigration',
                                'electoral' => 'Electoral',
                                'gro_index' => 'GRO Index',
                                'poor_law' => 'Poor Law',
                                'court' => 'Court Records',
                                'other' => 'Other',
                            ])
                            ->helperText('Category for grouping similar record types'),
                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Display Settings')
                    ->schema([
                        TextInput::make('icon')
                            ->maxLength(255)
                            ->helperText('Heroicon name (e.g., heroicon-o-newspaper)'),
                        Select::make('color')
                            ->options([
                                'primary' => 'Primary',
                                'success' => 'Success',
                                'danger' => 'Danger',
                                'warning' => 'Warning',
                                'info' => 'Info',
                                'gray' => 'Gray',
                            ])
                            ->helperText('Badge color for this record type'),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),
                        Toggle::make('is_active')
                            ->default(true)
                            ->helperText('Whether this record type is available for selection'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Metadata Schema')
                    ->schema([
                        KeyValue::make('metadata_schema')
                            ->keyLabel('Field Name')
                            ->valueLabel('Field Type')
                            ->helperText('Define the fields specific to this record type')
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'vital' => 'success',
                        'census' => 'primary',
                        'newspaper' => 'info',
                        'parish' => 'primary',
                        'military' => 'danger',
                        'electoral' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucwords(str_replace('_', ' ', $state)))
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->sortable(),
                TextColumn::make('sources_count')
                    ->counts('sources')
                    ->label('Sources'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'vital' => 'Vital Records',
                        'census' => 'Census',
                        'newspaper' => 'Newspaper',
                        'parish' => 'Parish Records',
                        'military' => 'Military',
                        'electoral' => 'Electoral',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueLabel('Active only')
                    ->falseLabel('Inactive only')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecordTypes::route('/'),
            'create' => Pages\CreateRecordType::route('/create'),
            'edit' => Pages\EditRecordType::route('/{record}/edit'),
        ];
    }
}
