@extends('layouts.app')


@include('components.home-navbar')
 
    <div class="mt-8">
        @yield('content')
        @include('components.manage_section')
    </div>

@include('components.contact-form')
@include('components.footer')
