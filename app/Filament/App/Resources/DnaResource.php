<?php

namespace App\Filament\App\Resources;

use Override;
use Exception;
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
use App\Jobs\DnaMatching;
use App\Models\Dna;
use App\Services\DnaImportService;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DnaResource extends Resource
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
        return auth()->user()->canUploadDna();
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
                    ->multiple() // Enable multiple file uploads
                    ->maxSize(100000)
                    ->directory('dna-form-imports')
                    ->visibility('private')
                    ->acceptedFileTypes(['text/plain', 'text/csv', 'application/zip'])
                    ->afterStateUpdated(function ($state, $set, $livewire) {
                        if ($state === null) {
                            return;
                        }
                        
                        $allowed = null;
                        // If premium features are enabled, allow all users to upload
                        if (config('premium.enabled')) {
                            $allowed = true;
                        }
                        $role = Auth::user()->role_id;
                        $user_id = Auth::user()->id;
                        $dna = Dna::where('user_id', '=', $user_id)->count();
                        
                        if ($allowed !== true && in_array($role, [1, 2, 9, 10])) {
                            $allowed = true;
                        }
                        if ($allowed !== true && in_array($role, [4, 5, 6]) && $dna < 1) {
                            $allowed = true;
                        }
                        if ($allowed !== true && in_array($role, [7, 8]) && $dna < 5) {
                            $allowed = true;
                        }
                        
                        if ($allowed === true) {
                            try {
                                $currentUser = Auth::user();
                                $importService = app(DnaImportService::class);
                                
                                // Handle multiple files
                                $files = is_array($state) ? $state : [$state];
                                $successCount = 0;
                                $errors = [];
                                
                                foreach ($files as $file) {
                                    try {
                                        $fileName = $file->store('dna-form-imports', 'private');
                                        
                                        // Use import service for validation and creation
                                        $result = $importService->importSingleKit(
                                            $fileName,
                                            $currentUser->id,
                                            true // auto-match
                                        );
                                        
                                        $successCount++;
                                    } catch (Exception $e) {
                                        $errors[] = "Failed to import " . $file->getClientOriginalName() . ": " . $e->getMessage();
                                    }
                                }
                                
                                if ($successCount > 0) {
                                    $message = "Successfully imported {$successCount} DNA kit(s)";
                                    if (!empty($errors)) {
                                        $message .= " with " . count($errors) . " error(s)";
                                    }
                                    
                                    return [
                                        'message'  => __($message),
                                        'redirect' => 'dna.index',
                                    ];
                                } else {
                                    throw new Exception("All imports failed: " . implode(", ", $errors));
                                }
                            } catch (Exception $e) {
                                return $e->getMessage();
                            }
                        } else {
                            return "You have reached your DNA kit upload limit for your role.";
                        }
                    }),
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
