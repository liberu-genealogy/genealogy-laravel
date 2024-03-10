Gh<?php

<?php

/**
 * This file is part of the Filament package.
 *
 * (c) Your Name <your-email@example.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

class GedcomResource extends Resource
{
    /**
     * This class represents a resource for handling Gedcom data.
     */
    /**
     * Class GedcomResource
     *
     * This class represents a resource for handling Gedcom data.
     *
     * @var bool Is this resource scoped to a tenant
     */
{
    protected static bool $isScopedToTenant = false;

    protected static ?string $model = Gedcom::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

        /**
     * Define the form fields for the resource.
     *
     * @param  Form  $form
     * @return Form
     */
    
    /**
     * Define the form fields for the resource.
     *
     * @param  Form  $form The form object to be defined.
     * @return Form The updated form.
     */
    public static function form(Form $form): Form
    {
        /**
         * Define the form for creating a new Gedcom.
         *
         * @param  Form  $form The form to be defined.
         * @return Form The updated form.
         */
    
    /**
     * Get the pages defined for the resource.
     *
     * @return array The defined pages.
     */
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
     * Get the pages defined for the resource.
     *
     * @return array The defined pages.
     */
    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListGedcoms::route('/'),
            'create' => Pages\CreateGedcom::route('/create'),
            'view'   => Pages\ViewGedcom::route('/{record}'),
            'edit'   => Pages\EditGedcom::route('/{record}/edit'),
        ];
    }
    {
        /**
         * Define the schema for uploading a Gedcom.
         *
         * @param  Form  $form The form to be defined.
         * @return mixed The result of the schema definition.
         */
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static     /**
     * Get the relations defined for the resource.
     *
     * @return array The defined relations.
     */
        /**
     * Get the relations defined for the resource.
     *
     * @return array The defined relations.
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

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
     * Perform the import functionality.
     *
     * @return array
     */
    private static function import(): array
    {
    }
}
