@extends('layouts.app')


@component('components.x-home-header')
@component('components.x-home-navbar')
 
    <main>
        @include('components.manage_section')
@yield('content')
    </main>

@component('components.x-contact-form')
@include('components.footer')
