<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this-> uuid,
            'title'        => $this->title,
            'description'  => $this->description,

            'task_date'    => (new DateTimeResource($this->task_date, includeTime: false))->resolve($request),
            'task_date_display' => (new DateTimeResource ($this->task_date))->resolve($request),
            'completed_at' => $this->completed_at ?(new DateTimeResource($this->completed_at))->resolve($request): null,
            'created_at'   => (new DateTimeResource($this->created_at))->resolve($request),
            'updated_at'   => (new DateTimeResource($this->updated_at))->resolve($request),

            'category'     =>$this->whenLoaded('category', fn() => [
                'id'       => $this->category->uuid,
                'name'     => $this->category->name,
            ]),

        ];
    }
}
