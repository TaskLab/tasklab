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

class TaskCreationType extends Model
{
    use HasFactory;

    protected $table = 'task_creation_types';
    public $timestamps = false;

    protected $fillable = [
        'creation_type'
    ];
}
