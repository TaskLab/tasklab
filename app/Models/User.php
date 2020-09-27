<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\{
    BelongsTo,
    HasOne
};


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'organization_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * relationship to which org is the user a part of
     *
     * @return HasOne
     */
    public function organization(): HasOne
    {
        return $this->hasOne(Organization::class, 'organization_id', 'id');
    }

    /**
     * User could be a point of contact for an org.
     *
     * @return BelongsTo
     */
    public function pointOfContactFor(): BelongsTo
    {
        return $this->belongsTo(OrganizationSetting::class, 'point_of_contact_id', 'id');
    }
}
