<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{
    Model,
    Collection
};

use Illuminate\Database\Eloquent\Relations\{
    HasManyThrough,
    HasOne,
    BelongsTo,
    BelongsToMany
};

class TaskType extends Model
{
    use HasFactory;

    protected $table = 'task_types';
    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

    public static function getAllAsOptions(): Collection
    {
        return self::select('id', 'name')->get();
    }
}
