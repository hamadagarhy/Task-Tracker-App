<?php

namespace App\Actions\RecurringTask;

class BuildFrequencyConfig
{
    public function execute(string $frequency, array $data): ?array
    {
        if ($frequency === 'weekly') {
            return ['days' => array_values(array_unique($data['days'] ?? []))];
        }

        if ($frequency === 'monthly') {
            return ['day_of_month' => (int) ($data['day_of_month'] ?? 1)];
        }

        return null;
    }
}

