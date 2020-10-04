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

class TaskChanges extends Model
{
    use HasFactory;

    protected $table = 'task_changes';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'task_id',
        'field',
        'old_value',
        'new_value'
    ];

    public function task(): HasOne
    {
        return $this->hasOne(Task::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
