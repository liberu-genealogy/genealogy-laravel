<?php

namespace App\Modules\Places\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PlacesController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Places index']);
    }

    public function create()
    {
        return response()->json(['message' => 'Create place form']);
    }

    public function store(Request $request)
    {
        return response()->json(['message' => 'Place stored']);
    }

    public function show($place)
    {
        return response()->json(['message' => 'Show place', 'place' => $place]);
    }

    public function edit($place)
    {
        return response()->json(['message' => 'Edit place form', 'place' => $place]);
    }

    public function update(Request $request, $place)
    {
        return response()->json(['message' => 'Place updated', 'place' => $place]);
    }

    public function destroy($place)
    {
        return response()->json(['message' => 'Place deleted', 'place' => $place]);
    }

    public function search($query)
    {
        return response()->json(['message' => 'Places search', 'query' => $query]);
    }

    public function byCountry($country)
    {
        return response()->json(['message' => 'Places by country', 'country' => $country]);
    }

    public function geocode(Request $request, $place)
    {
        return response()->json(['message' => 'Geocode place', 'place' => $place]);
    }

    public function mapView()
    {
        return response()->json(['message' => 'Places map view']);
    }
}
