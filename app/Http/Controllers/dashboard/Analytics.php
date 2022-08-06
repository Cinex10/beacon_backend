<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Analytics extends Controller
{
  public function index()
  {

    if (Auth::guard('admin')->check()) {


      return view('content.admin.dashboards-admin');
    }
    if (Auth::guard('store')->check()) {

      return view('content.dashboard.dashboards-store');
    }
  }
}
