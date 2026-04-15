@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-teal-50 to-pink-50 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <a href="{{ route('landing') }}" class="inline-flex items-center space-x-2 mb-4">
                <svg class="w-10 h-10 text-teal-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 21.593c-5.63-5.539-11-10.297-11-14.402 0-3.791 3.068-5.191 5.281-5.191 1.312 0 4.151.501 5.719 4.457 1.59-3.968 4.464-4.447 5.726-4.447 2.54 0 5.274 1.621 5.274 5.181 0 4.069-5.136 8.625-11 14.402z"/>
                </svg>
                <span class="text-3xl font-bold text-teal-700">MamaCare</span>
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Welcome back</h2>
            <p class="text-gray-500 mt-1">Sign in to continue your care journey</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition @error('email') border-red-400 @enderror"
                           placeholder="you@example.com">
                    @error('email')
                        <p class="mt-1.5 text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition @error('password') border-red-400 @enderror"
                           placeholder="••••••••">
                    @error('password')
                        <p class="mt-1.5 text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-teal-600">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <button type="submit"
                        class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-3 rounded-xl transition shadow-sm">
                    Sign In
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-500 text-sm">Don't have an account?
                    <a href="{{ route('register') }}" class="text-teal-600 hover:text-teal-700 font-medium">Register as Patient</a>
                </p>
            </div>
        </div>

        <!-- Demo credentials hint -->
        <div class="mt-6 bg-teal-50 border border-teal-200 rounded-xl p-4 text-sm">
            <p class="font-semibold text-teal-700 mb-2">Demo Accounts:</p>
            <p class="text-teal-600">Admin: admin@antenatal.com / password</p>
            <p class="text-teal-600">Doctor: doctor@antenatal.com / password</p>
        </div>

        <div class="text-center mt-6">
            <a href="{{ route('landing') }}" class="text-gray-400 hover:text-teal-600 text-sm transition">← Back to Home</a>
        </div>
    </div>
</div>
@endsection
