<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPointRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'idUser',
        'operation',
        'points',
        'ref',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'idUser');
    }
}
