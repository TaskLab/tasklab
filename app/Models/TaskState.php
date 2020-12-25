<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskState extends Model
{
    use HasFactory;

    protected $table = 'task_states';

    public function task(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
