<?php

namespace App\Observers;

use App\Models\Category;
use App\Services\CategoryCacheService;

class CategoryObserver
{

    public function __construct(private readonly CategoryCacheService $categoryCacheService)
    {
    }

    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        $this->categoryCacheService->clear($category->user_id);
    }

    /**
     * Handle the Category "updated" event.
     */
    public function updated(Category $category): void
    {
        $this->categoryCacheService->clear($category->user_id);
    }

    /**
     * Handle the Category "deleted" event.
     */
    public function deleted(Category $category): void
    {
        $this->categoryCacheService->clear($category->user_id);
    }

}
