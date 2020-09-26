<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    HasOne
};

class OrganizationSetting extends Model
{
    use HasFactory;

    protected $table = 'organization_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * Org relationship
     *
     * @return  BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'id', 'id');
    }

    /**
     * User relationship for point of contact
     *
     * @return HasOne
     */
    public function pointOfContact(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'point_of_contact_id');
    }
}
