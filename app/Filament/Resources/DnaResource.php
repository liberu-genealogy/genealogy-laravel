<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DnaResource\Pages;
use App\Jobs\DnaMatching;
use App\Jobs\ImportGedcom;
use App\Models\Dna;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DnaResource extends Resource
{
    protected static bool $isScopedToTenant = false;

    protected static ?string $model = Dna::class;

    protected static ?string $navigationLabel = 'DNA';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Dna Matching';

    public static function form(Form $form): Form
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

                                $var_name = 'var_' . $random_string;
                                $file_name = $state->store('dna-form-imports', 'private');
                                $filename = Storage::disk('private')->path($file_name);
                                $user_id = $currentUser->id;

                                $dna = new Dna();
                                $dna->name = 'DNA Kit for user ' . $user_id;
                                $dna->user_id = $user_id;
                                $dna->variable_name = $var_name;
                                $dna->file_name = $file_name;

                                $dna->save();
                                DnaMatching::dispatch($currentUser, $var_name, $file_name);

                                return [
                                    'message' => __('The dna was successfully created'),
                                    'redirect' => 'dna.edit',
                                    'param' => ['dna' => $dna->id],
                                ];
                            } catch (\Exception $e) {
                                return $e->getMessage();
                            }

                            return response()->json(['Not uploaded'], 422);
                        }
                    }),
            ]);
    }

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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

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
