<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;

class WhyUsSection extends Component
{
    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.why-us-section');
    }
}
