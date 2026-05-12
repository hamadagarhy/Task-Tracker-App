<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecurringTaskResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'title' => $this->title,
            'description' => $this->description,
            'frequency' => $this->frequency?->value ?? (string) $this->frequency,
            'frequency_config' => $this->frequency_config,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'created_at' => $this->created_at?->format('m d ,Y g:i A'),
            'updated_at' => $this->updated_at?->format('m d ,Y g:i A'),

            'category' => $this->whenLoaded('category', fn () => [
                'id' => $this->category?->uuid,
                'name' => $this->category?->name,
            ]),
        ];
    }
}

