<?php

namespace App\Actions\Category;

use App\Models\Category;
use App\Services\CategoryCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GetCategories
{

    public function __construct(private readonly CategoryCacheService $categoryCacheService)
    {
    }

    public function execute(int $userId): array
    {
        return $this->categoryCacheService->remember($userId,fn() => Category::where('user_id', $userId)
            ->orderBy('name')
            ->pluck('name', 'uuid')
            ->toArray()
        );
    }
}
