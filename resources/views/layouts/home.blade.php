@extends('layouts.app')


@include('components.home-header')
@include('components.home-navbar')
 
    <main>
        @include('components.manage_section')
@yield('content')
    </main>

@include('components.contact-form')
@include('components.footer')
