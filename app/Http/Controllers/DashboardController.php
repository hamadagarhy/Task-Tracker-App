<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();
        $tomorrowEnd = now()->addDay()->endOfDay();
        $sevenDaysAgoStart = now()->subDays(6)->startOfDay();

        $overdueCount = $user->tasks()
            ->whereNull('completed_at')
            ->where('task_date', '<', $todayStart)
            ->count();

        $completedTodayCount = $user->tasks()
            ->whereNotNull('completed_at')
            ->whereBetween('completed_at', [$todayStart, $todayEnd])
            ->count();

        $totalTodayCount = $user->tasks()
            ->whereBetween('task_date', [$todayStart, $todayEnd])
            ->count();

        $completedLast7DaysCount = $user->tasks()
            ->whereNotNull('completed_at')
            ->where('completed_at', '>=', $sevenDaysAgoStart)
            ->count();

        $pendingTasksCount = $user->tasks()
            ->whereNull('completed_at')
            ->count();

        $overdueTasks = $user->tasks()
            ->with('category')
            ->whereNull('completed_at')
            ->where('task_date', '<', $todayStart)
            ->orderBy('task_date', 'desc')
            ->limit(6)
            ->get();

        $todayTasks = $user->tasks()
            ->with('category')
            ->whereBetween('task_date', [$todayStart, $todayEnd])
            ->orderByRaw('completed_at is not null') // incomplete first
            ->orderBy('task_date')
            ->limit(10)
            ->get();

        $upcomingTasks = $user->tasks()
            ->with('category')
            ->whereNull('completed_at')
            ->whereBetween('task_date', [$todayStart, $tomorrowEnd])
            ->orderBy('task_date')
            ->limit(8)
            ->get();

        return view('dashboard', [
            'stats' => [
                'todayTotal' => $totalTodayCount,
                'todayCompleted' => $completedTodayCount,
                'todayCompletionPercent' => $totalTodayCount > 0
                    ? (int) round(($completedTodayCount / $totalTodayCount) * 100)
                    : 0,
                'overdue' => $overdueCount,
                'pending' => $pendingTasksCount,
                'completedLast7Days' => $completedLast7DaysCount,
            ],
            'overdueTasks' => TaskResource::collection($overdueTasks)->resolve(),
            'todayTasks' => TaskResource::collection($todayTasks)->resolve(),
            'upcomingTasks' => TaskResource::collection($upcomingTasks)->resolve(),
        ]);
    }
}
