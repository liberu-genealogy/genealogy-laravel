@extends('layouts.home')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-emerald-50 via-white to-blue-50 py-20 overflow-hidden">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23059669" fill-opacity="0.05"%3E%3Ccircle cx="30" cy="30" r="4"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <div class="mb-8">
                <div class="inline-flex items-center px-4 py-2 bg-emerald-100 text-emerald-800 rounded-full text-sm font-medium mb-6">
                    🌳 Discover Your Heritage
                </div>
                <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                    Build Your
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-blue-600">
                        Family Tree
                    </span>
                </h1>
                <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto leading-relaxed">
                    Uncover your family's story with powerful genealogy tools. Connect generations, preserve memories, and discover your roots with our comprehensive family history platform.
                </p>
            </div>

            @auth
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ route('filament.app.tenant') }}"
                       class="inline-flex items-center px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Go to Dashboard
                    </a>
                    <a href="{{ route('filament.app.tenant') }}/subscription"
                       class="inline-flex items-center px-6 py-4 bg-white hover:bg-gray-50 text-emerald-600 font-semibold rounded-lg border-2 border-emerald-200 hover:border-emerald-300 transition-all duration-200">
                        ✨ Explore Premium Features
                    </a>
                </div>
            @else
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Start Free Today
                    </a>
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center px-6 py-4 bg-white hover:bg-gray-50 text-emerald-600 font-semibold rounded-lg border-2 border-emerald-200 hover:border-emerald-300 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Sign In
                    </a>
                </div>

                <p class="text-sm text-gray-500 mt-4">
                    Free forever • No credit card required • 7-day premium trial
                </p>
            @endauth
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Everything You Need for Family Research
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Powerful tools and features to help you discover, document, and share your family history
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 p-8 rounded-2xl hover:shadow-lg transition-shadow duration-300">
                <div class="w-12 h-12 bg-emerald-600 rounded-lg flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Family Tree Builder</h3>
                <p class="text-gray-600">Create beautiful, interactive family trees with unlimited people and generations. Add photos, documents, and stories.</p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-8 rounded-2xl hover:shadow-lg transition-shadow duration-300">
                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Interactive Charts</h3>
                <p class="text-gray-600">Visualize your family history with pedigree charts, descendant trees, and fan charts. Export and share with family.</p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-8 rounded-2xl hover:shadow-lg transition-shadow duration-300">
                <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">DNA Analysis</h3>
                <p class="text-gray-600">Upload and analyze DNA results to find genetic matches and discover new family connections across multiple platforms.</p>
            </div>

            <!-- Feature 4 -->
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-8 rounded-2xl hover:shadow-lg transition-shadow duration-300">
                <div class="w-12 h-12 bg-orange-600 rounded-lg flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Media & Documents</h3>
                <p class="text-gray-600">Store and organize family photos, documents, certificates, and stories. Create a digital family archive.</p>
            </div>

            <!-- Feature 5 -->
            <div class="bg-gradient-to-br from-teal-50 to-teal-100 p-8 rounded-2xl hover:shadow-lg transition-shadow duration-300">
                <div class="w-12 h-12 bg-teal-600 rounded-lg flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Research Tools</h3>
                <p class="text-gray-600">Advanced search capabilities, source citations, and research tracking to help you discover your family's story.</p>
            </div>

            <!-- Feature 6 -->
            <div class="bg-gradient-to-br from-rose-50 to-rose-100 p-8 rounded-2xl hover:shadow-lg transition-shadow duration-300">
                <div class="w-12 h-12 bg-rose-600 rounded-lg flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Share & Collaborate</h3>
                <p class="text-gray-600">Share your family tree with relatives, collaborate on research, and export data in standard GEDCOM format.</p>
            </div>
        </div>
    </div>
</section>

<!-- Premium Features Section -->
<section class="py-20 bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-400 to-orange-400 text-white rounded-full text-sm font-medium mb-6">
                ✨ Premium Features
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Unlock Advanced Genealogy Tools
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Take your family research to the next level with premium features designed for serious genealogists
            </p>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="grid md:grid-cols-2 gap-8 p-8">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-6">Premium Benefits</h3>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Unlimited DNA Uploads</h4>
                                    <p class="text-gray-600 text-sm">Upload DNA results from multiple testing companies</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Smart Duplicate Checker</h4>
                                    <p class="text-gray-600 text-sm">Automatically find and merge duplicate entries</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Smart Matching</h4>
                                    <p class="text-gray-600 text-sm">Find potential matches across public family trees</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Premium Badge</h4>
                                    <p class="text-gray-600 text-sm">Show your commitment to family research</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-emerald-50 to-blue-50 p-6 rounded-xl">
                        <div class="text-center">
                            <div class="text-4xl font-bold text-gray-900 mb-2">£4.99</div>
                            <div class="text-gray-600 mb-6">per month</div>
                            <div class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-sm font-medium mb-6">
                                7-day free trial
                            </div>
                            @guest
                                <a href="{{ route('register') }}"
                                   class="block w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                                    Start Free Trial
                                </a>
                                <p class="text-xs text-gray-500 mt-3">No credit card required</p>
                            @else
                                <a href="{{ route('filament.app.tenant') }}/subscription"
                                   class="block w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                                    Upgrade to Premium
                                </a>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-emerald-600">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
            Start Your Family History Journey Today
        </h2>
        <p class="text-xl text-emerald-100 mb-8 max-w-2xl mx-auto">
            Join thousands of genealogy enthusiasts who trust Liberu Genealogy to preserve their family stories
        </p>
        @guest
            <a href="{{ route('register') }}"
               class="inline-flex items-center px-8 py-4 bg-white hover:bg-gray-100 text-emerald-600 font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Get Started Free
            </a>
        @else
            <a href="{{ route('filament.app.tenant') }}"
               class="inline-flex items-center px-8 py-4 bg-white hover:bg-gray-100 text-emerald-600 font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Continue Building Your Tree
            </a>
        @endguest
    </div>
</section>
@endsection
