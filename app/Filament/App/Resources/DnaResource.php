<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\DnaResource\Pages\CreateDna;
use App\Filament\App\Resources\DnaResource\Pages\EditDna;
use App\Filament\App\Resources\DnaResource\Pages\ListDnas;
use App\Models\Dna;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Override;

class DnaResource extends AppResource
{
    #[Override]
    protected static bool $isScopedToTenant = false;

    #[Override]
    protected static ?string $model = Dna::class;

    #[Override]
    protected static ?string $navigationLabel = 'DNA Records';

    #[Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-beaker';

    #[Override]
    protected static string|\UnitEnum|null $navigationGroup = '🧬 DNA & Matching';

    #[Override]
    protected static ?int $navigationSort = 1;

    #[Override]
    public static function shouldRegisterNavigation(): bool
    {
        // When premium is enabled globally, always show DNA navigation for all users
        if (config('premium.enabled')) {
            return true;
        }

        return auth()->user()?->isPremium() ?? false;
    }

    #[Override]
    public static function canCreate(): bool
    {
        $user = auth()->user();
        if (! $user) {
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
                Checkbox::make('consent_given')
                    ->label('I consent to my DNA data being stored and used for relative matching')
                    ->helperText('Required. Your genetic data will not be stored or matched without this consent.')
                    ->accepted()
                    ->required(),
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
                IconColumn::make('consent_given')
                    ->label('Consent')
                    ->boolean()
                    ->sortable(),
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
            'index' => ListDnas::route('/'),
            'create' => CreateDna::route('/create'),
            'edit' => EditDna::route('/{record}/edit'),
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
