<?php

namespace App\Filament\App\Pages;

use Override;
use UnitEnum;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UpdatePasswordPage extends Page
{
    use InteractsWithForms;
    protected string $view = 'filament.pages.profile.update-password';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-lock-closed';

    protected static string | \UnitEnum | null $navigationGroup = '\ud83d\udc64 Account & Settings';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Update Password';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $state = array_filter([
            'password' => Hash::make($this->form->getState()['new_password'] ?? ''),
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
