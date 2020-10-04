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

class TaskPriority extends Model
{
    use HasFactory;

    protected $table = 'task_priorities';
    public $timestamps = false;

    protected $fillable = [
        'priority'
    ];
}
