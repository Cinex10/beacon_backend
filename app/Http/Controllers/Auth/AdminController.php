<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Annonce;
use App\Models\Store;
use App\Models\User;


class AdminController extends Controller
{
    public function adminDashboardView()
    {
        $config = file_get_contents(base_path('storage/systemConfig.json'));
        $configData = json_decode($config);
        $counstStores = Store::all()->count();
        return view('content.admin.dashboards-admin', ['configData' => $configData, 'countStores' => $counstStores]);
    }

    public function storesView()
    {
        return view('content.admin.stores', ['stores' => Store::all()]);
    }

    public function adminsView()
    {
        return view('content.admin.admins', ['admins' => Admin::all()]);
    }

    public function usersView()
    {
        return view('content.admin.users', ['users' => User::all()]);
    }


    public function annoncesView()
    {
        return view('content.admin.annonces', ['annonces' => Annonce::all()]);
    }
}
