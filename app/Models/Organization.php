<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    BelongsToMany,
    HasOne
};

class Organization extends Model
{
    use HasFactory;

    protected $table = 'organization';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'org_name',
        'organization_setting_id',
        'active',
    ];

    /**
     * User relationship - each org will have multiple users related.
     *
     * @return BelongsToMany
     */
    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'id');
    }

    /**
     * Org Settings relationship
     *
     * @return HasOne
     */
    public function organizationSetting(): HasOne
    {
        return $this->hasOne(OrganizationSetting::class, 'id', 'organization_setting_id');
    }
}
