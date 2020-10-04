<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'source_id',
        'author_id',
        'description',
        'title'
    ];

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

    public function source(): HasOne
    {
        return $this->hasOne(TaskCreationType::class, 'id', 'source_id');
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
