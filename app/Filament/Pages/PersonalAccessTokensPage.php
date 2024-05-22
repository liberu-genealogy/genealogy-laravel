<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;

class PersonalAccessTokensPage extends Page
{
    protected static string $view = 'filament.pages.profile.personal-access-tokens';

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationGroup = 'Account';

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Personal Access Tokens';

    public User $user;

    public function mount(): void
    {
        $this->user = Auth::user();
    }

    public function createApiToken(string $name): void
    {
        $this->user->createToken($name);
    }

    public function deleteApiToken(string $name): void
    {
        $this->user->tokens()->where('name', $name)->first()->delete();
    }

    protected function getHeading(): string
    {
        return static::$title;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return config('filament-jetstream.show_personal_access_tokens_page');
    }
}
