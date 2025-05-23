<?php

namespace App\Filament\Admin\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Database\Eloquent\Model;

class CreateTeam extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Create Team';
    }

    #[\Override]
    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                TextInput::make('name'),
            ]);
    }

    #[\Override]
    protected function handleRegistration(array $data): Model
    {
        return app(\App\Actions\Jetstream\CreateTeam::class)->create(auth()->user(), $data);
    }
}
