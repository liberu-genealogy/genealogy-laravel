<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditProfile extends Component
{
    public $name;
    public $email;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updateProfile()
    {
        $this->validate();

        try {
            Auth::user()->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);

            $this->emit('profileUpdated');
        } catch (\Exception $e) {
            $this->emit('profileUpdateFailed', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.edit-profile');
    }
}