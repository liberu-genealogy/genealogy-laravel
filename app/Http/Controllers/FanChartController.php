<?php

namespace App\Http\Controllers;

class FanChartController extends Controller
{
    public function show()
    {
        return \Livewire::render(FanChartComponent::class);
    }
}
