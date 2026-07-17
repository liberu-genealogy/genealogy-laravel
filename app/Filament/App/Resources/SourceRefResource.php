<?php

declare(strict_types=1);

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\SourceRefResource\Pages\CreateSourceRef;
use App\Filament\App\Resources\SourceRefResource\Pages\EditSourceRef;
use App\Filament\App\Resources\SourceRefResource\Pages\ListSourceRefs;
use App\Models\Person;
use App\Models\Source;
use App\Models\SourceRef;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Override;

class SourceRefResource extends AppResource
{
    /** Mirrors SourceRef::qualityLabel() — GEDCOM QUAY 0-3. */
    public const QUAY_OPTIONS = [
        '0' => 'Unreliable',
        '1' => 'Questionable',
        '2' => 'Secondary evidence',
        '3' => 'Primary evidence',
    ];

    #[Override]
    protected static ?string $model = SourceRef::class;

    #[Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-link';

    #[Override]
    protected static ?string $navigationLabel = 'Source References';

    #[Override]
    protected static string|\UnitEnum|null $navigationGroup = '🔍 Research & Analysis';

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // `group`/`gid` are the importer's pseudo-polymorphic key and carry no
                // FKs, so a free-text edit silently re-points the row at an unrelated
                // record. Fixed to 'indi' for anything created here — the only group
                // this UI writes. Imported rows show their own group read-only, and
                // their `gid` is left untouched because the person Select below hides
                // for every group but 'indi'.
                TextInput::make('group')
                    ->label('Evidences')
                    ->default(SourceRef::GROUP_INDI)
                    ->readOnly(),
                Select::make('gid')
                    ->label('Person')
                    ->options(fn (): array => Person::query()->orderBy('name')->pluck('name', 'id')->all())
                    ->searchable()
                    ->required()
                    ->visible(fn (Get $get): bool => $get('group') === SourceRef::GROUP_INDI),
                ...self::citationComponents(),
            ]);
    }

    /**
     * The citation half of a SOUR reference — which source, where in it, how
     * trusted. Shared with PersonResource's SourcesRelationManager, which supplies
     * `group`/`gid` from the relation instead of from the form.
     *
     * @return list<Field>
     */
    public static function citationComponents(): array
    {
        return [
            Select::make('sour_id')
                ->label('Source')
                ->options(fn (): array => self::sourceOptions())
                ->searchable()
                ->required(),
            TextInput::make('page')
                ->label('Page / citation detail')
                ->maxLength(255),
            Select::make('quay')
                ->label('Confidence')
                ->helperText('GEDCOM QUAY: how far the source is trusted.')
                // Merge the record's own value in when it isn't 0-3 (imports carry free
                // text here), so editing an off-list row can't silently blank it.
                ->options(function ($record): array {
                    $options = self::QUAY_OPTIONS;
                    if ($record?->quay && ! array_key_exists($record->quay, $options)) {
                        $options[$record->quay] = $record->quay;
                    }

                    return $options;
                }),
            Textarea::make('text')
                ->label('Quoted text')
                ->maxLength(255)
                ->columnSpanFull(),
        ];
    }

    /**
     * Sources keyed by id. The importer fills `titl`; the app's own form fills
     * `name` — so fall back rather than render a blank option.
     *
     * ponytail: loads every source and filters client-side. Swap to
     * getSearchResultsUsing() if a tree ever carries thousands.
     *
     * @return array<int, string>
     */
    private static function sourceOptions(): array
    {
        return Source::query()
            ->orderBy('name')
            ->get(['id', 'name', 'titl'])
            ->mapWithKeys(fn (Source $source): array => [
                $source->id => $source->name ?: $source->titl ?: "Source #{$source->id}",
            ])
            ->all();
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group')
                    ->searchable(),
                TextColumn::make('gid')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sour_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('text')
                    ->searchable(),
                TextColumn::make('quay')
                    ->searchable(),
                TextColumn::make('page')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            //
        ];
    }

    #[Override]
    public static function getPages(): array
    {
        return [
            'index' => ListSourceRefs::route('/'),
            'create' => CreateSourceRef::route('/create'),
            'edit' => EditSourceRef::route('/{record}/edit'),
        ];
    }
}
