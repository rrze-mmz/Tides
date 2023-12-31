<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <div class="flex justify-center">
                    <x-application-logo class="w-1/4 text-gray-500"/>
                </div>
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors"/>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- First name -->
            <div class="mb-4">
                <x-label for="first_name" value="First Name"/>

                <x-input id="first_name" class="mt-1 block w-full" type="text" name="first_name"
                         :value="old('first_name')" required
                         autofocus/>
            </div>

            <!-- Last name -->
            <div class="mb-4">
                <x-label for="last_name" value="Last Name"/>

                <x-input id="last_name" class="mt-1 block w-full" type="text" name="last_name"
                         :value="old('last_name')" required
                         autofocus/>
            </div>

            <!-- Last name -->
            <div class="mb-4">
                <x-label for="username" value="Username"/>

                <x-input id="username" class="mt-1 block w-full" type="text" name="username"
                         :value="old('username')" required
                         autofocus/>
            </div>


            <!-- Email Address -->
            <div class="mt-4">
                <x-label for="email" :value="__('Email')"/>

                <x-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required/>
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')"/>

                <x-input id="password" class="mt-1 block w-full"
                         type="password"
                         name="password"
                         required autocomplete="new-password"/>
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirm Password')"/>

                <x-input id="password_confirmation" class="mt-1 block w-full"
                         type="password"
                         name="password_confirmation" required/>
            </div>

            <div class="mt-4 flex items-center justify-end">
                <a class="text-sm text-gray-600 underline hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ml-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
