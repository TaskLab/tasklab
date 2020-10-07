<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\{
    Request,
    RedirectResponse
};

use Illuminate\Support\Facades\{
    Auth,
    Validator
};

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
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        } else {
            return Inertia::render('Login');
        }
    }

    /**
     * authenticate
     *
     * @param Request $request
     * @return InertiaResponse
     */
    public function login(Request $request): InertiaResponse
    {
        try {
            $credentials = $this->getValidatedLoginCredentials($request->json()->all());
            if (Auth::attempt($credentials)) {
                return Inertia::render('Login', [
                    'success' => 'User credentials valid.'
                ]);
            } else {
                throw new Exception('Invalid login credentials.');
            }
        } catch (Exception $e) {
            return Inertia::render('Login', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        return redirect()->route('home');
    }

    /**
     * return validated login credentials or throw exception
     *
     * @param array $credentials
     * @return array
     */
    private function getValidatedLoginCredentials(array $credentials): array
    {
        $errorMessages = [
            'required' => 'The :attribute field is required.',
            'email'    => 'The :attribute provided is not valid.',
            'string'   => 'The :attribute must be a string.'
        ];

        $validator = Validator::make($credentials, [
            'email' => 'required|email:rfc',
            'password' => 'required|string'
        ], $errorMessages);

        if ($validator->fails()) {
            $msgs = Arr::flatten(
                array_values($validator->errors()->getMessages())
            );

            foreach ($msgs as $key => $msg) {
                $errorMessages .= ($key !== (count($msgs) - 1))
                    ? $msg . "\n"
                    : $msg;
            }

            throw new Exception($errorMessages);
        }

        return $credentials;
    }
}
