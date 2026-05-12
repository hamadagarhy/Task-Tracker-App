@php
    $selectClass = 'block w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm';
    $config = $recurringTask['frequency_config'] ?? [];
@endphp

<x-app-layout title="Edit Recurring Task">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Recurring Task') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <x-auth-session-status class="mb-4" :status="session('success')" />

                    <form
                        method="POST"
                        action="{{ route('recurring-tasks.update', ['recurring_task' => $recurringTask['id']]) }}"
                        class="space-y-6"
                        x-data="{
                            frequency: '{{ old('frequency', $recurringTask['frequency'] ?? 'daily') }}'
                        }"
                        data-prevent-double-submit
                    >
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input
                                id="title"
                                name="title"
                                class="block mt-1 w-full"
                                :value="old('title', $recurringTask['title'])"
                                required
                                autofocus
                                autocomplete="off"
                            />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="3" class="{{ $selectClass }}">{{ old('description', $recurringTask['description']) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select id="category_id" name="category_id" class="{{ $selectClass }}">
                                <option value="">{{ __('None') }}</option>
                                @foreach ($categories as $categoryId => $category)
                                    <option value="{{ $categoryId }}" @selected(old('category_id', $recurringTask['category']['id'] ?? null) == $categoryId)>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="frequency" :value="__('Frequency')" />
                            <select id="frequency" name="frequency" class="{{ $selectClass }}" x-model="frequency">
                                <option value="daily">{{ __('Daily') }}</option>
                                <option value="weekdays">{{ __('Weekdays') }}</option>
                                <option value="weekly">{{ __('Weekly') }}</option>
                                <option value="monthly">{{ __('Monthly') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('frequency')" class="mt-2" />
                        </div>

                        <div x-show="frequency === 'weekly'" x-cloak>
                            <x-input-label :value="__('Days of week')" />
                            <div class="mt-2 grid grid-cols-2 sm:grid-cols-4 gap-3">
                                @php($days = ['mon' => 'Mon', 'tue' => 'Tue', 'wed' => 'Wed', 'thu' => 'Thu', 'fri' => 'Fri', 'sat' => 'Sat', 'sun' => 'Sun'])
                                @foreach ($days as $value => $label)
                                    <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                        <input
                                            type="checkbox"
                                            name="days[]"
                                            value="{{ $value }}"
                                            class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                            @checked(in_array($value, old('days', $config['days'] ?? [])))
                                        />
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('days')" class="mt-2" />
                        </div>

                        <div x-show="frequency === 'monthly'" x-cloak>
                            <x-input-label for="day_of_month" :value="__('Day of month (1-31)')" />
                            <x-text-input
                                id="day_of_month"
                                name="day_of_month"
                                type="number"
                                min="1"
                                max="31"
                                class="block mt-1 w-full"
                                :value="old('day_of_month', $config['day_of_month'] ?? null)"
                            />
                            <x-input-error :messages="$errors->get('day_of_month')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="start_date" :value="__('Start date (optional)')" />
                                <x-text-input
                                    id="start_date"
                                    name="start_date"
                                    type="date"
                                    class="block mt-1 w-full"
                                    :value="old('start_date', $recurringTask['start_date'])"
                                />
                                <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="end_date" :value="__('End date (optional)')" />
                                <x-text-input
                                    id="end_date"
                                    name="end_date"
                                    type="date"
                                    class="block mt-1 w-full"
                                    :value="old('end_date', $recurringTask['end_date'])"
                                />
                                <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <x-primary-button>
                                {{ __('Update') }}
                            </x-primary-button>
                            <a
                                href="{{ route('recurring-tasks.index') }}"
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

