<?php

namespace App\Filament\App\Resources;

use UnitEnum;
use BackedEnum;
use App\Filament\App\Resources\DnaResource\Pages;
use App\Jobs\DnaMatching;
use App\Models\Dna;
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

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-beaker';

    protected static string | UnitEnum | null $navigationGroup = 'DNA Analysis';

    protected static ?int $navigationSort = 1;

    public static function canCreate(): bool
    {
        return auth()->user()->canUploadDna();
    }

    #[\Override]
    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                FileUpload::make('attachment')
                    ->required()
                    ->maxSize(100000)
                    ->directory('dna-form-imports')
                    ->visibility('private')
                    ->afterStateUpdated(function ($state, $set, $livewire) {
                        if ($state === null) {
                            return;
                        }
                        $allowed = null;
                        $role = Auth::user()->role_id;
                        $user_id = Auth::user()->id;
                        $dna = Dna::where('user_id', '=', $user_id)->count();
                        if (in_array($role, [1, 2, 9, 10])) {
                            $allowed = true;
                        }
                        if (in_array($role, [4, 5, 6]) && $dna < 1) {
                            $allowed = true;
                        }

                        if (in_array($role, [7, 8]) && $dna < 5) {
                            $allowed = true;
                        }
                        if ($allowed === true) {
                            try {
                                $currentUser = Auth::user();

                                $random_string = Str::random(5);
                                while (Dna::where('name', $random_string)->exists()) {
                                    $random_string = Str::random(5);
                                }

                                $var_name = 'var_'.$random_string;
                                $file_name = $state->store('dna-form-imports', 'private');
                                $filename = Storage::disk('private')->path($file_name);
                                $user_id = $currentUser->id;

                                $dna = new Dna();
                                $dna->name = 'DNA Kit for user '.$user_id;
                                $dna->user_id = $user_id;
                                $dna->variable_name = $var_name;
                                $dna->file_name = $file_name;

                                $dna->save();
                                DnaMatching::dispatch($currentUser, $var_name, $file_name);

                                return [
                                    'message'  => __('The dna was successfully created'),
                                    'redirect' => 'dna.edit',
                                    'param'    => ['dna' => $dna->id],
                                ];
                            } catch (\Exception $e) {
                                return $e->getMessage();
                            }
                        }
                    }),
            ]);
    }

    #[\Override]
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('variable_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('file_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    #[\Override]
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDnas::route('/'),
            'create' => Pages\CreateDna::route('/create'),
            'edit'   => Pages\EditDna::route('/{record}/edit'),
        ];
    }

    public static function visibility(): bool
    {
        return true; // Set to true to make the resource visible in the sidebar
    }
}
