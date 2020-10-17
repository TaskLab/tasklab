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

Route::middleware('org.missing')->get('/', function () {
    // welcome and sign-up page
    return Inertia::render('Home');
})->name('home');

Route::middleware(['auth','org.present'])->get('/missing-org', function () {
    return Inertia::render('MissingOrg');
})->name('missing-org');

// Protect task related routes with:
// middleware(['auth','auth.org'])

/** AUTH **/
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::get('/register', [RegisterController::class, 'showRegister']);
Route::post('/register', [RegisterController::class, 'register']);

Route::prefix('org')->middleware('auth')->group(function () {
    Route::post('create', [OrganizationController::class, 'create']);
    Route::get('delete/{orgId}', [OrganizationController::class, 'delete']);
});

Route::fallback(function () {
    return redirect()->route('home');
});