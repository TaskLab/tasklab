<?php

namespace App\Http\Controllers;

use Illuminate\Http\{
    Request,
    JsonResponse
};

use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class RegisterController extends Controller
{
    public function showRegister(Request $request): InertiaResponse
    {
        return Inertia::render('Register');
    }

    public function register(Request $request): InertiaResponse
    {
        dd($request->all());
    }
}
