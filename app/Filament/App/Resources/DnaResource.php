<?php

namespace App\Filament\App\Resources;

use Override;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\App\Resources\DnaResource\Pages\ListDnas;
use App\Filament\App\Resources\DnaResource\Pages\CreateDna;
use App\Filament\App\Resources\DnaResource\Pages\EditDna;
use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\DnaResource\Pages;
use App\Models\Dna;
use Filament\Forms\Components\FileUpload;
use App\Filament\App\Resources\AppResource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;

class DnaResource extends AppResource
{
    #[\Override]
    protected static bool $isScopedToTenant = false;

    #[\Override]
    protected static ?string $model = Dna::class;

    #[\Override]
    protected static ?string $navigationLabel = 'DNA Records';

    #[\Override]
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-beaker';

    #[\Override]
    protected static string | \UnitEnum | null $navigationGroup = '🧬 DNA & Genetics';

    #[\Override]
    protected static ?int $navigationSort = 1;

    #[\Override]
    public static function shouldRegisterNavigation(): bool
    {
        // When premium is enabled globally, always show DNA navigation for all users
        if (config('premium.enabled')) {
            return true;
        }
        return auth()->user()?->isPremium() ?? false;
    }

    #[\Override]
    public static function canCreate(): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        return $user->canUploadDna();
    }

    #[Override]
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('attachment')
                    ->label('DNA Kit File(s)')
                    ->helperText('Upload one or more DNA kit files. Supported formats: 23andMe, AncestryDNA, MyHeritage, FamilyTreeDNA')
                    ->required()
                    ->multiple()
                    ->maxSize(100000)
                    ->disk('private')
                    ->directory('dna-form-imports')
                    ->visibility('private')
                    ->acceptedFileTypes(['text/plain', 'text/csv', 'application/zip', 'application/octet-stream']),
            ]);
    }

    #[Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('variable_name')
                    ->searchable(),
                TextColumn::make('file_name')
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

    #[\Override]
    public static function getPages(): array
    {
        return [
            'index'  => ListDnas::route('/'),
            'create' => CreateDna::route('/create'),
            'edit'   => EditDna::route('/{record}/edit'),
        ];
    }

    public static function visibility(): bool
    {
        // If premium is enabled, make visible to everyone; otherwise default to premium users only
        if (config('premium.enabled')) {
            return true;
        }
        return auth()->user()?->isPremium() ?? false;
    }
}
