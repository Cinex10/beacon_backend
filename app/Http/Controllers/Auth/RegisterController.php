<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Store;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function createUser(Request $request)
    {
        // $request->validate([
        //     'name' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'unique:stores'],
        //     'password' => ['required', 'confirmed', Rules\Password::defaults()],
        // ]);
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|email',
            'gender' => 'required|in:male,female',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            $response = Response($validator->errors(), 401);
            return $response;
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'gender' => $request->gender,
                'points' => 0,
                'password' => Hash::make($request->password),
            ]);

            event(new Registered($user));
            $token = $user->createToken('iBeaconsApp')->plainTextToken;
            $user["total_vouchers"] = 0;
            $response = [
                'user' => $user,
                'token' => $token,
            ];
            return Response($response, 201);
        }

        return redirect(RouteServiceProvider::HOME);
    }
    public function createStore(Request $request)
    {


        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'passcode' => ['required', 'string',],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'unique:stores', 'unique:admins'],
            'password' => ['required',],
        ]);


        // return $request;

        $config = file_get_contents(base_path('storage/systemConfig.json'));
        $configData = json_decode($config);
        $points = $configData->pointsPerStore;

        $store = Store::create([
            'name' => $request->name,
            'points' => $points,
            'address' => $request->address,
            'passcode' => "0000",
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($store));

        // return 'home';
        return redirect(route('stores'));
    }
    public function createAdmin(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'unique:stores'],
            'password' => ['required', Rules\Password::defaults()],
        ]);



        $store = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($store));

        return redirect(route('admins'));
    }
}
