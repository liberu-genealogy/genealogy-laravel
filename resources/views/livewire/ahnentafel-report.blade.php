resources/views/livewire/ahnentafel-report.blade.php:

@extends('layouts.app')

@section('content')
    <div>
        <h1>Ahnentafel Report</h1>
        <ul>
            @foreach($reportData as $person)
                <li>
                    <span>Number: {{ $person['number'] }}</span><br>
                    <span>Name: {{ $person['name'] }}</span><br>
                    <span>Birth: {{ $person['birth'] }}</span><br>
                    <span>Death: {{ $person['death'] }}</span><br>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
