<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ChanResource\Pages\CreateChan;
use App\Filament\App\Resources\ChanResource\Pages\EditChan;
use App\Filament\App\Resources\ChanResource\Pages\ListChans;
use App\Models\Chan;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Override;

class ChanResource extends AppResource
{
    #[Override]
    protected static ?string $model = Chan::class;

    #[Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clock';

    #[Override]
    protected static string|\UnitEnum|null $navigationGroup = '🗂️ GEDCOM Detail';

    #[Override]
    protected static ?string $navigationLabel = 'Chan';

    // protected static ?string $tenantRelationshipName = 'team';

    #[Override]
    public static function canCreate(): bool
    {
        return auth()->check();
    }

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('group')
                    ->label('Group')
                    ->maxLength(255),
                TextInput::make('gid')
                    ->label('Group ID')
                    ->numeric(),
                TextInput::make('date')
                    ->label('Change Date')
                    ->maxLength(255),
                TextInput::make('time')
                    ->label('Change Time')
                    ->maxLength(255),
            ]);
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
                TextColumn::make('date')
                    ->searchable(),
                TextColumn::make('time')
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
            'index' => ListChans::route('/'),
            'create' => CreateChan::route('/create'),
            'edit' => EditChan::route('/{record}/edit'),
        ];
    }
}
