<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CategoryCacheService
{
    public function remember(int $userId, \Closure $callback)
    {
        return Cache::remember($this->getKey($userId), 3600, $callback);
    }

    public function getKey(int $userId): string
    {
        return 'categories.user' . $userId;
    }

    public function clear(int $id): bool
    {
       return Cache::forget($this->getKey($id));
    }

}
