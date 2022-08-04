<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    view()->composer('layouts.sections.navbar.navbar', function ($view) {
      $view->with('user', Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::guard('store')->user());
    });
  }
}
