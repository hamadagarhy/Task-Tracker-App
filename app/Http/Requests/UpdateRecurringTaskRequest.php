<?php

namespace App\Http\Requests;

use App\Enums\TaskFrequency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRecurringTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,uuid'],

            'frequency' => ['required', Rule::enum(TaskFrequency::class)],
            'days' => ['nullable', 'array'],
            'days.*' => ['in:mon,tue,wed,thu,fri,sat,sun'],
            'day_of_month' => ['nullable', 'integer', 'min:1', 'max:31'],

            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ];
    }
}

