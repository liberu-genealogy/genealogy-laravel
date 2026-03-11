<?php

namespace App\Filament\App\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Override;

class CreateTeam extends RegisterTenant
{
    #[Override]
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Team Name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    #[Override]
    protected function handleRegistration(array $data): Model
    {
        $team = app(\App\Actions\Jetstream\CreateTeam::class)->create(auth()->user(), $data);

        return $team;
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
