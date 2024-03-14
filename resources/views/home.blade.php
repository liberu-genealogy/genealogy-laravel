{{-- Home Page Layout --}}
@extends('layouts.app')

@section('content')
    {{-- Corrected component references based on the actual component blade files present in the application --}}
    <x-home-header></x-home-header>
    <x-hero-section></x-hero-section>
    <x-about-us></x-about-us>
    <x-services></x-services>
    <x-contact-form></x-contact-form>
    <x-home_navbar></x-home_navbar>
    <x-footer></x-footer>
@endsection
