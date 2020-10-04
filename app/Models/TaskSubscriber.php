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

class TaskSubscriber extends Model
{
    use HasFactory;

    protected $table = 'task_subscribers';
    public $timestamps = true;

    protected $fillable = [
        'task_id',
        'user_id'
    ];

    public function subscriber(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function task(): HasOne
    {
        return $this->hasOne(Task::class, 'id', 'task_id');
    }
}
