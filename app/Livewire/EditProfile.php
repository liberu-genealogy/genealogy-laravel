<?php

declare(strict_types=1);

namespace App\Livewire;

use Throwable;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Rule;

final class EditProfile extends Component
{
    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('required|email|max:255')]
    public string $email = '';

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user?->name ?? '';
        $this->email = $user?->email ?? '';
    }

    public function updateProfile(): void
    {
        $validated = $this->validate();

        try {
            Auth::user()?->update($validated);
            $this->dispatch('profile-updated');
            session()->flash('message', 'Profile updated successfully!');
        } catch (Throwable $e) {
            $this->dispatch('profile-update-failed', message: $e->getMessage());
            session()->flash('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    public function render(): View
    {
        return view('livewire.edit-profile');
    }
}