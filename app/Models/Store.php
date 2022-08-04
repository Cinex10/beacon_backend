<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Store extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */


    protected $guard = 'store';



    protected $fillable = [
        'name',
        'email',
        'points',
        'image',
        'address',
        'storecode',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'passcode',
        'storecode',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function beacons()
    {
        return $this->hasMany(Beacon::class, 'idStore');
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class, 'idStore');
    }

    public function pointOffers()
    {
        return $this->hasMany(PointsOffer::class, 'idStore');
    }
}
