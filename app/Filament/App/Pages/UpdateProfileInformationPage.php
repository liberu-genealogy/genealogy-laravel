<?php

namespace App\Filament\App\Pages;

use Override;
use UnitEnum;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class UpdateProfileInformationPage extends Page
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.profile.update-profile-information';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user';

    protected static string | \UnitEnum | null $navigationGroup = '👤 Account & Settings';

    protected static ?int $navigationSort = 0;

    protected static ?string $title = 'Profile';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'name'  => Auth::user()->name,
            'email' => Auth::user()->email,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email Address')
                    ->required(),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $state = array_filter($this->form->getState());

        $user = Auth::user();

        $user->forceFill($state)->save();

        session()->flash('status', 'Your profile has been updated.');
    }

    #[Override]
    public function getHeading(): string
    {
        return static::$title;
    }

    #[Override]
    public static function shouldRegisterNavigation(): bool
    {
        return true; //config('filament-jetstream.show_update_profile_information_page');
    }
}
