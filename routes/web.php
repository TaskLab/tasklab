<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    LoginController,
    RegisterController,
    OrganizationController
};

use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // welcome and sign-up page.
    return Inertia::render('Home');
})->name('/');

// Protect task related routes with:
// middleware(['auth','auth.org'])

Route::post('/login', [LoginController::class, 'login']);
Route::prefix('org')->middleware('auth')->group(function () {
    Route::post('create', [OrganizationController::class, 'create']);
    Route::get('delete/{orgId}', [OrganizationController::class, 'delete']);
});

Route::get('/register', [RegisterController::class, 'showRegister']);
Route::post('/register', [RegisterController::class, 'register']);