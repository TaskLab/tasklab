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
     * Disable an organization
     *
     * @param Request $request
     * @param integer $orgId
     * @return JsonResponse
     */
    public function delete(Request $request, int $orgId): JsonResponse 
    {
        $org = Organization::findOrFail($orgId);
        $org->active = false;
        $org->save();

        // @todo send email to POC letting them know or for a confirmation?
        // Future code. For now it will disable when we want.

        return response()->json([
            'status' => 'Organization successfully disabled.'
        ]);
    }

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

        (new Organization)->createNewOrg($request->org_name, Auth::user()->id);

        return response()->json([
            'status' => "Org was created successfully."
        ]);
    }
}
