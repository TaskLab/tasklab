<?php declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    DashboardController,
    LoginController,
    RegisterController,
    TaskController,
    OrganizationController
};

use Illuminate\Http\{
    JsonResponse,
    Request
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

/** GUEST-DEPENDENT ROUTES **/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegister']);
    Route::post('/register', [RegisterController::class, 'register']);
});

/** AUTH-DEPENDENT ROUTES **/
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        $user = Auth::user();
        return Inertia::render('Home', [
            'user' => $user
        ]);
    })->name('home');

    Route::post('/logout', [LoginController::class, 'logout']);

    /** ORGANIZATION ROUTES  **/
    Route::prefix('org')->group(function () {
        Route::post('create', [OrganizationController::class, 'create']);
        Route::get('delete/{orgId}', [OrganizationController::class, 'delete']);
    });
    Route::middleware('auth.org')->get('/missing-org', function () {
        return Inertia::render('MissingOrg');
    })->name('missing-org');

    /** DASHBOARD ROUTES **/
    Route::prefix('dashboard')->group(function() {
        Route::get('org-user-stats', [DashboardController::class, 'getOrgUserStats']);
    });

    /** TASK ROUTES **/
    Route::resource('tasks', TaskController::class);
});


Route::get('/navigation-items', function (): JsonResponse {
    $items = \App\Models\NavigationItem::all();

    return response()->json([
        'items' => $items
    ]);
});

Route::fallback(function () {
    return redirect()->route('home');
});