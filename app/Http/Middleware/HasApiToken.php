<?php 

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;

class HasApiToken
{
    /**
     * Check token.
     *
     * @param Request $request
     * @param Closure $next
     * @param [type] ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $token = $request->bearerToken();
        $key = $request->input('org_api_key');

        $exist = DB::table('users')
            ->leftJoin('organizations', 'users.organization_id', '=', 'organizations.id')
            ->where([
                ['users.api_token', $token],
                ['organizations.org_api_key', $key]
            ])->exists();

        if (!$exists) {
            $msg = 'Invalid Auth Token and Org API Key combination. Please see documentation: <our generated docs here.>';
            
            return response()->json(['error' => $msg], 401);
        }

        return $next($request);
    }
}