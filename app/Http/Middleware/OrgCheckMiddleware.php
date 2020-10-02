<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\Models\Organization;

class OrgCheckMiddleware
{
    public function handle($request, Closure $next)
    {
        $userOrg = Auth::user()->organization_id;
        if ($userOrg === null) {
            // @todo redirect to some future page asking user to register an org.
            return redirect('missing-org');
        }
       
        $org = Organization::findOrFail($userOrg);

        if ($org->active === false) {
            // @todo come up with a plan for this.
            return redirect('missing-org');
        }

        return $next($request);
    }
}
