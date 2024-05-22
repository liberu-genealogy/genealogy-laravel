<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Http\Livewire\ApiTokenManager;

class ApiTokens extends ApiTokenManager
{
    public function mount()
    {
        parent::mount();
        $this->user = Auth::user();
    }

    public function render()
    {
        return view('livewire.api-tokens');
    }
}