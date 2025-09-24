<?php

namespace App\Filament\App\Pages;

use Override;
use UnitEnum;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordPage extends Page
{
    protected string $view = 'filament.pages.profile.update-password';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-lock-closed';

    protected static string | \UnitEnum | null $navigationGroup = 'Account';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Update Password';

    public $current_password;

    public $new_password;

    public $new_password_confirmation;

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('current_password')
                ->label('Current Password')
                ->password()
                ->rules(['required', 'current_password'])
                ->required(),
            TextInput::make('new_password')
                ->label('New Password')
                ->password()
                ->rules(['required', Password::defaults(), 'confirmed'])
                ->required(),
            TextInput::make('new_password_confirmation')
                ->label('Confirm Password')
                ->password()
                ->rules(['required'])
                ->required(),
        ];
    }

    public function submit(): void
    {
        $this->form->getState();

        $state = array_filter([
            'password' => Hash::make($this->new_password),
        ]);

        $user = Auth::user();

        $user->forceFill($state)->save();

        session()->flash('status', 'Your password has been updated.');
    }

    #[Override]
    public function getHeading(): string
    {
        return static::$title;
    }

    #[Override]
    public static function shouldRegisterNavigation(): bool
    {
        return true; //config('filament-jetstream.show_update_password_page');
    }
}
