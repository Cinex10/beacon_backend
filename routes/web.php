<?php

use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\StoreController;
use App\Models\Admin;
use App\Models\Annonce;
use App\Models\Beacon;
use App\Models\PointsOffer;
use App\Models\Store;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

$controller_path = 'App\Http\Controllers';

Route::group(
    ['middleware' => ['web', 'auth:admin'], 'prefix' => 'admin'],
    function () {
        Route::get('dashboard', function () {
            $config = file_get_contents(base_path('storage/systemConfig.json'));
            $configData = json_decode($config);
            $counstStores = Store::all()->count();
            return view('content.admin.dashboards-admin', ['configData' => $configData, 'countStores' => $counstStores]);
        })->name('adminDashboardView');
        Route::view('stores', 'content.admin.stores', ['stores' => Store::all()])->name('stores');
        Route::view('admins', 'content.admin.admins', ['admins' => Admin::all()])->name('admins');
        Route::view('users', 'content.admin.users', ['users' => User::all()])->name('users');
        Route::view('annonces', 'content.admin.annonces', ['annonces' => Annonce::all()])->name('annonces');
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
)->name('storeAccountSetting');


Route::view('login', 'content.authentications.auth-login-basic')->name('loginView');
Route::view('reset', 'content.authentications.auth-forgot-password-basic')->name('resetPwdView');
Route::post('store/create', [RegisterController::class, 'createStore'])->name('createStore');

Route::post('annonce/create', [AnnonceController::class, 'createAnnonce'])->name('createAnnonce');




//Store routes
Route::group(['middleware' => ['web', 'auth:store'], 'prefix' => 'store'], function () {
    Route::get('beacon', function () {
        return view('content.store.beacons', ['beacons' => Beacon::where('idStore', Auth::guard('store')->user()->id)->get(),]);
    })->name('beaconsView');

    Route::get('voucher', function () {
        return view('content.store.vouchers', ['vouchers' => Voucher::where('idStore', Auth::guard('store')->user()->id)->get(), 'beacons' => Beacon::where('idStore', Auth::guard('store')->user()->id)->get()]);
    })->name('vouchersView');

    Route::get('pointoffre', function () {
        return view('content.store.points', ['pointOffres' => PointsOffer::where('idStore', Auth::guard('store')->user()->id)->get(), 'beacons' => Beacon::where('idStore', Auth::guard('store')->user()->id)->get()]);
    })->name('pointOfferView');

    Route::post('setting/account', [StoreController::class, 'editProfile'])->middleware('web', 'auth:store')->name('editStoreProfile');
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
Route::view('/store/dashboard', 'content.dashboard.dashboards-store')->name('storeDashboardView');

// layout
Route::get('/layouts/without-menu', $controller_path . '\layouts\WithoutMenu@index')->name('layouts-without-menu');
Route::get('/layouts/without-navbar', $controller_path . '\layouts\WithoutNavbar@index')->name('layouts-without-navbar');
Route::get('/layouts/fluid', $controller_path . '\layouts\Fluid@index')->name('layouts-fluid');
Route::get('/layouts/container', $controller_path . '\layouts\Container@index')->name('layouts-container');
Route::get('/layouts/blank', $controller_path . '\layouts\Blank@index')->name('layouts-blank');

// pages
Route::get('/pages/account-settings-account', $controller_path . '\pages\AccountSettingsAccount@index')->name('pages-account-settings-account');
Route::get('/pages/account-settings-notifications', $controller_path . '\pages\AccountSettingsNotifications@index')->name('pages-account-settings-notifications');
Route::get('/pages/account-settings-connections', $controller_path . '\pages\AccountSettingsConnections@index')->name('pages-account-settings-connections');
Route::get('/pages/misc-error', $controller_path . '\pages\MiscError@index')->name('pages-misc-error');
Route::get('/pages/misc-under-maintenance', $controller_path . '\pages\MiscUnderMaintenance@index')->name('pages-misc-under-maintenance');

// authentication
Route::get('/auth/login-basic', $controller_path . '\authentications\LoginBasic@index')->name('auth-login-basic');
Route::get('/auth/register-basic', $controller_path . '\authentications\RegisterBasic@index')->name('auth-register-basic');
Route::get('/auth/forgot-password-basic', $controller_path . '\authentications\ForgotPasswordBasic@index')->name('auth-reset-password-basic');

// cards
Route::get('/cards/basic', $controller_path . '\cards\CardBasic@index')->name('cards-basic');

// User Interface
Route::get('/ui/accordion', $controller_path . '\user_interface\Accordion@index')->name('ui-accordion');
Route::get('/ui/alerts', $controller_path . '\user_interface\Alerts@index')->name('ui-alerts');
Route::get('/ui/badges', $controller_path . '\user_interface\Badges@index')->name('ui-badges');
Route::get('/ui/buttons', $controller_path . '\user_interface\Buttons@index')->name('ui-buttons');
Route::get('/ui/carousel', $controller_path . '\user_interface\Carousel@index')->name('ui-carousel');
Route::get('/ui/collapse', $controller_path . '\user_interface\Collapse@index')->name('ui-collapse');
Route::get('/ui/dropdowns', $controller_path . '\user_interface\Dropdowns@index')->name('ui-dropdowns');
Route::get('/ui/footer', $controller_path . '\user_interface\Footer@index')->name('ui-footer');
Route::get('/ui/list-groups', $controller_path . '\user_interface\ListGroups@index')->name('ui-list-groups');
Route::get('/ui/modals', $controller_path . '\user_interface\Modals@index')->name('ui-modals');
Route::get('/ui/navbar', $controller_path . '\user_interface\Navbar@index')->name('ui-navbar');
Route::get('/ui/offcanvas', $controller_path . '\user_interface\Offcanvas@index')->name('ui-offcanvas');
Route::get('/ui/pagination-breadcrumbs', $controller_path . '\user_interface\PaginationBreadcrumbs@index')->name('ui-pagination-breadcrumbs');
Route::get('/ui/progress', $controller_path . '\user_interface\Progress@index')->name('ui-progress');
Route::get('/ui/spinners', $controller_path . '\user_interface\Spinners@index')->name('ui-spinners');
Route::get('/ui/tabs-pills', $controller_path . '\user_interface\TabsPills@index')->name('ui-tabs-pills');
Route::get('/ui/toasts', $controller_path . '\user_interface\Toasts@index')->name('ui-toasts');
Route::get('/ui/tooltips-popovers', $controller_path . '\user_interface\TooltipsPopovers@index')->name('ui-tooltips-popovers');
Route::get('/ui/typography', $controller_path . '\user_interface\Typography@index')->name('ui-typography');

// extended ui
Route::get('/extended/ui-perfect-scrollbar', $controller_path . '\extended_ui\PerfectScrollbar@index')->name('extended-ui-perfect-scrollbar');
Route::get('/extended/ui-text-divider', $controller_path . '\extended_ui\TextDivider@index')->name('extended-ui-text-divider');

// icons
Route::get('/icons/boxicons', $controller_path . '\icons\Boxicons@index')->name('icons-boxicons');

// form elements
Route::get('/forms/basic-inputs', $controller_path . '\form_elements\BasicInput@index')->name('forms-basic-inputs');
Route::get('/forms/input-groups', $controller_path . '\form_elements\InputGroups@index')->name('forms-input-groups');

// form layouts
Route::get('/form/layouts-vertical', $controller_path . '\form_layouts\VerticalForm@index')->name('form-layouts-vertical');
Route::get('/form/layouts-horizontal', $controller_path . '\form_layouts\HorizontalForm@index')->name('form-layouts-horizontal');

// tables
Route::get('/tables/basic', $controller_path . '\tables\Basic@index')->name('tables-basic');
