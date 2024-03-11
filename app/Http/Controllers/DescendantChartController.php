&lt;?php

namespace App\Http\Controllers;

use use App\\Models\\Person;\nIlluminate\Http\Request;
use App\Models\Person;

class DescendantChartController extends Controller
{
    public function index()
   
        return view('filament.widgets.descendant-chart', ['descendants' 
=> $descendants]);
   }

        $descendants = Person::all(); // Simplified fetch operation for example purposes
        return view('filament.widgets.descendant-chart', ['descendants' => $descendants]);
    }
}
