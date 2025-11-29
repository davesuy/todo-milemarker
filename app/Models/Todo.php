<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Todo extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'due_date',
        'is_completed',
        'completed_at',
        'user_id',
        'category_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'due_date' => 'datetime',
            'completed_at' => 'datetime',
            'is_completed' => 'boolean',
        ];
    }

    /**
     * Get the user that owns the todo.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that owns the todo.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Scope a query to only include completed todos.
     */
    public function scopeCompleted(Builder $query): void
    {
        $query->where('is_completed', true);
    }

    /**
     * Scope a query to only include incomplete todos.
     */
    public function scopeIncomplete(Builder $query): void
    {
        $query->where('is_completed', false);
    }

    /**
     * Scope a query to search todos by title or description.
     */
    public function scopeSearch(Builder $query, ?string $search): void
    {
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory(Builder $query, ?int $categoryId): void
    {
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
    }

    /**
     * Scope a query to filter overdue todos.
     */
    public function scopeOverdue(Builder $query): void
    {
        $query->where('is_completed', false)
              ->whereNotNull('due_date')
              ->where('due_date', '<', Carbon::now());
    }
}
