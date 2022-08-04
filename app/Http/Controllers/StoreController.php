<?php

namespace App\Http\Controllers;

use App\Models\Beacon;
use App\Models\PointsOffer;
use App\Models\Store;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;


class StoreController extends Controller
{
    // public function addStore(Request $request)
    // {
    //     $request->validate(
    //         [
    //             'name' => 'required',
    //             'address' => 'required',
    //             'email' => 'required',
    //             'password' => 'required'
    //         ]
    //     );

    // }

    public function createBeacon(Request $request)
    {

        $request->validate(
            [
                'identifier' => 'required',
                'proximityUUID' => 'required',
            ]
        );

        $beacon = Beacon::create([

            'idStore' => Auth::guard('store')->user()->id,
            'identifier' => $request->identifier,
            'proximityUUID' => $request->proximityUUID,

        ]);

        event(new Registered($beacon));

        return redirect(route('beaconsView'));
    }

    public function createVoucher(Request $request)
    {
        // return $request->all();
        $request->validate(
            [
                'nbCustomers' => 'required',
                'idBeacon' => 'required',
                'description' => 'required',

                'terms' => 'required',
            ]
        );


        $beacon = Voucher::create([
            'idStore' => Auth::guard('store')->user()->id,
            'idBeacon' => $request->idBeacon,
            'description' => $request->description,
            'terms' => $request->terms,
            'nbCustomers' => $request->nbCustomers,
        ]);

        event(new Registered($beacon));

        return redirect(route('vouchersView'));
    }

    public function createPointOffre(Request $request)
    {
        $request->validate(
            [
                'points' => 'required',
                'idBeacon' => 'required',
                'description' => 'required',
                'terms' => 'required',
            ]
        );

        $store = Auth::guard('store')->user();
        //Calculate the total number of points (the past offers with the new offer)
        $totalStorePointOffers = PointsOffer::where('idStore', $store->id)->sum('points') + $request->points;

        if ($store->points >= $totalStorePointOffers) {
            $pointOffre = PointsOffer::create([
                'idStore' => $store->id,
                'idBeacon' => $request->idBeacon,
                'description' => $request->description,
                'terms' => $request->terms,
                'points' => $request->points,
            ]);
            event(new Registered($pointOffre));
        } else {
            //this store have not enough points to create this point offre
            // return response([
            //     'status' => 'Unauthorized',
            //     'message' => 'this store have not enough points to create this point offre',
            // ], 403);
        }


        return redirect(route('pointOfferView'));
    }

    public function editProfile(Request $request)
    {

        $request->validate(
            [
                'name' => 'required',
                'email' => 'required',
                'address' => 'required',
                'password' => 'required',
                'storecode' => 'required',
            ]
        );


        $id = Auth::guard('store')->user()->id;

        $store = Store::find($id);
        $store->name = $request->name;
        $store->email = $request->email;
        $store->address = $request->address;
        $store->storecode = $request->storecode;
        $store->passcode = bcrypt($request->name);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = time() . '.' . $file->getClientOriginalExtension();
            $store->image = $name;
            $store->save();


            $file->move(public_path('upload/pictures/'), $name);
            return redirect(route('storeAccountSetting'))->with('success', 'Your account is up to date');
        } else {


            return redirect(route('storeAccountSetting'))->with('fail', 'fail to upload image');
        }

        return redirect(route('storeAccountSetting'))->with('fail', 'fail to update your account');
    }
}
