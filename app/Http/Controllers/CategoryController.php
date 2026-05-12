<?php

namespace App\Http\Controllers;

use App\Actions\Category\CreateCategory;
use App\Actions\Category\DeleteCategory;
use App\Actions\Category\ListCategories;
use App\Actions\Category\UpdateCategory;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        private readonly ListCategories $listCategories,
        private readonly UpdateCategory $updateCategory,
        private readonly DeleteCategory $deleteCategory,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = $this->listCategories->execute($request->user());

        $viewData = [
            'categories' => $categories->toResourceCollection()->resolve(),
            'links' => fn () => $categories->links(),
        ];

        if ($request->ajax() || $request->expectsJson()) {
            return view('categories._list', $viewData);
        }

        return view('categories.index', $viewData);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request, CreateCategory $createCategory)
    {
        $categoryData = $request->validated();

        $createCategory->execute($categoryData, $request->user());

        return to_route('categories.index')->with('success', 'Category created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', ['category' => $category->toResource()->resolve()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $this->updateCategory->execute($category, $request->validated());

        return to_route('categories.index')->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category, Request $request)
    {
        $this->deleteCategory->execute($category);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'Category deleted successfully.']);
        }

        return to_route('categories.index')->with('success', 'Category deleted successfully');
    }
}
