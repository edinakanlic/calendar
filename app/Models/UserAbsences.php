<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAbsences extends Model
{
    protected $fillable = [
        'user_id',
        'absence_type',
        'absence_from',
        'absence_to',
    ];

}
