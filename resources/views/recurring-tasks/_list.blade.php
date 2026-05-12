@if (count($recurringTasks))
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead>
                <tr>
                    <th class="text-left text-sm font-semibold text-gray-600 dark:text-gray-300 px-2 py-3">
                        {{ __('Title') }}
                    </th>
                    <th class="text-left text-sm font-semibold text-gray-600 dark:text-gray-300 px-2 py-3">
                        {{ __('Category') }}
                    </th>
                    <th class="text-left text-sm font-semibold text-gray-600 dark:text-gray-300 px-2 py-3">
                        {{ __('Frequency') }}
                    </th>
                    <th class="text-left text-sm font-semibold text-gray-600 dark:text-gray-300 px-2 py-3">
                        {{ __('Start') }}
                    </th>
                    <th class="text-left text-sm font-semibold text-gray-600 dark:text-gray-300 px-2 py-3">
                        {{ __('End') }}
                    </th>
                    <th class="text-right text-sm font-semibold text-gray-600 dark:text-gray-300 px-2 py-3">
                        {{ __('Actions') }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($recurringTasks as $template)
                    <tr data-recurring-task-row>
                        <td class="px-2 py-2 align-top">
                            <div class="font-medium text-gray-900 dark:text-gray-100">
                                {{ $template['title'] }}
                            </div>
                            @if ($template['description'])
                                <div class="mt-1 text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                    {{ $template['description'] }}
                                </div>
                            @endif
                        </td>
                        <td class="px-2 py-2 align-top text-sm text-gray-600 dark:text-gray-300">
                            {{ $template['category']['name'] ?? '—' }}
                        </td>
                        <td class="px-2 py-2 align-top text-sm text-gray-600 dark:text-gray-300">
                            {{ ucfirst($template['frequency']) }}
                        </td>
                        <td class="px-2 py-2 align-top text-sm text-gray-600 dark:text-gray-300">
                            {{ $template['start_date'] ?? '—' }}
                        </td>
                        <td class="px-2 py-2 align-top text-sm text-gray-600 dark:text-gray-300">
                            {{ $template['end_date'] ?? '—' }}
                        </td>
                        <td class="px-2 py-2 text-right align-top">
                            <div class="inline-flex items-center gap-2">
                                <a
                                    href="{{ route('recurring-tasks.edit', ['recurring_task' => $template['id']]) }}"
                                    class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-indigo-600 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/50 hover:bg-indigo-100 dark:hover:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                >
                                    {{ __('Edit') }}
                                </a>
                                <form
                                    method="POST"
                                    action="{{ route('recurring-tasks.destroy', ['recurring_task' => $template['id']]) }}"
                                    data-ajax-delete
                                    data-confirm="{{ __('Delete this template?') }}"
                                >
                                    @method('DELETE')
                                    @csrf
                                    <button
                                        type="submit"
                                        class="cursor-pointer inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-red-700 dark:text-red-300 bg-red-50 dark:bg-red-900/40 hover:bg-red-100 dark:hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                    >
                                        {{ __('Delete') }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-center">
        {{ $links() }}
    </div>
@else
    <div class="text-center py-10">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ __('No recurring task templates yet.') }}
        </p>
    </div>
@endif

