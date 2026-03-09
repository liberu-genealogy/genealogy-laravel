<?php

namespace App\Modules\Person\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PersonApiController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'API: Persons index']);
    }

    public function store(Request $request)
    {
        return response()->json(['message' => 'API: Person stored']);
    }

    public function show($person)
    {
        return response()->json(['message' => 'API: Show person', 'person' => $person]);
    }

    public function update(Request $request, $person)
    {
        return response()->json(['message' => 'API: Person updated', 'person' => $person]);
    }

    public function destroy($person)
    {
        return response()->json(['message' => 'API: Person deleted', 'person' => $person]);
    }

    public function search($query)
    {
        return response()->json(['message' => 'API: Person search', 'query' => $query]);
    }

    public function statistics()
    {
        return response()->json(['message' => 'API: Person statistics']);
    }

    public function ancestors($person)
    {
        return response()->json(['message' => 'API: Person ancestors', 'person' => $person]);
    }

    public function descendants($person)
    {
        return response()->json(['message' => 'API: Person descendants', 'person' => $person]);
    }

    public function siblings($person)
    {
        return response()->json(['message' => 'API: Person siblings', 'person' => $person]);
    }

    public function events($person)
    {
        return response()->json(['message' => 'API: Person events', 'person' => $person]);
    }

    public function addEvent(Request $request, $person)
    {
        return response()->json(['message' => 'API: Event added to person', 'person' => $person]);
    }

    public function export($person)
    {
        return response()->json(['message' => 'API: Person export', 'person' => $person]);
    }
}
