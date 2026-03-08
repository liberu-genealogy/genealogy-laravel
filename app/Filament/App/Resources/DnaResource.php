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
    protected static bool $isScopedToTenant = false;

    protected static ?string $model = Dna::class;

    protected static ?string $navigationLabel = 'DNA Records';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-beaker';

    protected static string | \UnitEnum | null $navigationGroup = '🧬 DNA & Genetics';

    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        // When premium is enabled globally, always show DNA navigation for all users
        if (config('premium.enabled')) {
            return true;
        }
        return auth()->user()?->isPremium() ?? false;
    }

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
                    ->directory('dna-form-imports')
                    ->visibility('private')
                    ->acceptedFileTypes(['text/plain', 'text/csv', 'application/zip']),
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
