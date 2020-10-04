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

class TaskStatus extends Model
{
    use HasFactory;

    protected $table = 'task_statuses';
    public $timestamps = false;

    protected $fillable = [
        'status'
    ];
}
