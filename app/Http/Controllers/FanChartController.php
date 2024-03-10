<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FanChartController extends Controller
{
    public function show()
    {
        return livewire('fan-chart');
    }
}
