<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointsOffer extends Model
{
    use HasFactory;
    protected $fillable = [
        'idStore',
        'idBeacon',
        'idUser',
        'points',
        'description',
        'terms',
        'status',
    ];

    public function beacon()
    {
        return $this->belongsTo(Beacon::class, 'idBeacon');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'idStore',);
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'user_point_offre', 'idPointOffer', 'idUser');
    }
}
