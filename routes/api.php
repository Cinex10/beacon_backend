<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\OfferController;
use App\Models\Annonce;
use App\Models\Beacon;
use App\Models\PointsOffer;
use App\Models\Store;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('user/register', [RegisterController::class, 'createUser']);
Route::post('user/login', [LoginController::class, 'loginUser']);
Route::post('user/logout', [LoginController::class, 'logoutUser'])->middleware(['auth:sanctum']);
Route::post('user/reset', [LoginController::class, 'resetPwdUser']);
Route::get('annonce/all', function () {
    return Annonce::all();
})->middleware('auth:sanctum');
Route::get('store/all', function () {
    return Store::where('status', "active")->get();
})->middleware('auth:sanctum');


Route::group(
    ['middleware' => ['auth:sanctum'], 'prefix' => 'voucher'],
    function () {

        Route::post('take', [OfferController::class, 'takeVoucher']);
        Route::post('use', [OfferController::class, 'useVoucher']);
    }
);

Route::group(
    ['middleware' => ['auth:sanctum'], 'prefix' => 'offers'],
    function () {
        Route::get('me', [OfferController::class, 'myOffers']);
        Route::get('used', [OfferController::class, 'usedOffers']);
    }
);


Route::get('user/records', [OfferController::class, 'getPointsRecord']);


Route::get('user', function () {
    $id =  Auth::guard('user')->user()->id;
    $user = User::find($id);
    $user['total_vouchers'] = $user->vouchers()->where('user_voucher.status', 'saved')->count();
    return $user;
})->middleware(['auth:sanctum']);


Route::group(
    ['middleware' => ['auth:sanctum'], 'prefix' => 'poffers'],
    function () {

        Route::post('take', [OfferController::class, 'takePointOffre']);
        Route::post('use', [OfferController::class, 'usePointOffre']);
    }
);


Route::post('alpha', function (Request $request) {

    return Auth::guard('user')->user()->id;

    $currentDateTime = date('Y-m-d\TH:m:s.000000\Z');

    return $currentDateTime;
    // $input = $request->all();
    // $store = Store::where('storecode', $input['storecode'])->first();
    // $passcode = $store->get()->makeVisible(['passcode'])->first()->passcode;
    // return $passcode;
})->middleware(['auth:user']);


Route::get('voucher/all', function () {
    // return Voucher::with('store')->get();
    $voucher = Voucher::find(1);
    $user = User::find(1);
    if ($voucher->users->contains($user))
        return 'existed';
    $voucher->users()->attach($user);
    return User::with('vouchers')->get();
    // return Voucher::with('users')->get();
});


Route::get('bc', function () {
    $idUser = 3;
    $user = User::find($idUser);

    $response = [
        'available_vouchers' => $user->vouchers()
            ->where('user_voucher.status', 'saved')
            ->with('store:id,name')->get(),
        // 'available_pointoffer' => $user->pointoffers()->get(),
    ];

    return response($response, 200);
});
Route::get('beacon/all', function () {
    return Beacon::with('availableVouchers.store:id,name', 'availablePointoffer.store:id,name')->get();
})->middleware('auth:sanctum');
//TODO ->middleware('auth:user');
// select * from `beacons` where exists (select * from `vouchers` where 
// `beacons`.`id` = `vouchers`.`idBeacon` and exists (select * from `users` 
// inner join `user_voucher` on `users`.`id` = `user_voucher`.`idUser` where 
// `vouchers`.`id` = `user_voucher`.`idVoucher` and `idser` != 1) and `status` = available))