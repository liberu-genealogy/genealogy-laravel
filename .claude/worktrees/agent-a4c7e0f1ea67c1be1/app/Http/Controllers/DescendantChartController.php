<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;

class DescendantChartController extends Controller
{
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        // Since the Livewire component handles data fetching and processing, we don't need to fetch data here.
        // Just return the view that includes the Livewire component.
        return view('filament.pages.descendant-chart');
    }
}
