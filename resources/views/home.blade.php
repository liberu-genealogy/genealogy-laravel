@extends('layouts.home')

@section('title', 'Welcome to Our Genealogy Platform')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-4xl font-bold text-center my-8">Discover Your Ancestry</h1>
    <p class="text-lg text-gray-600 text-center mb-6">Explore your family's history and connect with your ancestors.</p>
    @livewire('search-ancestors')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
        <div class="info-card">
            <h2 class="text-2xl font-semibold">Start Your Journey</h2>
            <p>Use our tools to build your family tree and uncover your past.</p>
            <a href="{{ route('family-tree') }}" class="btn btn-primary mt-4">Build Your Tree</a>
        </div>
        <div class="info-card">
            <h2 class="text-2xl font-semibold">Community Stories</h2>
            <p>Read stories from our community members and share your own.</p>
            @livewire('community-stories')
        </div>
    </div>
</div>
@endsection

@section('footer')
<div class="footer bg-gray-100 text-center p-4">
    <p>&copy; {{ date('Y') }} Genealogy Platform. All rights reserved.</p>
</div>
@endsection
