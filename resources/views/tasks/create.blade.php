@php
    $selectClass = 'block w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm';
@endphp

<x-app-layout title="Create Task">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Task') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-auth-session-status class="mb-4" :status="session('success')" />

                    <form method="POST" action="{{ route('tasks.store') }}" class="space-y-6" data-prevent-double-submit>
                        @csrf

                        <div>
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input
                                id="title"
                                name="title"
                                class="block mt-1 w-full"
                                :value="old('title')"
                                required
                                autofocus
                                autocomplete="off"
                            />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea
                                id="description"
                                name="description"
                                rows="3"
                                class="{{ $selectClass }}"
                            >{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select id="category_id" name="category_id" class="{{ $selectClass }}">
                                <option value="">{{ __('None') }}</option>
                                @foreach ($categories as $categoryId => $category)
                                    <option value="{{ $categoryId }}" @selected(old('category_id') == $categoryId)>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="task_date" :value="__('Task date')" />
                            <x-text-input
                                id="task_date"
                                name="task_date"
                                type="date"
                                class="block mt-1 w-full"
                                :value="old('task_date')"
                                required
                            />
                            <x-input-error :messages="$errors->get('task_date')" class="mt-2" />
                        </div>

                        <div>
                            <a
                                href="{{ route('recurring-tasks.index') }}"
                                class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                            >
                                {{ __('Manage recurring templates') }}
                            </a>
                        </div>

                        <div class="flex items-center gap-3">
                            <x-primary-button type="submit">
                                {{ __('Create') }}
                            </x-primary-button>
                            <a
                                href="{{ route('tasks.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                            >
                                {{ __('Back') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
