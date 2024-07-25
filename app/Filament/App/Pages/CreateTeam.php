<?php

namespace App\Filament\App\Pages;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CreateTeam extends RegisterTenant
{

    public function form(Form $form): Form
    {
        return $form
            ->schema([
            TextInput::make('name')
                ->label('Team Name')
                ->required()
                ->maxLength(255),
        ]);
    }

    protected function handleRegistration(array $data): Model
    {
        return app(\App\Actions\Jetstream\CreateTeam::class)->create(auth()->user(), $data);
    }

    // public function getBreadcrumbs(): array
    // {
    //     return [
    //         url()->current() => 'Create Team',
    //     ];
    // }

    public static function getLabel(): string
    {
        return 'Create Team';
    }
}
