<?php

namespace App\Http\Controllers;

use App\Models\PointsOffer;
use App\Models\Store;
use App\Models\User;
use App\Models\UserPointRecord;
use App\Models\Voucher;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class OfferController extends Controller
{
    public function takeVoucher(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make(
            $input,
            [
                'idVoucher' => 'required',

            ]
        );

        if ($validator->fails()) {
            $response =  response($validator->errors(), 401);
            return $response;
        } else {
            $voucher = Voucher::find($input['idVoucher']);
            $idUser = Auth::guard('user')->user()->id;
            $user = User::find($idUser);

            if ($voucher) {
                if ($user) {
                    if ($voucher->status == 'available') {
                        if ($voucher->users->contains($user)) {
                            return response([
                                'status' => 'Unauthorized',
                                'message' => 'this user is already take the voucher'
                            ], 403);
                        } else {
                            $voucher->users()->attach($user, ['status' => 'saved']);
                            $voucher->nbCustomers--;
                            if ($voucher->nbCustomers == 0)
                                $voucher->status = 'consumed';
                            $voucher->save();
                            return response([
                                'status' => 'success',
                                'message' => 'the voucher is taken by user with success'
                            ], 200);
                        }
                    } else {
                        return response([
                            'status' => 'Unauthorized',
                            'message' => 'the voucher is ' . $voucher->status,
                        ], 403);
                    }
                } else {
                    return response([
                        'status' => 'Not found',
                        'message' => 'the user is not found'
                    ], 404);
                }
            } else {
                return response([
                    'status' => 'Not found',
                    'message' => 'the voucher is not found'
                ], 404);
            }
        }
    }

    public function takePointOffre(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make(
            $input,
            [
                'idPointOffre' => 'required',

            ]
        );

        if ($validator->fails()) {
            return  response($validator->errors(), 401);
        } else {
            $pointOffre = PointsOffer::find($input['idPointOffre']);
            $idUser = Auth::guard('user')->user()->id;
            $user = User::find($idUser);
            $store = $pointOffre->store;
            if (($pointOffre != null) && $store != null) {
                if ($pointOffre->status == 'available') {
                    $store->points = $store->points - $pointOffre->points;
                    $user->points = $user->points + $pointOffre->points;
                    $pointOffre->user()->attach($user);
                    $pointOffre->status = 'consumed';
                    $pointOffre->save();
                    $user->save();
                    $store->save();
                    UserPointRecord::create([
                        'idUser' => $idUser,
                        'points' => $pointOffre->points,
                        'operation' => 'collect',
                    ]);
                    return response([
                        'status' => 'success',
                        'message' => 'the point offre is taken by user with success'
                    ], 200);
                } else {
                    return response([
                        'status' => 'Unauthorized',
                        'message' => 'the point offre is ' . $pointOffre->status,
                    ], 403);
                }
            } else {
                return response([
                    'status' => 'Not found',
                    'message' => 'invalide data'
                ], 404);
            }
        }
    }

    public function useVoucher(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make(
            $input,
            [
                'idVoucher' => 'required',
                'passcode' => 'required',
            ]
        );


        if ($validator->fails()) {
            return  response($validator->errors(), 401);
        } else {
            $idUser = Auth::guard('user')->user()->id;
            $idVoucher = $input['idVoucher'];
            //TODO Get the authenticated user
            $user = User::find($idUser);
            //Get the associated voucher
            $voucher = $user->vouchers()->where('user_voucher.idVoucher', $idVoucher)->first();
            if ($voucher != null) {
                //Get the passcode of the store
                $passcode = $voucher->store()->get()->makeVisible(['passcode'])->first()->passcode;
                //Check if it's the right passcode
                if ($passcode == $input['passcode']) {
                    //Check if the voucher in saved status
                    if ($user->vouchers()->where('user_voucher.idVoucher', $idVoucher)->first()->pivot->status == 'saved') {
                        //Generate ref code for the voucher
                        //The code will be shown in last step screen while using the voucher 
                        $ref = generateRefCode();
                        //save the ref and the new status 

                        $user->vouchers()->where('user_voucher.idVoucher', $input['idVoucher'])->update(['user_voucher.status' => 'used', 'ref' => $ref,]);
                        $updated_at = $user->vouchers()->where('user_voucher.idVoucher', $input['idVoucher'])->first()->updated_at;
                        return response([
                            'status' => 'success',
                            'message' => 'this voucher is used succesfully',
                            'ref' => $ref,
                            'used_at' => $updated_at,
                        ], 200);
                    } else {
                        return response([
                            'status' => 'Unauthorized',
                            'message' => 'this voucher is already used',
                        ], 403);
                    }
                } else {
                    return response([
                        'status' => 'Unauthorized',
                        'message' => 'the passcode is wrong',
                    ], 403);
                }
            } else {
                return response([
                    'status' => 'Not found',
                    'message' => 'invalide data'
                ], 404);
            }
        }
    }
    //Redeem
    public function usePointOffre(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make(
            $input,
            [
                'points' => 'required',
                'storecode' => 'required',
                'passcode' => 'required',
            ]
        );


        if ($validator->fails()) {
            return  response($validator->errors(), 401);
        } else {
            //TODO Get the authenticated user
            $idUser = Auth::guard('user')->user()->id;
            $user = User::find($idUser);
            $requestedPoints = $input['points'];
            if ($user->points >= $requestedPoints) {
                $store = Store::where('storecode', $input['storecode'])->first();
                if ($store != null) {
                    $passcode = $store->get()->makeVisible(['passcode'])->first()->passcode;
                    //Check if it's the right passcode
                    if ($passcode == $input['passcode']) {
                        $ref = generateRefCode();
                        UserPointRecord::create([
                            'idUser' => $idUser,
                            'points' => $requestedPoints,
                            'operation' => 'withdraw',
                            'ref' => $ref,
                        ]);
                        $user->points = $user->points - $requestedPoints;
                        $user->save();
                        return response([
                            'status' => 'success',
                            'message' => 'the points is redeemed succesfully',
                            'ref' => $ref,
                        ], 200);
                    } else {
                        return response([
                            'status' => 'Unauthorized',
                            'message' => 'invalide data',
                        ], 403);
                    }
                } else {
                    return response([
                        'status' => 'Unauthorized',
                        'message' => 'invalide data',
                    ], 403);
                }
            } else {
                return response([
                    'status' => 'Unauthorized',
                    'message' => 'this user have not enough points to withdraw',
                ], 403);
            }
        }
    }

    public function myOffers()
    {

        $idUser = Auth::guard('user')->user()->id;
        $user = User::find($idUser);

        $response = [
            'available_vouchers' => $user->vouchers()->where('user_voucher.status', 'saved')->with('store:id,name')->get(),
            // 'available_pointoffer' => $user->pointoffers()->get(),
        ];

        return response($response, 200);
    }

    public function usedOffers()
    {

        $idUser = Auth::guard('user')->user()->id;
        $user = User::find($idUser);

        $response = [
            'available_vouchers' => $user->vouchers()->where('user_voucher.status', 'used')->with('store:id,name')->get(),
            // 'available_pointoffer' => $user->pointoffres()->where('user_point_offre.status', 'used')->get(),
        ];

        return response($response, 200);
    }

    public function getPointsRecord()
    {
        $idUser = Auth::guard('user')->user()->id;
        $records = DB::table('user_point_records')->select('*')->where('idUser', $idUser)->get();
        return response(
            $records,
            200
        );
    }
}

function generateRefCode()
{

    $characters = '0123456789ABCDEF';
    $randomString = '';

    for ($i = 1; $i < 12; $i++) {
        if (($i % 4) == 0) {
            $randomString .= '-';
        } else {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
    }

    return $randomString;
}
