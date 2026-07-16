<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class WhyUsSection extends Component
{
    public function render(): Factory|View
    {
        return view('livewire.why-us-section');
    }
}
