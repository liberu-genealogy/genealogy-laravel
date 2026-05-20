<?php

namespace App\Modules\Events\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EventsController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Events index']);
    }

    public function create()
    {
        return response()->json(['message' => 'Create event form']);
    }

    public function store(Request $request)
    {
        return response()->json(['message' => 'Event stored']);
    }

    public function show($event)
    {
        return response()->json(['message' => 'Show event', 'event' => $event]);
    }

    public function edit($event)
    {
        return response()->json(['message' => 'Edit event form', 'event' => $event]);
    }

    public function update(Request $request, $event)
    {
        return response()->json(['message' => 'Event updated', 'event' => $event]);
    }

    public function destroy($event)
    {
        return response()->json(['message' => 'Event deleted', 'event' => $event]);
    }

    public function byType($type)
    {
        return response()->json(['message' => 'Events by type', 'type' => $type]);
    }

    public function timeline()
    {
        return response()->json(['message' => 'Events timeline view']);
    }

    public function calendar()
    {
        return response()->json(['message' => 'Events calendar view']);
    }

    public function search($query)
    {
        return response()->json(['message' => 'Events search results', 'query' => $query]);
    }
}
