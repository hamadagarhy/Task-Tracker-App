<?php

namespace App\Models;

use App\Enums\TaskFrequency;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecurringTask extends Model
{
    use HasUuids;
    use SoftDeletes;
    use HasFactory;

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'frequency',
        'frequency_config',
        'start_date',
        'end_date',
    ];

    public function casts(): array
    {
        return [
            'frequency'         => TaskFrequency::class,
            'frequency_config'  => 'array',
            'start_date'        => 'date',
            'end_date'          => 'date',
        ];
    }

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function resourceClass(): string
    {
        return \App\Http\Resources\RecurringTaskResource::class;
    }
}
