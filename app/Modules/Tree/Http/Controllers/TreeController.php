<?php

namespace App\Modules\Tree\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TreeController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Tree index']);
    }

    public function person($person)
    {
        return response()->json(['message' => 'Tree for person', 'person' => $person]);
    }

    public function pedigree($person)
    {
        return response()->json(['message' => 'Pedigree tree', 'person' => $person]);
    }

    public function descendants($person)
    {
        return response()->json(['message' => 'Descendants tree', 'person' => $person]);
    }

    public function interactive($person)
    {
        return response()->json(['message' => 'Interactive tree', 'person' => $person]);
    }

    public function exportPdf($person)
    {
        return response()->json(['message' => 'Export tree PDF', 'person' => $person]);
    }

    public function exportSvg($person)
    {
        return response()->json(['message' => 'Export tree SVG', 'person' => $person]);
    }

    public function exportPng($person)
    {
        return response()->json(['message' => 'Export tree PNG', 'person' => $person]);
    }
}
