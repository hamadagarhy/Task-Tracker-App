<?php

namespace App\Actions\RecurringTask;

use App\Actions\Category\ResolveCategory;
use App\Models\RecurringTask;
use App\Models\User;

class UpdateRecurringTask
{
    public function __construct(
        private readonly ResolveCategory $resolveCategory,
        private readonly BuildFrequencyConfig $buildFrequencyConfig,
    ) {
    }

    public function execute(User $user, RecurringTask $recurringTask, array $data): RecurringTask
    {
        $categoryId = $this->resolveCategory->execute($data['category_id'] ?? '', $user);
        $frequencyConfig = $this->buildFrequencyConfig->execute((string) ($data['frequency'] ?? ''), $data);

        $recurringTask->update([
            'category_id' => $categoryId,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'frequency' => $data['frequency'],
            'frequency_config' => $frequencyConfig,
            'start_date' => $data['start_date'] ?? null,
            'end_date' => $data['end_date'] ?? null,
        ]);

        return $recurringTask;
    }
}

