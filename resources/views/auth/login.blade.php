<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-purple-600">
        <div class="bg-white shadow-lg rounded-lg flex w-3/4 max-w-4xl overflow-hidden">
            <!-- Left Side Image -->
            <div class="hidden md:block w-1/2 bg-cover bg-center" style="background-image: {{ asset('img-logo/login.jpg') }};">
                <!-- Add your SVG or image here -->
            </div>

            <!-- Right Side Form -->
            <div class="w-full md:w-1/2 p-8">
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('img-logo/Logo.png') }}" alt="Logo" class="landing-image" width="150px" height="150px">
                </div>

                <x-validation-errors class="mb-4" />

                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <x-label for="email" value="{{ __('Email') }}" class="block text-sm font-medium text-gray-700" />
                        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    </div>

                    <div class="mb-4">
                        <x-label for="password" value="{{ __('Password') }}" class="block text-sm font-medium text-gray-700" />
                        <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                    </div>

                    <div class="block mb-4">
                        <label for="remember_me" class="flex items-center">
                            <x-checkbox id="remember_me" name="remember" />
                            <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-between">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif

                        <x-button class="ml-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Log in') }}
                        </x-button>
                    </div>

                    <div class="mt-6 text-center">
                        <p class="text-gray-700">Don't have an account? <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Sign up</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
