<?php

namespace App\Console\Commands;

use App\Enums\TaskFrequency;
use App\Models\RecurringTask;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class GenerateRecurringTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-recurring-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate recurring tasks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. determine the date for generating tasks
        $targetDate = today();

        // 2. find recurring tasks within date range
        $recurringTasks = RecurringTask::query()
            ->where(fn($query) => $query->whereNull('start_date')
            ->orWhere('start_date', '<=', $targetDate))
            ->where(fn($query) => $query->whereNull('end_date')
            ->orWhere('end_date', '>=', $targetDate))
            ->whereDoesntHave('tasks', fn($query) => $query->whereDate('task_date', $targetDate));

        if (! ($totalRecurringTasks = $recurringTasks->count())) {
            $this->info("No recurring tasks found");
            return self::FAILURE;
        }

        $this->info('Generating' . $totalRecurringTasks . 'recurring task templates');

        $created = 0;
        $skipped = 0;



        $recurringTasks->chunkById(250, function (Collection $recurringTasks) use($targetDate, &$skipped, &$created) {

            try{
            $insertTaskBatch = [];

            foreach ($recurringTasks as $recurringTask) {
                try {

                    // 3. For each recurring template we check if we should generate a task for this date based on frequency
                    if (!$this->isRecurringTaskDue($recurringTask, $targetDate)) {
                        $skipped++;
                        continue;
                    }

                    // 4. verify we haven't already generated this task to avoid duplicates
//                $exists = Task::query()
//                    ->where('recurring_task_id', $recurringTask->id)
//                    ->whereDate('task_date', $targetDate)
//                    ->exists();
//
//                if ($exists) {
//                    $skipped++;
//                    continue;
//                }

                    // 5. create the task
                    $insertTaskBatch [] = [
                        'uuid' => (string)Str::uuid7(),
                        'user_id' => $recurringTask->user_id,
                        'category_id' => $recurringTask->category_id,
                        'recurring_task_id' => $recurringTask->id,
                        'title' => $recurringTask->title,
                        'description' => $recurringTask->description,
                        'task_date' => $targetDate,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                }catch (\Exception $e){
                    report($e);
                }
            }

                if ($insertTaskBatch) {
                    Task::insert($insertTaskBatch);

                    $created += count($insertTaskBatch);
                }


        }catch (\Exception $e){
                report($e);
            }
        }
        );


        // 7. output feedback
        $this->info('Created ' . $created . ' recurring tasks');

        if ($skipped > 0) {
            $this->warn('Skipped ' . $skipped . ' recurring tasks');
        }


        $this->newLine();

        return self::SUCCESS;

    }

    private function isRecurringTaskDue(RecurringTask $recurringTask, Carbon $targetDate)
    {
        return match ($recurringTask->frequency)
        {
            TaskFrequency::Daily      => true,
            TaskFrequency::Weekdays   => $targetDate->isWeekday(),
            TaskFrequency::Weekly     => $this->isWeeklyRecurringTaskDue($recurringTask, $targetDate),
            TaskFrequency::Monthly    => $this->isMonthlyRecurringTaskDue($recurringTask, $targetDate),
            default => false,
        };
    }

    private function isWeeklyRecurringTaskDue(RecurringTask $recurringTask, Carbon $targetDate)
    {
        $config = $recurringTask->frequency_config;

        if(! $config || ! isset($config['days']) || ! is_array($config['days']))
        {
            return false;
        }

        return in_array(strtolower($targetDate->englishDayOfWeek), $config['days']);
    }

    private function isMonthlyRecurringTaskDue(RecurringTask $recurringTask, Carbon $targetDate)
    {
        $config = $recurringTask->frequency_config;

        if(! $config || ! isset($config['days']) || ! is_array($config['days']))
        {
            return false;
        }

        $dayOfMonth = min($config['days'], $targetDate->daysInMonth());

        return $targetDate->day === $dayOfMonth;
    }
}
