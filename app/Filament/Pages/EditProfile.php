<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;

class EditProfile extends Page
{
    protected static string $view = 'filament.pages.edit-profile';

    public User $user;

    public $name = '';
    public $email = '';

    public function mount()
    {
        $this->user = Filament::auth()->user();
        $this->form->fill([
            'name' => $this->user->name,
            'email' => $this->user->email,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label('Name')
                ->required()
                ->maxLength(255),
            TextInput::make('email')
                ->label('Email Address')
                ->required()
                ->maxLength(255),
        ];
    }

    public function submit()
    {
        $this->validate();

        $this->user->forceFill([
            'name' => $this->name,
            'email' => $this->email,
        ])->save();

        Filament::notify('success', 'Your profile has been updated.');
    }

    public function getBreadcrumbs(): array
    {
        return [
            url()->current() => 'Edit Profile',
        ];
    }
}
