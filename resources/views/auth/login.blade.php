@extends('layouts.home')

@section('content')
    <div class="min-h-[calc(100vh-8rem)] flex flex-col sm:justify-center items-center py-12 px-4 bg-gradient-to-br from-emerald-50 via-white to-blue-50">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900">Welcome back</h1>
            <p class="mt-2 text-gray-600">Sign in to continue building your family tree</p>
        </div>

        <div class="w-full sm:max-w-md bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
            <div class="px-8 py-8">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="space-y-5">
                        <div>
                            <label class="block font-medium text-sm text-gray-700 mb-1.5" for="email">
                                {{ __('Email address') }}
                            </label>
                            <input class="w-full border border-gray-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 rounded-lg px-4 py-2.5 text-gray-900 bg-white transition-colors placeholder-gray-400 text-sm"
                                   id="email" type="email" name="email" required autofocus
                                   placeholder="you@example.com"
                                   value="{{ old('email') }}">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block font-medium text-sm text-gray-700" for="password">
                                    {{ __('Password') }}
                                </label>
                                <a href="/forgot-password" class="text-xs text-emerald-600 hover:text-emerald-700 font-medium">
                                    Forgot password?
                                </a>
                            </div>
                            <input class="w-full border border-gray-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 rounded-lg px-4 py-2.5 text-gray-900 bg-white transition-colors text-sm"
                                   id="password" type="password" name="password" required
                                   autocomplete="current-password"
                                   placeholder="••••••••">
                            @error('password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox"
                                   class="w-4 h-4 rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                                   id="remember_me" name="remember">
                            <label for="remember_me" class="ml-2 text-sm text-gray-600">
                                {{ __('Remember me') }}
                            </label>
                        </div>

                        <button type="submit"
                                class="w-full flex justify-center items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-sm hover:shadow transition-all duration-200 text-sm">
                            {{ __('Sign in') }}
                        </button>
                    </div>
                </form>
            </div>

            <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-600">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="font-medium text-emerald-600 hover:text-emerald-700">
                        Start free today
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection