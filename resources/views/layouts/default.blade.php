@extends('layouts.app')

@section('content')
    @yield('content')
@endsection
@include('components.header')
@include('components.footer')

<section>
    @yield('content')
</section>
