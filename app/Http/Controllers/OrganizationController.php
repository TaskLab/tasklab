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
use Illuminate\Database\Eloquent\Builder;
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
        $options = $request->json()->all();
        if ($options['is_org']) {
            (new Organization)->createNewOrg($options['value'], Auth::user()->id);
        } else {
            $org = Organization::whereHas('organizationSetting', function (Builder $query) use ($request) {
                $query->where('org_key', $options['value']);
            })->firstOrFail();
            
            User::where('id', Auth::user()->id)->update([
                'organization_id' => $org->id
            ]);
        }

        // redirect to tasks home page.
        return redirect('/');
    }
}
