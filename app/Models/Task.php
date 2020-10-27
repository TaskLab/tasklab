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

    public function owner(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'owner_id');
    }

    public function status(): HasOne
    {
        return $this->hasOne(TaskStatus::class, 'id', 'status_id');
    }

    public function priority(): HasOne
    {
        return $this->hasOne(TaskPriority::class, 'id', 'priority_id');
    }

    public function taskType(): HasOne
    {
        return $this->hasOne(TaskType::class, 'id', 'type_id');
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

    public function parentTasks(): HasManyThrough
    {
        return $this->hasManyThrough(Task::class, TaskRelation::class, 'parent_id', 'id', 'id', 'task_id');
    }

    public function taskHistory(): belongsToMany
    {
        return $this->belongsToMany(TaskChanges::class, 'task_id', 'id');
    }
}
