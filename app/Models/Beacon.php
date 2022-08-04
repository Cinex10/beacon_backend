<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Beacon extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */





    protected $fillable = [
        'idStore',
        'identifier',
        'proximityUUID',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    // protected $hidden = [
    //     'password',
    //     'remember_token',
    // ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'idStore');
    }

    public function vouchers()
    {
        return $this->hasMany(Voucher::class, 'idBeacon');
    }
    public function availableVouchers()
    {
        return $this->hasMany(Voucher::class, 'idBeacon')->where('status', 'available')->whereDoesntHave('users', function ($query) {
            $query->where(
                'idUser',
                '=',
                auth()->user()->id,
            );
        });
    }

    public function pointoffer()
    {
        return $this->hasMany(PointsOffer::class, 'idBeacon');
    }

    public function availablePointoffer()
    {
        return $this->hasMany(PointsOffer::class, 'idBeacon')->where('status', 'available');
    }
}
