<?php

namespace App\Actions\Category;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListCategories
{
    public function execute(User $user): LengthAwarePaginator
    {
        return $user->categories()
            ->latest()
            ->paginate()
            ->withQueryString();
    }
}

