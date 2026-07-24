<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\FamilyResource\Pages\CreateFamily;
use App\Filament\App\Resources\FamilyResource\Pages\EditFamily;
use App\Filament\App\Resources\FamilyResource\Pages\ListFamilies;
use App\Filament\App\Resources\FamilyResource\RelationManagers\ChildrenRelationManager;
use App\Models\Family;
use App\Models\Person;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Override;

class FamilyResource extends AppResource
{
    #[Override]
    protected static ?string $model = Family::class;

    #[Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    #[Override]
    protected static ?string $navigationLabel = 'Families';

    #[Override]
    protected static string|\UnitEnum|null $navigationGroup = '👥 Family Tree';

    #[Override]
    protected static ?int $navigationSort = 2;

    /** "Givn Surn" for a person, or '' when the person is null/unnamed (so a column placeholder shows). */
    protected static function personName(?Person $person): string
    {
        return $person ? trim("{$person->givn} {$person->surn}") : '';
    }

    /** Eager-load the parents and children the table/name columns read, so the list isn't N+1. */
    #[Override]
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['husband', 'wife', 'children']);
    }

    /**
     * A searchable person-picker for one parent slot. No sex filter (same-sex
     * families allowed) and not required (single-parent families allowed).
     * Writes the person id straight to the husband_id/wife_id column. The
     * Person query is team-scoped by BelongsToTenant, so it only offers the
     * current team's people.
     */
    protected static function parentSelect(string $field, string $label): Select
    {
        $optionLabel = static fn (Person $p): string => trim("{$p->givn} {$p->surn}")." (#{$p->id})";

        return Select::make($field)
            ->label($label)
            ->searchable()
            ->getSearchResultsUsing(static fn (string $search): array => Person::query()
                ->where(fn ($q) => $q->where('givn', 'like', "%{$search}%")
                    ->orWhere('surn', 'like', "%{$search}%"))
                ->limit(50)
                ->get()
                ->mapWithKeys(static fn (Person $p): array => [$p->id => $optionLabel($p)])
                ->all())
            ->getOptionLabelUsing(static fn ($value): ?string => ($p = Person::find($value)) ? $optionLabel($p) : null);
    }

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Parents')
                    ->description('Either or both parents, any sex — single-parent and same-sex families are allowed. Leave one blank for a single parent.')
                    ->icon('heroicon-o-users')
                    ->columns(2)
                    ->schema([
                        self::parentSelect('husband_id', 'Parent 1'),
                        self::parentSelect('wife_id', 'Parent 2'),
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
                TextColumn::make('parent1')
                    ->label('Parent 1')
                    ->state(fn (Family $record): string => self::personName($record->husband))
                    ->placeholder('—'),
                TextColumn::make('parent2')
                    ->label('Parent 2')
                    ->state(fn (Family $record): string => self::personName($record->wife))
                    ->placeholder('—'),
                TextColumn::make('children')
                    ->label('Children')
                    ->state(fn (Family $record): array => $record->children
                        ->map(fn (Person $child): string => self::personName($child))
                        ->all())
                    ->badge()
                    ->listWithLineBreaks()
                    ->limitList(3)
                    ->expandableLimitedList()
                    ->placeholder('—'),
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
            ChildrenRelationManager::class,
        ];
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ListFamilies::route('/'),
            'create' => CreateFamily::route('/create'),
            'edit' => EditFamily::route('/{record}/edit'),
        ];
    }
}
