<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Organization;

class OrgCheckMiddleware
{
    public function handle($request, Closure $next)
    {
        $userOrg = \Auth::user()->organization_id;
        if ($userOrg === null) {
            // @todo redirect to some future page asking user to register an or.
            return redirect('/');
        }
       
        $org = Organization::findOrFail($userOrg);

        if ($org->active === false) {
            // @todo come up with a plan for this.
            return redirect('/');
        }

        return $next($request);
    }
}
