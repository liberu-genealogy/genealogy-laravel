{{-- Moved to `resources/views/components/contact-form.blade.php` and renamed to match Laravel component naming convention --}}
@extends('layouts.home')
@section('content')
    <div class="container flex justify-center">
        <div class="w-full sm:max-w-xl px-6 mb-5 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <h1>Contact Us</h1>
            <x-contact-form />
        </div>
    </div>
@endsection
