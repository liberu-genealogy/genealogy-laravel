@extends('layouts.home')

@section('content')
@php
    $trialDays = config('subscription.premium.trial_days', 14);
    $price = config('subscription.premium.price', '$2.99');
    $interval = config('subscription.premium.interval', 'month');
@endphp
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-purple-50 via-white to-pink-50 py-20 overflow-hidden">
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center mb-16">
            <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-400 to-orange-400 text-white rounded-full text-sm font-medium mb-6">
                ✨ Premium Features
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                Upgrade to Premium
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Unlock powerful genealogy tools and unlimited features. Start with a {{ $trialDays }}-day free trial — no credit card required.
            </p>
        </div>

        <!-- Pricing Cards -->
        <div class="max-w-4xl mx-auto grid md:grid-cols-2 gap-8 mb-16">
            <!-- Standard Plan -->
            <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-1">Standard</h2>
                    <p class="text-gray-500 mb-4">Free forever</p>
                    <div class="text-4xl font-bold text-gray-900">$0</div>
                </div>

                <ul class="space-y-4">
                    <li class="flex items-center">
                        <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-gray-700">Basic family tree</span>
                    </li>
                    <li class="flex items-center">
                        <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-gray-700">Standard charts</span>
                    </li>
                    <li class="flex items-center">
                        <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-gray-700">1 DNA kit upload</span>
                    </li>
                    <li class="flex items-center">
                        <div class="w-5 h-5 bg-red-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-gray-400 line-through">Premium badge</span>
                    </li>
                    <li class="flex items-center">
                        <div class="w-5 h-5 bg-red-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-gray-400 line-through">Duplicate checker</span>
                    </li>
                    <li class="flex items-center">
                        <div class="w-5 h-5 bg-red-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-3 h-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-gray-400 line-through">Smart matching</span>
                    </li>
                </ul>

                <div class="mt-8">
                    <a href="{{ route('register') }}"
                       class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-lg transition-colors duration-200">
                        Get Started Free
                    </a>
                </div>
            </div>

            <!-- Premium Plan -->
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl border-2 border-purple-300 p-8 relative shadow-lg">
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                    <span class="bg-gradient-to-r from-purple-500 to-pink-500 text-white px-4 py-1 rounded-full text-sm font-semibold">
                        Most Popular
                    </span>
                </div>

                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-1">Premium</h2>
                    <p class="text-gray-500 mb-4">{{ $trialDays }}-day free trial</p>
                    <div class="text-4xl font-bold text-gray-900">{{ $price }}</div>
                    <p class="text-sm text-gray-500">per {{ $interval }}</p>
                    <div class="mt-3 bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium inline-block">
                        🎉 {{ $trialDays }}-Day Free Trial
                    </div>
                </div>

                <ul class="space-y-4">
                    <li class="flex items-center">
                        <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-gray-700">Everything in Standard</span>
                    </li>
                    <li class="flex items-center">
                        <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-gray-700">Unlimited DNA uploads</span>
                    </li>
                    <li class="flex items-center">
                        <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-gray-700">Smart duplicate checker</span>
                    </li>
                    <li class="flex items-center">
                        <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-gray-700">Smart matching across trees</span>
                    </li>
                    <li class="flex items-center">
                        <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-gray-700">Premium badge</span>
                    </li>
                    <li class="flex items-center">
                        <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                            <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="text-gray-700">Priority support</span>
                    </li>
                </ul>

                <div class="mt-8">
                    @auth
                        <a href="{{ url('/app/subscription') }}"
                           class="block w-full text-center bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                            Upgrade to Premium
                        </a>
                    @else
                        <a href="{{ route('register') }}"
                           class="block w-full text-center bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                            Start Free Trial
                        </a>
                        <p class="text-xs text-gray-500 mt-2 text-center">No credit card required</p>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Detail Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Everything Premium Includes</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Take your genealogy research to the next level with advanced tools designed for serious family historians
            </p>
        </div>

        <div class="max-w-5xl mx-auto grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Unlimited DNA Uploads</h3>
                <p class="text-gray-600 text-sm">Upload DNA results from any testing company — AncestryDNA, 23andMe, MyHeritage, and more.</p>
            </div>

            <div class="text-center p-6">
                <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Smart Duplicate Checker</h3>
                <p class="text-gray-600 text-sm">Automatically detect and merge duplicate person entries, keeping your family tree clean and accurate.</p>
            </div>

            <div class="text-center p-6">
                <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Smart Matching</h3>
                <p class="text-gray-600 text-sm">Find potential matches and relatives across public family trees to expand your research.</p>
            </div>

            <div class="text-center p-6">
                <div class="w-14 h-14 bg-yellow-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Premium Badge</h3>
                <p class="text-gray-600 text-sm">Display your premium status and show your commitment to serious genealogy research.</p>
            </div>

            <div class="text-center p-6">
                <div class="w-14 h-14 bg-rose-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Priority Support</h3>
                <p class="text-gray-600 text-sm">Get faster responses and dedicated assistance from our genealogy research support team.</p>
            </div>

            <div class="text-center p-6">
                <div class="w-14 h-14 bg-indigo-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Advanced Media Storage</h3>
                <p class="text-gray-600 text-sm">Store more photos, documents, and media files to build a richer family archive.</p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Frequently Asked Questions</h2>

            <div class="space-y-6">
                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">What happens during the free trial?</h3>
                    <p class="text-gray-600">
                        You get full access to all premium features for {{ $trialDays }} days. No payment information is required upfront — just sign up and start exploring.
                    </p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Can I cancel anytime?</h3>
                    <p class="text-gray-600">
                        Yes, you can cancel your subscription at any time. You'll continue to have access to premium features until the end of your billing period with no hidden fees.
                    </p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">What payment methods do you accept?</h3>
                    <p class="text-gray-600">
                        We accept all major credit and debit cards through Stripe's secure payment processing. Your payment information is never stored on our servers.
                    </p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">What happens to my data if I downgrade?</h3>
                    <p class="text-gray-600">
                        Your family tree data is always safe. If you downgrade to the free plan, you'll retain all your existing data but will lose access to premium-only features until you re-subscribe.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-purple-600 to-pink-600">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
            Ready to Unlock Premium?
        </h2>
        <p class="text-xl text-purple-100 mb-8 max-w-2xl mx-auto">
            Start your {{ $trialDays }}-day free trial today and discover what premium genealogy research can do for you.
        </p>
        @auth
            <a href="{{ url('/app/subscription') }}"
               class="inline-flex items-center px-8 py-4 bg-white hover:bg-gray-100 text-purple-600 font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Upgrade Now
            </a>
        @else
            <a href="{{ route('register') }}"
               class="inline-flex items-center px-8 py-4 bg-white hover:bg-gray-100 text-purple-600 font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Start Free Trial
            </a>
            <p class="text-purple-200 text-sm mt-3">No credit card required • Cancel anytime</p>
        @endauth
    </div>
</section>
@endsection
