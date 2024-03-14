/**
 * This file contains the default layout for the website.
 * It includes the app layout, header component, content section, and footer component.
 */

@extends('layouts.app')

@include('components.header')

    @yield('content')

@include('components.footer')
