<?php

namespace App\Http\Controllers;

use App\Actions\Category\GetCategories;
use App\Actions\Category\ResolveCategory;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    public function __construct(private readonly GetCategories $getCategories)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $category = null;

        if($request->category_id) {

            $category = Category::where('uuid', $request->category_id)->first();

            if (!$category || $request->user()->cannot('manage', $category)) {
                throw ValidationException::withMessages(['category_id' => 'Category id not found.']);
            }
        }

        $tasks = $user->tasks()->with('category')->latest();

        //filter by status
        if($request->filled('status')){
            if($request->status === 'completed'){
                $tasks->whereNotNull('completed_at');
            }
            elseif ($request->status === 'incomplete'){
                $tasks->whereNull('completed_at');
            }
        }

        //filter by category
        if ($request->filled('category_id')) {
            $tasks->whereCategoryId($category->id);
        }

        //filter by date range
        if ($request->from) {
            $tasks->whereDate('task_date', '>=', $request->from);
        }

        if ($request->to) {
            $tasks->whereDate('task_date', '<=', $request->to);
        }

        $tasks = $tasks->paginate()->withQueryString();

        $categories = $this->getCategories->execute($request->user()->id);
        //$categories = $request->user()->categories()->orderBy('name')->pluck('name', 'uuid')->toArray();

        $viewData = [
            'tasks' => $tasks->toResourceCollection()->resolve(),
            'links' => fn() => $tasks->links(),
            'categories' => $categories,
            'filters' => $request->only(['status', 'category_id', 'from', 'to']),
        ];

        if ($request->ajax() || $request->expectsJson()) {
            return view('tasks._list', $viewData);
        }

        return view('tasks.index', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $categories = $this->getCategories->execute($request->user()->id);

        return view('tasks.create', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request, ResolveCategory $resolveCategory)
    {
        $taskData = $request->validated();

        $taskData['category_id'] = $resolveCategory->execute($taskData['category_id'] ?? '', $request->user());

        $request->user()->tasks()->create($taskData);

        return to_route('tasks.index')->with('success', 'Task created successfully.');

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task, Request $request)
    {
        $task->load('category');

        $categories = $this->getCategories->execute($request->user()->id);
        //$categories = $request->user()->categories()->orderBy('name')->pluck('name', 'uuid')->toArray();

        return view('tasks.edit', ['task' => $task->toResource()->resolve(), 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task, ResolveCategory $resolveCategory)
    {
        $taskData = $request->validated();
        $taskData['category_id'] = $resolveCategory->execute($taskData['category_id'] ?? '', $request->user());

//        if($request->category_id) {
//
//            $category = Category::where('uuid', $request->category_id)->first();
//
//            if (!$category || $request->user()->cannot('manage', $category)) {
//                throw ValidationException::withMessages(['category_id' => 'Category id not found.']);
//            }
//
//            $taskData['category_id'] = $category->id;
//
//        }else {
//            $taskData['category_id'] = null;
//        }

        $task->update($taskData);

        return to_route('tasks.index')->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task, Request $request)
    {
        $task->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'Task deleted successfully.']);
        }

        return to_route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    public function toggleComplete(Task $task, Request $request)
    {
        $task->completed_at = $task->completed_at ? null : now();
        $task->save();


//        if($task->completed_at)
//        {
//            $task->update(['completed_at' => null]);
//        }
//        else
//        {
//            $task->update(['completed_at' => now()]);
//        }
//
//        $redirectTo = (string) $request->input('redirect_to', '');
//
//        if ($redirectTo !== '' && str_starts_with($redirectTo, url('/'))) {
//            return redirect()->to($redirectTo)->with('success', 'Task updated successfully.');
//        }
//
        //return back()->with('success', 'Task updated successfully.');

        return response()->json(['completed' => $task->completed_at!== null]);
    }
}
