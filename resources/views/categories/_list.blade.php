@if (count($categories))
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead>
                <tr>
                    <th scope="col" class="text-left text-sm font-semibold text-gray-600 dark:text-gray-300 px-2 py-3 w-10">
                        <span class="sr-only">{{ __('ICON') }}</span>
                    </th>
                    <th scope="col" class="text-left text-sm font-semibold text-gray-600 dark:text-gray-300 px-2 py-3">
                        {{ __('NAME') }}
                    </th>
                    <th scope="col" class="text-left text-sm font-semibold text-gray-600 dark:text-gray-300 px-2 py-3">
                        {{ __('CREATED AT') }}
                    </th>
                    <th scope="col" class="text-left text-sm font-semibold text-gray-600 dark:text-gray-300 px-2 py-3">
                        {{ __('UPDATED AT') }}
                    </th>
                    <th scope="col" class="text-right text-sm font-semibold text-gray-600 dark:text-gray-300 px-2 py-3">
                        {{ __('ACTIONS') }}
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($categories as $category)
                    <tr data-category-row>
                        <td class="px-2 py-2 align-top">
                            <div class="flex items-start justify-start">
                                <svg
                                    class="h-5 w-5 text-indigo-600 dark:text-indigo-300"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                    aria-hidden="true"
                                >
                                    <path d="M6.5 2.5A2.5 2.5 0 0 1 9 0h2a2.5 2.5 0 0 1 2.5 2.5V4h.75A2.25 2.25 0 0 1 16.5 6.25v11.5A2.25 2.25 0 0 1 14.25 20h-8.5A2.25 2.25 0 0 1 3.5 17.75V6.25A2.25 2.25 0 0 1 5.75 4H6.5v-1.5Zm2.5-.5a.5.5 0 0 0-.5.5V4h2V2.5a.5.5 0 0 0-.5-.5h-1Z" />
                                    <path d="M6.5 8.5a.75.75 0 0 1 .75-.75h5.5a.75.75 0 0 1 0 1.5H7.25A.75.75 0 0 1 6.5 8.5ZM6.5 12a.75.75 0 0 1 .75-.75h5.5a.75.75 0 0 1 0 1.5H7.25A.75.75 0 0 1 6.5 12Z" />
                                </svg>
                            </div>
                        </td>

                        <td class="px-2 py-2 align-top">
                            <div class="font-medium text-gray-900 dark:text-gray-100">
                                {{ $category['name'] }}
                            </div>
                        </td>

                        <td class="px-2 py-2 align-top">
                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                {{ $category['created_at'] }}
                            </div>
                        </td>

                        <td class="px-2 py-2 align-top">
                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                {{ $category['updated_at'] }}
                            </div>
                        </td>

                        <td class="px-2 py-2 text-right align-top">
                            <div class="inline-flex items-center gap-2">
                                <a
                                    href="{{ route('categories.edit', ['category'=>$category['id']]) }}"
                                    class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-indigo-600 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/50 hover:bg-indigo-100 dark:hover:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                >
                                    {{ __('Edit') }}
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('categories.destroy', ['category'=>$category['id']]) }}"
                                    data-ajax-delete
                                    data-confirm="{{ __('Delete this category?') }}"
                                >
                                    @csrf
                                    @method('DELETE')

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

    <div class="mt-6">
        <div class="flex items-center justify-center">
            {{ $links() }}
        </div>
    </div>
@else
    <div class="text-center py-10">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ __('No categories yet.') }}
        </p>
    </div>
@endif

