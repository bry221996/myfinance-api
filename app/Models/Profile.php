<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $casts = [
        'balance' => 'float'
    ];

    protected $fillable  = [
        'name',
        'description',
        'balance',
        'currency',
        'user_id'
    ];
}
