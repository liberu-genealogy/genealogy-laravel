<?php

namespace App\Modules\Family\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FamilyController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Families index']);
    }

    public function create()
    {
        return response()->json(['message' => 'Create family form']);
    }

    public function store(Request $request)
    {
        return response()->json(['message' => 'Family stored']);
    }

    public function show($family)
    {
        return response()->json(['message' => 'Show family', 'family' => $family]);
    }

    public function edit($family)
    {
        return response()->json(['message' => 'Edit family form', 'family' => $family]);
    }

    public function update(Request $request, $family)
    {
        return response()->json(['message' => 'Family updated', 'family' => $family]);
    }

    public function destroy($family)
    {
        return response()->json(['message' => 'Family deleted', 'family' => $family]);
    }

    public function tree($family)
    {
        return response()->json(['message' => 'Family tree view', 'family' => $family]);
    }

    public function addChild(Request $request, $family)
    {
        return response()->json(['message' => 'Child added to family', 'family' => $family]);
    }

    public function removeChild($family, $person)
    {
        return response()->json(['message' => 'Child removed from family', 'family' => $family, 'person' => $person]);
    }
}
