{{-- Home Page Layout --}}
@extends('layouts.app')

@section('content')
    {{-- Corrected component references based on the actual component blade files present in the application --}}
    <x-header></x-header>
    <x-hero-section></x-hero-section>
    <x-about-us></x-about-us>
    <x-services></x-services>
    <x-contact-form></x-contact-form>
    @include('components.footer')
        <div class="flex justify-between items-center p-4 bg-gray-100">
            <span class="text-gray-600 text-sm">&copy; 2023 Genealogy Laravel. All rights reserved.</span>
            <a href="https://wa.me/+447706007407" class="text-gray-600 text-sm" target="_blank">Contact us on WhatsApp</a>
        </div>
    </x-footer>
    <style>
        /* Add responsive design CSS here */
    </style>
@endsection
