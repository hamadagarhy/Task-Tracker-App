<x-app-layout title="Dashboard">
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
                <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ now()->translatedFormat('l, F j, Y') }}
                </div>
            </div>

            <a
                href="{{ route('tasks.create') }}"
                class="inline-flex shrink-0 items-center justify-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-white/10 rounded-md font-semibold text-xs text-gray-900 dark:text-gray-100 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-white/15 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
            >
                {{ __('New Task') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        @php
            $todayTotal = (int) ($stats['todayTotal'] ?? 0);
            $todayCompleted = (int) ($stats['todayCompleted'] ?? 0);
            $todayPercent = (int) ($stats['todayCompletionPercent'] ?? 0);
            $overdueCount = (int) ($stats['overdue'] ?? 0);
            $pendingCount = (int) ($stats['pending'] ?? 0);
            $completed7d = (int) ($stats['completedLast7Days'] ?? 0);
            $hasOverdue = !empty($overdueTasks);
        @endphp

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-white ring-1 ring-gray-200 shadow-sm p-6 sm:p-8 space-y-6 dark:bg-slate-900/20 dark:ring-white/5 dark:shadow-[0_20px_60px_-30px_rgba(0,0,0,0.8)]">
            {{-- Stat cards --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                {{-- Tasks Today --}}
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-5 shadow-sm dark:border-white/10 dark:bg-slate-800/40">
                    <div class="flex items-center gap-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-50 text-blue-600 ring-1 ring-blue-200 dark:bg-blue-500/15 dark:text-blue-300 dark:ring-blue-500/20">
                                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10m-13 9h16a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z" />
                                </svg>
                            </div>
                        <div class="min-w-0">
                            <div class="text-xs font-medium text-gray-600 dark:text-gray-300/80">{{ __('Tasks Today') }}</div>
                            <div class="mt-1 flex items-baseline gap-2">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white" data-stat-today-ratio>{{ $todayCompleted }}/{{ $todayTotal }}</div>
                            </div>
                            <div class="mt-1 text-[11px] text-gray-500 dark:text-gray-300/60">
                                {{ $todayTotal > 0 ? ($todayPercent . '% ' . __('completed')) : __('No tasks today') }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Overdue --}}
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-5 shadow-sm dark:border-white/10 dark:bg-slate-800/40">
                    <div class="flex items-center gap-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-50 text-red-600 ring-1 ring-red-200 dark:bg-red-500/15 dark:text-red-300 dark:ring-red-500/20">
                                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v5l3 3m6-4a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                        <div class="min-w-0">
                            <div class="text-xs font-medium text-gray-600 dark:text-gray-300/80">{{ __('Overdue') }}</div>
                            <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white" data-stat-overdue>{{ $overdueCount }}</div>
                            <div class="mt-1 text-[11px] text-gray-500 dark:text-gray-300/60">{{ __('Needs attention') }}</div>
                        </div>
                    </div>
                </div>

                {{-- Total Pending --}}
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-5 shadow-sm dark:border-white/10 dark:bg-slate-800/40">
                    <div class="flex items-center gap-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-50 text-amber-700 ring-1 ring-amber-200 dark:bg-amber-500/15 dark:text-amber-300 dark:ring-amber-500/20">
                                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h10v10H7z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 3h10M7 21h10" />
                                </svg>
                            </div>
                        <div class="min-w-0">
                            <div class="text-xs font-medium text-gray-600 dark:text-gray-300/80">{{ __('Total Pending') }}</div>
                            <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white" data-stat-pending>{{ $pendingCount }}</div>
                            <div class="mt-1 text-[11px] text-gray-500 dark:text-gray-300/60">{{ __('Incomplete tasks') }}</div>
                        </div>
                    </div>
                </div>

                {{-- Completed (7 days) --}}
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-5 shadow-sm dark:border-white/10 dark:bg-slate-800/40">
                    <div class="flex items-center gap-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200 dark:bg-emerald-500/15 dark:text-emerald-300 dark:ring-emerald-500/20">
                                <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7 10 17l-5-5" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M22 12a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z" />
                                </svg>
                            </div>
                        <div class="min-w-0">
                            <div class="text-xs font-medium text-gray-600 dark:text-gray-300/80">{{ __('Completed (7 days)') }}</div>
                            <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white" data-stat-completed-7d>{{ $completed7d }}</div>
                            <div class="mt-1 text-[11px] text-gray-500 dark:text-gray-300/60">{{ __('Last 7 days') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Panels --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                {{-- Overdue Tasks --}}
                <section class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden dark:border-white/10 dark:bg-slate-800/40" data-task-section="overdue">
                    <div class="flex items-center justify-between px-6 py-4">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('Overdue Tasks') }}</h3>
                        <span class="text-xs text-gray-500 dark:text-gray-400" data-section-count>{{ count($overdueTasks) }}</span>
                    </div>
                    <div class="border-t border-gray-200 dark:border-white/10">
                        <div class="relative px-6 py-5">
                            <div class="absolute left-0 top-0 h-full w-1 bg-red-500 dark:bg-red-500/70"></div>

                            @if (empty($overdueTasks))
                                <div class="text-sm text-gray-600 dark:text-gray-300/70">
                                    {{ __('No overdue tasks. Nice work!') }}
                                </div>
                            @else
                                <div class="divide-y divide-gray-200 dark:divide-white/10">
                                    @foreach ($overdueTasks as $task)
                                        @php
                                            $dateLabel = !empty($task['task_date'])
                                                ? $task['task_date']['display']
                                                : '-';
                                        @endphp
                                        <div class="py-4 first:pt-0 last:pb-0" data-task-item>
                                            <div class="flex items-start gap-3">
                                                <button
                                                    type="button"
                                                    class="inline-block h-5 w-5 shrink-0 appearance-none rounded-full border border-gray-300 bg-transparent p-0 align-middle hover:border-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-white dark:border-white/20 dark:hover:border-white/40 dark:focus:ring-offset-slate-900"
                                                    aria-label="{{ __('Mark complete') }}"
                                                    data-task-toggle
                                                    data-context="dashboard"
                                                    data-task-id="{{ $task['id'] }}"
                                                    data-url="{{ route('tasks.complete', ['task' => $task['id']]) }}"
                                                ></button>


                                                <div class="min-w-0">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                        {{ $task['title'] }}
                                                    </div>
                                                    @if (!empty($task['description']))
                                                        <div class="mt-1 text-xs text-gray-600 dark:text-gray-300/70 line-clamp-1">
                                                            {{ $task['description'] }}
                                                        </div>
                                                    @endif
                                                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400/70">
                                                        {{ $dateLabel }}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </section>

                {{-- Upcoming Tasks --}}
                <section class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden dark:border-white/10 dark:bg-slate-800/40" data-task-section="upcoming">
                    <div class="flex items-center justify-between px-6 py-4">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('Upcoming Tasks') }}</h3>
                        <span class="text-xs text-gray-500 dark:text-gray-400" data-section-count>{{ count($upcomingTasks) }}</span>
                    </div>
                    <div class="border-t border-gray-200 dark:border-white/10 px-6 py-5">
                        @if (empty($upcomingTasks))
                            <div class="text-sm text-gray-600 dark:text-gray-300/70 text-center py-8">
                                {{ __('No upcoming tasks for today or tomorrow') }}
                            </div>
                        @else
                            <div class="divide-y divide-gray-200 dark:divide-white/10">
                                @foreach ($upcomingTasks as $task)
                                    @php
                                        $dateLabel = !empty($task['task_date'])
                                               ? $task['task_date']['display']
                                               : '-';
                                    @endphp
                                    <div class="py-4 first:pt-0 last:pb-0" data-task-item>
                                        <div class="flex items-start gap-3">
                                            <button
                                                type="button"
                                                class="inline-block h-5 w-5 shrink-0 appearance-none rounded-full border border-gray-300 bg-transparent p-0 align-middle hover:border-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-white dark:border-white/20 dark:hover:border-white/40 dark:focus:ring-offset-slate-900"
                                                aria-label="{{ __('Mark complete') }}"
                                                data-task-toggle
                                                data-context="dashboard"
                                                data-task-id="{{ $task['id'] }}"
                                                data-url="{{ route('tasks.complete', ['task' => $task['id']]) }}"
                                            ></button>

                                            <div class="min-w-0">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                    {{ $task['title'] }}
                                                </div>
                                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400/70">
                                                    {{ $dateLabel }}
                                                    @if (!empty($task['category']['name']))
                                                        <span class="mx-2 text-gray-300 dark:text-white/10">•</span>
                                                        <span class="text-gray-600 dark:text-gray-300/70">{{ $task['category']['name'] }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </section>
            </div>

            {{-- Quick Actions --}}
            <section class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden dark:border-white/10 dark:bg-slate-800/40">
                <div class="px-6 py-4">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ __('Quick Actions') }}</h3>
                </div>
                <div class="border-t border-gray-200 dark:border-white/10 px-6 py-4">
                    <div class="flex flex-wrap gap-3">
                        <a
                            href="{{ route('tasks.index') }}"
                            class="inline-flex items-center gap-2 rounded-md bg-gray-50 px-4 py-2 text-xs font-semibold text-gray-800 ring-1 ring-gray-200 hover:bg-gray-100 dark:bg-white/5 dark:text-gray-100 dark:ring-white/10 dark:hover:bg-white/10"
                        >
                            <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01" />
                            </svg>
                            {{ __('View All Tasks') }}
                        </a>
                        <a
                            href="{{ route('tasks.index', ['status' => 'incomplete']) }}"
                            class="inline-flex items-center gap-2 rounded-md bg-gray-50 px-4 py-2 text-xs font-semibold text-gray-800 ring-1 ring-gray-200 hover:bg-gray-100 dark:bg-white/5 dark:text-gray-100 dark:ring-white/10 dark:hover:bg-white/10"
                        >
                            <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            {{ __('Incomplete Tasks') }}
                        </a>
                        <a
                            href="{{ route('recurring-tasks.index') }}"
                            class="inline-flex items-center gap-2 rounded-md bg-gray-50 px-4 py-2 text-xs font-semibold text-gray-800 ring-1 ring-gray-200 hover:bg-gray-100 dark:bg-white/5 dark:text-gray-100 dark:ring-white/10 dark:hover:bg-white/10"
                        >
                            <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 6v6h-6M4 18v-6h6" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 12a8 8 0 0 0-14.83-4M4 12a8 8 0 0 0 14.83 4" />
                            </svg>
                            {{ __('Recurring Tasks') }}
                        </a>
                        <a
                            href="{{ route('categories.index') }}"
                            class="inline-flex items-center gap-2 rounded-md bg-gray-50 px-4 py-2 text-xs font-semibold text-gray-800 ring-1 ring-gray-200 hover:bg-gray-100 dark:bg-white/5 dark:text-gray-100 dark:ring-white/10 dark:hover:bg-white/10"
                        >
                            <svg viewBox="0 0 24 24" fill="none" class="h-4 w-4" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6Z" />
                            </svg>
                            {{ __('Categories') }}
                        </a>
                    </div>
                </div>
            </section>
            </div>
        </div>
    </div>
</x-app-layout>
