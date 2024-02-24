<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts
        = [
            'balance' => 'float',
        ];

    protected $fillable
        = [
            'name',
            'description',
            'user_id',
        ];

    public function test()
    {

    }
}
