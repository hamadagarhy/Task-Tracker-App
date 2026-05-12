<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;

class Task extends Model
{
    use HasUuids;
    use HasFactory;

    /**
     * Actual FK column on `tasks` (legacy migrations used "Category_id").
     */
    protected static ?string $resolvedCategoryForeignKey = null;

    public static function categoryForeignKey(): string
    {
        if (static::$resolvedCategoryForeignKey !== null) {
            return static::$resolvedCategoryForeignKey;
        }

        $instance = new static;

        if (! Schema::hasTable($instance->getTable())) {
            return static::$resolvedCategoryForeignKey = 'category_id';
        }

        foreach (Schema::getColumnListing($instance->getTable()) as $column) {
            if (strcasecmp($column, 'category_id') === 0) {
                return static::$resolvedCategoryForeignKey = $column;
            }
        }

        return static::$resolvedCategoryForeignKey = 'category_id';
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    protected $fillable = [
        'title',
        'description',
        'task_date',
        'completed_at',
        'category_id',
        'recurring_task_id',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
            'task_date' => 'datetime',
        ];
    }

    /**
     * Map logical category_id (forms, validation) to the real DB column name.
     */
    protected function categoryId(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                $key = static::categoryForeignKey();

                return $attributes[$key] ?? null;
            },
            set: fn (mixed $value) => [static::categoryForeignKey() => $value],
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, static::categoryForeignKey());
    }

    public function recurringTask(): BelongsTo
    {
        return $this->belongsTo(RecurringTask::class);
    }


    public function scopeWhereCategoryId($query, mixed $id)
    {
        return $query->where(static::categoryForeignKey(), $id);
    }

    public function resourceClass(): string
    {
        return \App\Http\Resources\TaskResource::class;
    }
}
