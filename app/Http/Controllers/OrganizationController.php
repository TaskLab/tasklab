<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\{
    Organization,
    OrganizationSetting,
    User
};

use Illuminate\Http\{
    JsonResponse,
    Request
};

use Auth;
use Illuminate\Support\Facades\DB;

class OrganizationController extends Controller
{

    /**
     * Create a new Org.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        
        $valid = $request->validate([
            'org_name' => 'required|max:255'
        ]);

        if (!$valid) {
            return response()->json([
                'status' => 'Missing or invalid org_name'
            ], 422);
        }

        if (Auth::user()->organization_id !== null) {
            return response()->json([
                'status' => 'Cannot create new org, you\'re already in an org.'
            ], 403);
        }

        DB::transaction(function () use ($request): void {
            $orgSetting = OrganizationSetting::create([
                'point_of_contact_id' => Auth::user()->id
            ]);
    
            $orgRecord = [
                'org_name'                => $request->org_name,
                'organization_setting_id' => $orgSetting->id,
                'active'                  => true
            ];
            $org = Organization::create($orgRecord);
    
            User::where('id', 1)->update([
                'organization_id' => $org->id
            ]);
        });

        return response()->json([
            'status' => "Org was created successfully."
        ]);
    }
}
