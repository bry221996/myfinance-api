<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $casts = [
        'balance' => 'double',
    ];

    protected $fillable = [
        'name',
        'balance',
        'currency',
        'profile_id',
        'description',
    ];
}
