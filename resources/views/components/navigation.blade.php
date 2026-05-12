<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="route('tasks.index')" :active="request()->routeIs('tasks.*')">
                        <span class="inline-flex items-center gap-2">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M4.5 2A1.5 1.5 0 003 3.5v13A1.5 1.5 0 004.5 18h11a1.5 1.5 0 001.5-1.5V7.621a1.5 1.5 0 00-.44-1.06l-4.12-4.122A1.5 1.5 0 0010.378 2H4.5zM7 8.25a.75.75 0 01.75-.75h4.5a.75.75 0 010 1.5h-4.5A.75.75 0 017 8.25zm.75 2.25a.75.75 0 000 1.5h4.5a.75.75 0 000-1.5h-4.5zm.75 3a.75.75 0 000 1.5h2.25a.75.75 0 000-1.5H8.5z" clip-rule="evenodd" />
                            </svg>
                            <span>{{ __('Tasks') }}</span>
                        </span>
                    </x-nav-link>

                    <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                        <span class="inline-flex items-center gap-2">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M6.5 2.5A2.5 2.5 0 0 1 9 0h2a2.5 2.5 0 0 1 2.5 2.5V4h.75A2.25 2.25 0 0 1 16.5 6.25v11.5A2.25 2.25 0 0 1 14.25 20h-8.5A2.25 2.25 0 0 1 3.5 17.75V6.25A2.25 2.25 0 0 1 5.75 4H6.5v-1.5Zm2.5-.5a.5.5 0 0 0-.5.5V4h2V2.5a.5.5 0 0 0-.5-.5h-1Z" />
                                <path d="M6.5 8.5a.75.75 0 0 1 .75-.75h5.5a.75.75 0 0 1 0 1.5H7.25A.75.75 0 0 1 6.5 8.5ZM6.5 12a.75.75 0 0 1 .75-.75h5.5a.75.75 0 0 1 0 1.5H7.25A.75.75 0 0 1 6.5 12Z" />
                            </svg>
                            <span>{{ __('Categories') }}</span>
                        </span>
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
{{--                        <x-dropdown-link :href="route('profile.edit')">--}}
{{--                            {{ __('Profile') }}--}}
{{--                        </x-dropdown-link>--}}

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('tasks.index')" :active="request()->routeIs('tasks.*')">
                <span class="inline-flex items-center gap-2">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M4.5 2A1.5 1.5 0 003 3.5v13A1.5 1.5 0 004.5 18h11a1.5 1.5 0 001.5-1.5V7.621a1.5 1.5 0 00-.44-1.06l-4.12-4.122A1.5 1.5 0 0010.378 2H4.5zM7 8.25a.75.75 0 01.75-.75h4.5a.75.75 0 010 1.5h-4.5A.75.75 0 017 8.25zm.75 2.25a.75.75 0 000 1.5h4.5a.75.75 0 000-1.5h-4.5zm.75 3a.75.75 0 000 1.5h2.25a.75.75 0 000-1.5H8.5z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ __('Tasks') }}</span>
                </span>
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')">
                <span class="inline-flex items-center gap-2">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M6.5 2.5A2.5 2.5 0 0 1 9 0h2a2.5 2.5 0 0 1 2.5 2.5V4h.75A2.25 2.25 0 0 1 16.5 6.25v11.5A2.25 2.25 0 0 1 14.25 20h-8.5A2.25 2.25 0 0 1 3.5 17.75V6.25A2.25 2.25 0 0 1 5.75 4H6.5v-1.5Zm2.5-.5a.5.5 0 0 0-.5.5V4h2V2.5a.5.5 0 0 0-.5-.5h-1Z" />
                        <path d="M6.5 8.5a.75.75 0 0 1 .75-.75h5.5a.75.75 0 0 1 0 1.5H7.25A.75.75 0 0 1 6.5 8.5ZM6.5 12a.75.75 0 0 1 .75-.75h5.5a.75.75 0 0 1 0 1.5H7.25A.75.75 0 0 1 6.5 12Z" />
                    </svg>
                    <span>{{ __('Categories') }}</span>
                </span>
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
{{--                <x-responsive-nav-link :href="route('profile.edit')">--}}
{{--                    {{ __('Profile') }}--}}
{{--                </x-responsive-nav-link>--}}

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                                           onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
