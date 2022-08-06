<?php



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

use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\StoreController;
use App\Models\Beacon;
use App\Models\PointsOffer;
use App\Models\Voucher;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;



//Admin views

Route::group(
    ['middleware' => ['web', 'auth:admin'], 'prefix' => 'admin'],
    function () {
        Route::get('dashboard', [AdminController::class, 'adminDashboardView'])->name('adminDashboardView');
        Route::get('stores', [AdminController::class, 'storesView'])->name('stores');
        Route::get('admins', [AdminController::class, 'adminsView'])->name('admins');
        Route::get('users', [AdminController::class, 'usersView'])->name('users');
        Route::get('annonces', [AdminController::class, 'annoncesView'])->name('annonces');
        Route::post('store/create', [RegisterController::class, 'createStore'])->name('createStore');
        Route::post('annonce/create', [AnnonceController::class, 'createAnnonce'])->name('createAnnonce');
    }
);



Route::get(
    'setting/account',
    function () {
        if (Auth::guard('admin')->check()) {
            $user = Auth::guard('admin')->user();
            $isAdmin = true;
        } else if (Auth::guard('store')->check()) {
            $isAdmin = false;
            $user = Auth::guard('store')->user();
        }


        return view('content.pages.settings-account', ['user' => $user, 'isAdmin' => $isAdmin]);
    }
)
    ->name('storeAccountSetting');


Route::view('login', 'content.authentications.auth-login-basic')->name('loginView');
Route::view('reset', 'content.authentications.auth-forgot-password-basic')->name('resetPwdView');





//Store routes
Route::group(['middleware' => ['web', 'auth:store'], 'prefix' => 'store'], function () {
    Route::view('dashboard', 'content.dashboard.dashboards-store')->name('storeDashboardView')->middleware(['web', 'auth:store']);
    Route::get('beacon', function () {
        return view('content.store.beacons', ['beacons' => Beacon::where('idStore', Auth::guard('store')->user()->id)->get(),]);
    })->name('beaconsView');

    Route::get('voucher', function () {
        return view('content.store.vouchers', ['vouchers' => Voucher::where('idStore', Auth::guard('store')->user()->id)->get(), 'beacons' => Beacon::where('idStore', Auth::guard('store')->user()->id)->get()]);
    })->name('vouchersView');

    Route::get('pointoffre', function () {
        return view('content.store.points', ['pointOffres' => PointsOffer::where('idStore', Auth::guard('store')->user()->id)->get(), 'beacons' => Beacon::where('idStore', Auth::guard('store')->user()->id)->get()]);
    })->name('pointOfferView');

    Route::post('setting/account', [StoreController::class, 'editProfile'])->name('editStoreProfile');
    Route::post('beacon/create', [StoreController::class, 'createBeacon'])->name('createBeacon');
    Route::post('voucher/create', [StoreController::class, 'createVoucher'])->name('createVoucher');
    Route::post('pointoffre/create', [StoreController::class, 'createPointOffre'])->name('createPointOffre');
    Route::post('reset', [LoginController::class, 'resetPwdStore'])->name('resetStore');
});




Route::post('login', [LoginController::class, 'login'])->name('login')->middleware('guest');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
Route::post('admin/create', [RegisterController::class, 'createAdmin'])->name('createAdmin');



// Main Page Route
// Route::get('/home', $controller_path . '\dashboard\Analytics@index')->name('home');
