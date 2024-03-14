@extends('layouts.app')

@section('content')

@component('components.x-home-header')
@component('components.x-home-navbar')
 
    <main>
        @include('components.manage_section')
    </main>

@component('components.x-contact-form')
@include('components.footer')
@endsection
