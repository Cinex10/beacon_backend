<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'points',
        'gender',
        'password',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
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

    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class, 'user_voucher', 'idUser', 'idVoucher')->withPivot('status', 'ref', 'created_at', 'updated_at')->withTimestamps();
    }

    public function pointoffers()
    {
        return $this->belongsToMany(PointsOffer::class, 'user_point_offre', 'idUser', 'idPointOffer');
    }
    public function pointRecord()
    {
        return $this->hasMany(UserPointRecord::class, 'idUser');
    }
}
