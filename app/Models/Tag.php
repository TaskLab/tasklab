<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\{
    Model,
    Collection
};

class Tag extends Model
{
    use HasFactory;
    public $table = 'tags';

    /**
    * get all task tags for option dropdown
     *
     * @return Collection
     */
    public static function getAllAsOptions(): Collection
    {
        return self::select('id', 'name')->get();
    }

    public function taskTag(): HasMany
    {
        return $this->hasMany(TaskTag::class);
    }
}
