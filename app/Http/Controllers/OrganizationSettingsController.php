<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

use App\Models\{
    Organization,
    OrganizationSetting,
    User
};

use Illuminate\Http\{
    JsonResponse,
    Request
};

use Inertia\{
    Inertia,
    Response as InertiaResponse
};

class OrganizationSettingsController extends Controller
{
    public function index(Request $request): InertiaResponse
    {
        $orgID = Auth::user()->organization_id;
        $org = Organization::find($orgID);

        return Inertia::render('OrganizationSettings/View', [
            'org'          => $org,
            'org_settings' => $org->organizationSetting(),
        ]);
    }
}