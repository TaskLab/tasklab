<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\{
    HasManyThrough,
    HasOne,
    BelongsTo,
    BelongsToMany
};

class TaskRelation extends Model
{
    use HasFactory;

    protected $table = 'task_relations';

    protected $fillable = [
        'task_id',
        'parent_id',
        'child_id'
    ];

    public function task(): HasOne
    {
        return $this->hasOne(Task::class, 'id', 'task_id');
    }

    public function parent(): HasOne
    {
        return $this->hasOne(Task::class, 'id', 'parent_id');
    }

    public function child(): HasOne
    {
        return $this->hasOne(Task::class, 'id', 'child_id');
    }
}
