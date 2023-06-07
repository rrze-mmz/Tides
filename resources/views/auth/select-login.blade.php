<x-guest-layout>
    <div x-data="setup()" x-init="$refs.loading.classList.add('hidden'); setColors(color);" :class="{ 'dark': isDark}">
        <div class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0">
            <div class="w-full max-w-sm rounded-md bg-white px-4 py-6 space-y-6 dark:bg-darker">
                <h1 class="text-center text-xl font-semibold">Login</h1>
                @if(!is_null($saml2TenantUUID))
                    <a
                        href="{{route('saml.login', $saml2TenantUUID)}}"
                        class="flex items-center justify-center rounded-md bg-black px-4 py-2 text-white transition-all duration-200 space-x-2 hover:bg-opacity-80 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-1 dark:focus:ring-offset-darker"
                    >
                        <span> Login with WebSSO </span>
                    </a>

                    <!-- Or -->
                    <div class="flex flex-nowrap items-center justify-center space-x-2">
                        <span class="h-px w-20 bg-gray-300"></span>
                        <span>OR</span>
                        <span class="h-px w-20 bg-gray-300"></span>
                    </div>
                @endif
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    <input
                        class="w-full rounded-md border px-4 py-2 focus:ring-primary-100 focus:outline-none focus:ring dark:bg-darker dark:border-gray-700 dark:focus:ring-primary-darker"
                        type="username"
                        name="username"
                        placeholder="Username"
                        required
                    />
                    <input
                        class="w-full rounded-md border px-4 py-2 focus:ring-primary-100 focus:outline-none focus:ring dark:bg-darker dark:border-gray-700 dark:focus:ring-primary-darker"
                        type="password"
                        name="password"
                        placeholder="Password"
                        required
                    />
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                               name="remember">
                        <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                    <div>
                        <button
                            type="submit"
                            class="w-full px-4 py-2 font-medium text-white transition-colors duration-200
                            rounded-md bg-blue-900 hover:bg-blue-500
                            focus:outline-none focus:ring-2 focus:ring-primary
                            focus:ring-offset-1 dark:focus:ring-offset-darker"
                        >
                            Login
                        </button>
                    </div>

                </form>
            </div>
        </div>
        <div class="fixed bottom-5 left-5">
            <button
                aria-hidden="true"
                @click="toggleTheme"
                class="rounded-full p-2 shadow-md transition-colors duration-200 bg-primary hover:bg-primary-darker focus:ring-primary focus:outline-none focus:ring"
            >
                <svg
                    x-show="isDark"
                    class="h-8 w-8 text-white"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                    />
                </svg>
                <svg
                    x-show="!isDark"
                    class="h-8 w-8 text-white"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
                    />
                </svg>
            </button>
        </div>
    </div>
</x-guest-layout>
