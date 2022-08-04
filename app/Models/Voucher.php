<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'idStore',
        'idBeacon',
        'nbCustomers',
        'description',
        'terms',
        'status',
    ];

    // protected $hidden = [

    // ];
    /**
     * Get the beacon() owns the Voucher
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function beacon()
    {
        return $this->belongsTo(Beacon::class, 'idBeacon');
    }
    public function store()
    {
        return $this->belongsTo(Store::class, 'idStore',);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_voucher', 'idVoucher', 'idUser')->withPivot('status', 'ref', 'created_at', 'updated_at')->withTimestamps();
    }
}
