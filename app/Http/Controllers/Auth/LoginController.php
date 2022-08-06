<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */

    //login for web ui (used to login stores and admins)
    public function login(LoginRequest $request)
    {
        //Admin login logic

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect(route('adminDashboardView'));
        }

        //Store login logic
        if (Auth::guard('store')->attempt(['email' => $request->email, 'password' => $request->password])) {


            return redirect(route('storeDashboardView'));
        } else return 'error';
    }

    public function loginUser(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make(
            $input,
            [
                'email' => 'required',
                'password' => 'required'
            ]
        );

        if ($validator->fails()) {
            $response =  response($validator->errors(), 401);
            return $response;
        } else {
            $user = User::where('email', $input['email'])->first();
            if (!$user || !Hash::check($input['password'], $user->password)) {
                $response = response(
                    [
                        'message' => 'Wrong informations',
                    ],
                    401
                );
                return $response;
            }
            $user['total_vouchers'] = $user->vouchers()->where('user_voucher.status', 'saved')->count();
            $token = $user->createToken('iBeaconsApp')->plainTextToken;
            $response = [
                'user' => $user,
                'token' => $token,
            ];

            return response($response, 200);
        }
    }

    public function logout(Request $request)
    {
        //Admin logout logic
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect(route('login'));
        }

        //Admin logout logic
        if (Auth::guard('store')->check()) {
            Auth::guard('store')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect(route('login'));
        }
    }

    public function logoutUser(Request $request)
    {
        Auth::guard('user')->user()->tokens()->delete();
        $response = response([
            'status' => 'sucess',
            'message' => 'Logged out'
        ], 200);
        return $response;
    }

    public function resetPwdStore(Request $request)
    {
        # code...
    }

    public function resetPwdUser(Request $request)
    {
        # code...
    }
}
