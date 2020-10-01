<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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
     * Create a new organization and related records.
     *
     * @param string $orgName
     * @param integer $userId
     * @return boolean
     */
    public function createNewOrg(string $orgName, int $userId): bool 
    {
        DB::transaction(function () use ($orgName, $userId): void {
            $orgSetting = OrganizationSetting::create([
                'point_of_contact_id' => $userId
            ]);
    
            $orgRecord = [
                'org_name'                => $orgName,
                'organization_setting_id' => $orgSetting->id,
                'active'                  => true
            ];
            $org = Organization::create($orgRecord);
    
            User::where('id', 1)->update([
                'organization_id' => $org->id
            ]);
        });

        return true;
    }

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
