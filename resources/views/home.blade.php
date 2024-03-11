{{-- Home Page Layout --}}
@extends('layouts.app')

@section('content')
    {{-- Corrected component references based on the actual component blade files present in the application --}}
    <x-header></x-header>
    <x-hero-section></x-hero-section>
    <x-about-us></x-about-us>
    <x-services></x-services>
    <x-contact-form></x-contact-form>
    <x-footer></x-footer>
@endsection
