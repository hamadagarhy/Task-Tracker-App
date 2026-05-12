<?php

namespace App\Http\Controllers;

use App\Actions\Category\GetCategories;
use App\Actions\RecurringTask\CreateRecurringTask;
use App\Actions\RecurringTask\DeleteRecurringTask;
use App\Actions\RecurringTask\ListRecurringTasks;
use App\Actions\RecurringTask\UpdateRecurringTask;
use App\Enums\TaskFrequency;
use App\Http\Requests\StoreRecurringTaskRequest;
use App\Http\Requests\UpdateRecurringTaskRequest;
use App\Models\RecurringTask;
use Illuminate\Http\Request;

class RecurringTaskController extends Controller
{
    public function __construct(
        private readonly ListRecurringTasks $listRecurringTasks,
        private readonly GetCategories $getCategories,
        private readonly CreateRecurringTask $createRecurringTask,
        private readonly UpdateRecurringTask $updateRecurringTask,
        private readonly DeleteRecurringTask $deleteRecurringTask,
    ) {
    }

    public function index(Request $request)
    {
        $recurringTasks = $this->listRecurringTasks->execute($request->user());
        $categories = $this->getCategories->execute($request->user()->id);

        $viewData = [
            'recurringTasks'    => $recurringTasks->toResourceCollection()->resolve(),
            'links'             => fn () => $recurringTasks->links(),
            'categories'        => $categories,
        ];

        if ($request->ajax() || $request->expectsJson()) {
            return view('recurring-tasks._list', $viewData);
        }

        return view('recurring-tasks.index', $viewData);
    }

    public function create(Request $request)
    {
        $categories = $this->getCategories->execute($request->user()->id);

        return view('recurring-tasks.create',
            ['categories' => $categories,
             'frequencies'=>TaskFrequency::cases()
            ]);
    }

    public function store(StoreRecurringTaskRequest $request)
    {
        $this->createRecurringTask->execute($request->user(), $request->validated());

        return to_route('recurring-tasks.index')->with('success', 'Recurring task created successfully.');
    }

    public function edit(RecurringTask $recurringTask, Request $request)
    {
        $recurringTask->load('category');

        $categories = $this->getCategories->execute($request->user()->id);

        return view('recurring-tasks.edit', [
            'recurringTask' => $recurringTask->toResource()->resolve(),
            'categories' => $categories,
            'frequencies'=>TaskFrequency::cases(),
        ]);
    }

    public function update(UpdateRecurringTaskRequest $request, RecurringTask $recurringTask)
    {
        $this->updateRecurringTask->execute($request->user(), $recurringTask, $request->validated());

        return to_route('recurring-tasks.index')->with('success', 'Recurring task updated successfully.');
    }

    public function destroy(RecurringTask $recurringTask, Request $request)
    {
        $this->deleteRecurringTask->execute($recurringTask);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'Task deleted successfully.']);
        }

        return to_route('recurring-tasks.index')->with('success', 'Recurring task deleted successfully.');
    }
}

