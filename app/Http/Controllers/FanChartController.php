<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FanChartController extends Controller
{
    public function show(Request $request): View
    {
        $personId = $request->integer('person_id');
        $person = $personId ? Person::find($personId) : null;

        return view('fan-chart', compact('person'));
    }
}
