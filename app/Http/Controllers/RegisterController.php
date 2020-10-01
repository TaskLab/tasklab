<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

use App\Models\{
    Organization,
    OrganizationSetting,
    User
};

use Illuminate\Support\Facades\{
    DB,
    Hash,
    Validator
};

use Inertia\{
    Inertia,
    Response as InertiaResponse
};

class RegisterController extends AppController
{
    /**
     * render the registration page
     *
     * @return InertiaResponse
     */
    public function showRegister(): InertiaResponse
    {
        return Inertia::render('Register');
    }

    /**
     * process new user registration
     *
     * @param Request $request
     * @return InertiaResponse
     */
    public function register(Request $request): InertiaResponse
    {
        try {
            $payload = $this->getValidatedRegistrationPayload($request->json()->all());
            $this->submitRegistrationPayload($payload);
        } catch (Exception $e) {
            return Inertia::render('Register', [
                'error' => $e->getMessage()
            ]);
        }

        return Inertia::render('Register', [
            'success' => 'New account created successfully.'
        ]);
    }

    /**
     * submit new user credentials
     *
     * @param array $payload
     * @return void
     */
    private function submitRegistrationPayload(array $payload): void
    {
        $user = User::create([
            'name'     => $payload['firstname'] . ' ' . $payload['lastname'],
            'email'    => $payload['email'],
            'password' => Hash::make($payload['password'])
        ]);

        (new Organization)->createNewOrg($payload['organization'], $user->id);
    }

    /**
     * validate and submit new user request payload or throw exception
     *
     * @param array $payload
     * @return array
     */
    private function getValidatedRegistrationPayload(array $payload): array
    {
        $errorMessages = [
            'required' => 'The :attribute field is required.',
            'alpha'    => 'The :attribute value must only contain alphabetic characters.',
            'email'    => 'The :attribute provided is not valid.',
            'unique'   => 'The :attribute value already exists.',
            'min'      => 'The :attribute value must be a minimum of 8 characters.',
        ];

        $validator = Validator::make($payload,[
            'firstname'    => 'required|alpha',
            'lastname'     => 'required|alpha',
            'email'        => 'required|email:rfc',
            'organization' => 'unique:organization,org_name|alpha_num',
            'password'     => 'required|min:8'
        ], $errorMessages);

        $errorMessages = '';

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

        return $payload;
    }
}
