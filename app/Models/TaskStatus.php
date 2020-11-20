<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Task;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\{
    HasManyThrough,
    HasOne,
    BelongsTo,
    BelongsToMany
};

class TaskStatus extends Model
{
    use HasFactory;

    protected $table = 'task_statuses';
    public $timestamps = false;

    protected $fillable = [
        'status'
    ];

    public function task(): HasMany
    {
        return $this->hasMany(Task::class, 'status_id', 'id');
    }
}
