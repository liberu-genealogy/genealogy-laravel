<?php

namespace App\Http\Controllers;

use Livewire;

class FanChartController extends Controller
{
    public function show()
    {
        return Livewire::render(FanChartComponent::class);
    }
}
