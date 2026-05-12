<?php

namespace App\Actions\RecurringTask;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListRecurringTasks
{
    public function execute(User $user): LengthAwarePaginator
    {
        return $user->recurringTasks()
            ->with('category')
            ->latest()
            ->paginate()
            ->withQueryString();
    }
}

