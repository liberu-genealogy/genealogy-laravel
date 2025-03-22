<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Rule;

final class EditProfile extends Component
{
    #[Rule('required|string|max:255')]
    public string $name;

    #[Rule('required|email|max:255')]
    public string $email;

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user?->name;
        $this->email = $user?->email;
    }

    public function updateProfile(): void
    {
        $validated = $this->validate();

        try {
            Auth::user()?->update($validated);
            $this->dispatch('profile-updated');
        } catch (\Throwable $e) {
            $this->dispatch('profile-update-failed', message: $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.edit-profile');
    }
}