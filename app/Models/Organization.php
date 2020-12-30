<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\{
    BelongsToMany,
    HasOne
};

class Organization extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'organizations';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'organization_settings_id',
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
            $orgKey = $this->getRandomOrgKey();
            $orgSetting = OrganizationSetting::create([
                'point_of_contact_id' => $userId,
                'org_key'             => $orgKey
            ]);
    
            $orgRecord = [
                'name'                     => $orgName,
                'organization_settings_id' => $orgSetting->id,
                'active'                   => true
            ];

            $org = Organization::create($orgRecord);
    
            User::where('id', $userId)->update([
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

    /**
     * Help generate random Organization Key.
     *
     * @return string
     */
    private function getRandomOrgKey(): string 
    {
        $random = Str::random(8);
        while (OrganizationSetting::where('org_key', $random)->exists()) {
            $random = Str::random(8);
        }

        return strtoupper($random);
    }
}
