@if (empty($tasks))
    {{-- Empty state --}}
    <div
        class="flex min-h-[240px] flex-col items-center justify-center rounded-lg border border-dashed border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900/50 px-6 py-12 text-center"
    >
        <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ __('No tasks found.') }}
        </p>
        <a
            href="{{ route('tasks.create') }}"
            class="mt-6 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
        >
            {{ __('Create your first task') }}
        </a>
    </div>
@else
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead>
                <tr>
                    <th scope="col" class="text-left text-sm font-semibold text-gray-600 dark:text-gray-300 px-2 py-3">
                        {{ __('Title') }}
                    </th>
                    <th scope="col" class="text-left text-sm font-semibold text-gray-600 dark:text-gray-300 px-2 py-3">
                        {{ __('Category') }}
                    </th>
                    <th scope="col" class="text-left text-sm font-semibold text-gray-600 dark:text-gray-300 px-2 py-3">
                        {{ __('Date') }}
                    </th>
                    <th scope="col" class="text-left text-sm font-semibold text-gray-600 dark:text-gray-300 px-2 py-3">
                        {{ __('Status') }}
                    </th>
                    <th scope="col" class="text-right text-sm font-semibold text-gray-600 dark:text-gray-300 px-2 py-3">
                        {{ __('Actions') }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($tasks as $task)
                    <tr data-task-row>
                        <td class="px-2 py-2 align-top">
                            <div class="font-medium text-gray-900 dark:text-gray-100">
                                {{ $task['title'] }}
                            </div>
                            @if ($task['description'])
                                <div class="mt-1 text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                    {{ $task['description'] }}
                                </div>
                            @endif
                        </td>
                        <td class="px-2 py-2 align-top text-sm text-gray-600 dark:text-gray-300">
                            {{ $task['category']['name'] ?? '—' }}
                        </td>
                        <td class="px-2 py-2 align-top text-sm text-gray-600 dark:text-gray-300">
                            {{ $task['task_date_display']['display'] ?? ($task['task_date']['display'] ?? '-') }}
                        </td>
                        <td class="px-2 py-2 align-top">
                            @if ($task['completed_at'])
                                <span data-task-status class="inline-flex rounded-full bg-green-100 dark:bg-green-900/40 px-2 py-0.5 text-xs font-medium text-green-800 dark:text-green-300">
                                    {{ __('Completed') }}
                                </span>
                            @else
                                <span data-task-status class="inline-flex rounded-full bg-amber-100 dark:bg-amber-900/40 px-2 py-0.5 text-xs font-medium text-amber-800 dark:text-amber-200">
                                    {{ __('Incomplete') }}
                                </span>
                            @endif
                        </td>
                        <td class="px-2 py-2 text-right align-top">
                            <div class="flex flex-col items-end gap-2">

                                <div class="flex gap-2">
                                    <a
                                        href="{{ route('tasks.edit', ['task' => $task['id']]) }}"
                                        class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-indigo-600 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/50 hover:bg-indigo-100 dark:hover:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                    >
                                        {{ __('Edit') }}
                                    </a>

                                    <button
                                        type="button"
                                        data-task-delete
                                        data-task-id="{{ $task['id'] }}"
                                        data-url="{{ route('tasks.destroy', ['task' => $task['id']]) }}"
                                        data-confirm="{{ __('Delete this task?') }}"
                                        class="cursor-pointer inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-red-700 dark:text-red-300 bg-red-50 dark:bg-red-900/40 hover:bg-red-100 dark:hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                    >
                                        {{ __('Delete') }}
                                    </button>
                                </div>

                                <button
                                    type="button"
                                    data-task-toggle
                                    data-context="tasks-index"
                                    data-task-id="{{ $task['id'] }}"
                                    data-url="{{ route('tasks.complete', ['task' => $task['id']]) }}"
                                    class="cursor-pointer inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                >
                                    {{ $task['completed_at'] ? __('Mark incomplete') : __('Mark complete') }}
                                </button>

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
@endif

