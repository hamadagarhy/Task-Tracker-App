@php
    $f = $filters ?? [];
    $selectClass = 'block w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm';
@endphp

<x-app-layout title="Tasks">
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Tasks') }}
            </h2>
            <div class="flex flex-wrap items-center gap-3">
                <a
                    href="{{ route('recurring-tasks.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                >
                    {{ __('Recurring Tasks') }}
                </a>
                <a
                    href="{{ route('tasks.create') }}"
                    class="inline-flex shrink-0 items-center justify-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                >
                    {{ __('New Task') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-auth-session-status class="mb-4" :status="session('success')" />

                    {{-- Filters --}}
                    <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-6 mb-6">
                        <form method="GET" action="{{ route('tasks.index') }}" class="space-y-4" data-ajax-form data-ajax-auto-submit>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                                <div>
                                    <x-input-label for="filter_status" :value="__('Status')" />
                                    <select id="filter_status" name="status" class="{{ $selectClass }}">
                                        <option value="">{{ __('All') }}</option>
                                        <option value="incomplete" @selected(($f['status'] ?? '') === 'incomplete')>{{ __('Incomplete') }}</option>
                                        <option value="completed" @selected(($f['status'] ?? '') === 'completed')>{{ __('Completed') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="filter_category_id" :value="__('Category')" />
                                    <select id="filter_category_id" name="category_id" class="{{ $selectClass }}">
                                        <option value="">{{ __('All categories') }}</option>
                                        @foreach ($categories as $categoryId => $category)
                                            <option value="{{ $categoryId }}" @selected((string) ($f['category_id'] ?? '') === (string) $categoryId)>
                                                {{ $category }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="filter_from" :value="__('From Date')" />
                                    <x-text-input
                                        id="filter_from"
                                        name="from"
                                        type="date"
                                        class="block mt-1 w-full"
                                        :value="$f['from'] ?? ''"
                                    />
                                </div>
                                <div>
                                    <x-input-label for="filter_to" :value="__('To Date')" />
                                    <x-text-input
                                        id="filter_to"
                                        name="to"
                                        type="date"
                                        class="block mt-1 w-full"
                                        :value="$f['to'] ?? ''"
                                    />
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-3">
                                <x-primary-button type="submit">
                                    {{ __('Filter') }}
                                </x-primary-button>
                                <a
                                    href="{{ route('tasks.index') }}"
                                    data-ajax-link
                                    data-ajax-reset-form
                                    class="inline-flex items-center px-4 py-2 bg-transparent border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                >
                                    {{ __('Clear') }}
                                </a>
                            </div>
                        </form>
                    </div>

                    <div data-ajax-scope>
                        <div data-ajax-target>
                            @include('tasks._list')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
