<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    protected $fillable = [
        'type',
        'name',
    ];

    const BOLOVANJE          = 0;
    const GODISNJI_ODMOR     = 1;
    const DRZAVNI_PRAZNIK    = 2;
    const VJERSKI_PRAZNIK    = 3;
    const POSLOVNO_PUTOVANJE = 4;
    const TRUDNIČNO_ODSUSTVO = 5;

}
