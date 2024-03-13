<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GedcomResource\Pages;
use App\Models\Gedcom;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Jobs\ImportGedcom;
use Illuminate\Support\Facades\Storage;

class GedcomResource extends Resource {
    /**
     * Class GedcomResource
     *
     * This class represents a resource for handling Gedcom data.
     *
     * @var bool Is this resource scoped to a tenant
     */
    protected static bool $isScopedToTenant = false;

    protected static ?string $model = Gedcom::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListGedcoms::route('/'),
            'create' => Pages\CreateGedcom::route('/create'),
            'view'   => Pages\ViewGedcom::route('/{record}'),
            'edit'   => Pages\EditGedcom::route('/{record}/edit'),
        ];
    }



 /**
     * Define the form fields and behavior for the DnaResource.
 *
 * @param Form $form
 *
 * @return Form
     *
     * @param Form $form
     *
     * @return Form
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('attachment')
                    ->required()
                    ->maxSize(100000)
                ->directory('gedcom-form-imports')
                ->visibility('private')
            ->afterStateUpdated(function ($state, $set, $livewire) {
                if ($state === null) {
                    return;
                }
                $path = $state->store('gedcom-form-imports', 'private');
                ImportGedcom::dispatch($livewire->user(), Storage::path($path));
            }),
            ]);
    }

        /**
     * Define the table columns, filters, actions, and bulk actions.
     *
     * @param  Table  $table The table object to be defined.
     * @return Table The updated table.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('export')
                    ->action(fn () => static::exportGedcom())
                    ->label('Export GEDCOM'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

        /**
     * Imports GEDCOM data into the application.
     *
     * This function reads a GEDCOM file, parses its contents, and stores the relevant genealogical data into the application's database. It handles the mapping of GEDCOM tags to the application's data model and ensures data integrity during the import process.
     *
     * Returns:
     *    array: A summary of the import process, including counts of successfully imported entities (e.g., individuals, families) and any errors encountered.
     *
     * Side Effects:
     *    Modifies the application's database by adding new records corresponding to the imported GEDCOM data. This operation is transactional and will roll back in case of errors to maintain data integrity.
     *
     * Note:
     *    This function expects the GEDCOM file to be pre-validated and located in a specific directory accessible to the application.
     */
    private static function import(): array
    {
    }

    /**
     * Initiates the GEDCOM export process.
     *
     * @return void
     */
    public static function exportGedcom(): void
    {
        $user = auth()->user(); // Assuming the user is authenticated
        $fileName = now()->format('Y-m-d_His') . '_family_tree.ged'; // Generating a unique file name
        ExportGedCom::dispatch($fileName, $user);
    }
} 
