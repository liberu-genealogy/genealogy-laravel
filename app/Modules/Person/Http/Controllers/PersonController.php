<?php

namespace App\Modules\Person\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PersonController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Persons index']);
    }

    public function create()
    {
        return response()->json(['message' => 'Create person form']);
    }

    public function store(Request $request)
    {
        return response()->json(['message' => 'Person stored']);
    }

    public function show($person)
    {
        return response()->json(['message' => 'Show person', 'person' => $person]);
    }

    public function edit($person)
    {
        return response()->json(['message' => 'Edit person form', 'person' => $person]);
    }

    public function update(Request $request, $person)
    {
        return response()->json(['message' => 'Person updated', 'person' => $person]);
    }

    public function destroy($person)
    {
        return response()->json(['message' => 'Person deleted', 'person' => $person]);
    }

    public function timeline($person)
    {
        return response()->json(['message' => 'Person timeline', 'person' => $person]);
    }

    public function tree($person)
    {
        return response()->json(['message' => 'Person tree view', 'person' => $person]);
    }

    public function addEvent(Request $request, $person)
    {
        return response()->json(['message' => 'Event added to person', 'person' => $person]);
    }
}
