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

class TaskComment extends Model
{
    use HasFactory;

    protected $table = 'task_comments';
    public $timestamps = true;

    protected $fillable = [
        'task_id',
        'author_id',
        'comment'
    ];

    public function author(): HasOne
    {
        return $this->hasOne(User::class, 'author_id', 'id');
    }

    public function task(): HasOne
    {
        return $this->hasOne(Task::class);
    }
}
