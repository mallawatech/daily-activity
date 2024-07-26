<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-blue-600">
        <div class="bg-white shadow-lg rounded-lg flex w-full max-w-md overflow-hidden my-8"> <!-- Added vertical margin -->
            <!-- Right Side Form -->
            <div class="w-full p-6"> <!-- Reduced padding -->
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('img-logo/Logo.png') }}" alt="Logo" class="landing-image" width="150px" height="150px">
                </div>

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-4">
                        <x-label for="name" value="{{ __('Name') }}" class="block text-sm font-medium text-gray-700" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                    </div>

                    <div class="mb-4">
                        <x-label for="email" value="{{ __('Email') }}" class="block text-sm font-medium text-gray-700" />
                        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                    </div>

                    <div class="mb-4">
                        <x-label for="password" value="{{ __('Password') }}" class="block text-sm font-medium text-gray-700" />
                        <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                    </div>

                    <div class="mb-4">
                        <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" class="block text-sm font-medium text-gray-700" />
                        <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                    </div>

                    <div class="flex items-center justify-between">
                        <x-button class="ml-4 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            {{ __('Register') }}
                        </x-button>
                    </div>

                    <div class="mt-6 text-center">
                        <p class="text-gray-700">Already have an account? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Log in</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
