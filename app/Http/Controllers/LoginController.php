<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\{
    Inertia,
    Response as InertiaResponse
};

class LoginController extends Controller
{
    /**
     * render the login page for existing users
     *
     * @return InertiaResponse
     */
    public function showLogin(): InertiaResponse
    {
        return Inertia::render('Login');
    }

    /**
     * Authenticate
     *
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function login(Request $request)
    {
        $valid = $request->validate([
            'email'    => 'required',
            'password' => 'required'
        ]);

        if (!$valid) {
            return response()->json([
                'status' => 'Invalid email and/or password'
            ], 422);
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect('/');
        }

        return response()->json(['status' => 'Login failed.'], 401);
    }
}
