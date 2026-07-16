<?php

namespace App\Filament\App\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Override;

class UpdatePasswordPage extends Page
{
    use InteractsWithForms;

    #[Override]
    protected string $view = 'filament.pages.profile.update-password';

    #[Override]
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-lock-closed';

    #[Override]
    protected static ?string $navigationLabel = 'Update Password';

    #[Override]
    protected static string|\UnitEnum|null $navigationGroup = '👤 Account & Settings';

    #[Override]
    protected static ?int $navigationSort = 1;

    #[Override]
    protected static ?string $title = 'Update Password';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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
        return true; // config('filament-jetstream.show_update_password_page');
    }
}
