<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;

class ApiTokensPage extends Page
{
    protected static string $view = 'filament.pages.api.api-tokens';

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationGroup = 'Account';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'API Tokens';

    public User $user;

    public function mount(): void
    {
        $this->user = Auth::user();
    }

    public function createApiToken(string $name, array $permissions): void
    {
        $this->user->createToken($name, $permissions);
    }

    public function deleteApiToken(string $name): void
    {
        $this->user->tokens()->where('name', $name)->first()->delete();
    }

    protected function getHeading(): string
    {
        return static::$title;
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return config('filament-jetstream.show_api_token_page');
    }
}
