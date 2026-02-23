<?php

namespace App\Models;

use App\Casts\PrettyDateCast;
use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'due_date' => PrettyDateCast::class,
            'priority' => TaskPriorityEnum::class,
            'status' => TaskStatusEnum::class,
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function medias(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    #[Scope]
    protected function todo(Builder $query): Builder
    {
        return $query->where('status', TaskStatusEnum::Todo);
    }

    #[Scope]
    protected function completed(Builder $query): Builder
    {
        return $query->where('status', TaskStatusEnum::Completed);
    }
}
