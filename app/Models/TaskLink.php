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


class TaskLink extends Model
{
    use HasFactory;

    protected $table = 'task_links';
    public $timestamps = true;

    protected $fillable = [
        'task_id',
        'url'
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
