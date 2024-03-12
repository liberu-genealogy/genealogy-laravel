<?php

/**
 * Handles the display of the fan chart.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FanChartController extends Controller
{
    public function show()
    {
        return \Livewire::render(FanChartComponent::class);
    }
}
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return \Livewire::render(FanChartComponent::class);
    }
}
