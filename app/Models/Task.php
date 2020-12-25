<?php declare(strict_types=1);

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{
    Model,
    Collection
};

use Illuminate\Database\Eloquent\Relations\{
    HasManyThrough,
    HasOne,
    HasMany,
    BelongsTo,
    BelongsToMany
};

class Task extends Model
{
    use HasFactory;
    protected $table = 'tasks';
    public $timestamps = true;

    protected $fillable = [
        'owner_id',
        'status_id',
        'priority_id',
        'type_id',
        'status_id',
        'state_id',
        'author_id',
        'description',
        'organization_id',
        'parent_id',
        'target_date',
        'title',
        'link'
    ];

    /**
     * get all tasks for option dropdown
     *
     * @return Collection
     */
    public static function getAllAsOptions(): Collection
    {
        return self::select('id', 'title')
            ->where('organization_id', Auth::user()->organization_id)
            ->get();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(TaskStatus::class, 'status_id', 'id');
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(TaskState::class);
    }

    public function priority(): BelongsTo
    {
        return $this->belongsTo(TaskPriority::class, 'priority_id', 'id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(TaskType::class, 'type_id', 'id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class, 'task_id', 'id');
    }

    public function subscribers(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, TaskSubscriber::class, 'task_id', 'id');
    }

    public function childrenTasks(): HasManyThrough
    {
        return $this->hasManyThrough(Task::class, TaskRelation::class, 'child_id', 'id', 'id', 'task_id');
    }

    public function parentTask(): HasManyThrough
    {
        return $this->hasManyThrough(Task::class, TaskRelation::class, 'parent_id', 'id', 'id', 'task_id');
    }

    public function taskHistory(): belongsToMany
    {
        return $this->belongsToMany(TaskChanges::class, 'task_id', 'id');
    }
}
