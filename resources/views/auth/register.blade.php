@extends('layouts.home')

@section('content')
    <div class="min-h-[calc(100vh-8rem)] flex flex-col sm:justify-center items-center py-12 px-4 bg-gradient-to-br from-emerald-50 via-white to-blue-50">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900">Create your account</h1>
            <p class="mt-2 text-gray-600">Start building your family tree for free</p>
        </div>

        <div class="w-full sm:max-w-md bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
            <div class="px-8 py-8">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="space-y-5">
                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1.5" for="name">
                                {{ __('Full name') }}
                            </label>
                            <input class="w-full border border-gray-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 rounded-lg px-4 py-2.5 text-gray-900 bg-white transition-colors placeholder-gray-400 text-sm"
                                   id="name" type="text" name="name"
                                   value="{{ old('name') }}" required autofocus
                                   placeholder="Jane Smith">
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1.5" for="email">
                                {{ __('Email address') }}
                            </label>
                            <input class="w-full border border-gray-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 rounded-lg px-4 py-2.5 text-gray-900 bg-white transition-colors placeholder-gray-400 text-sm"
                                   id="email" type="email" name="email"
                                   value="{{ old('email') }}" required
                                   placeholder="you@example.com">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1.5" for="password">
                                {{ __('Password') }}
                            </label>
                            <input class="w-full border border-gray-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 rounded-lg px-4 py-2.5 text-gray-900 bg-white transition-colors text-sm"
                                   id="password" type="password" name="password" required
                                   autocomplete="new-password"
                                   placeholder="Min. 8 characters">
                            @error('password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1.5" for="password_confirmation">
                                {{ __('Confirm password') }}
                            </label>
                            <input class="w-full border border-gray-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 rounded-lg px-4 py-2.5 text-gray-900 bg-white transition-colors text-sm"
                                   id="password_confirmation" type="password" name="password_confirmation" required
                                   placeholder="Re-enter your password">
                        </div>

                        <button type="submit"
                                class="w-full flex justify-center items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-sm hover:shadow transition-all duration-200 text-sm">
                            {{ __('Create account') }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-medium text-emerald-600 hover:text-emerald-700">
                        Sign in
                    </a>
                </p>
            </div>
        </div>

        <p class="mt-4 text-xs text-gray-500 text-center max-w-sm">
            By creating an account, you agree to our
            <a href="/terms-and-conditions" class="underline hover:text-gray-700">Terms of Service</a>
            and
            <a href="/privacy" class="underline hover:text-gray-700">Privacy Policy</a>.
        </p>
    </div>
@endsection