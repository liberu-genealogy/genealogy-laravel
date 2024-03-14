@extends('layouts.app')

@section('content')

@include('components.home-header')
@include('components.home-navbar')
 
    <main>
        @include('components.manage_section')
    </main>

@include('components.contact-form')
@include('components.footer')
@endsection
