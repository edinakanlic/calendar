<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAbsencesArchive extends Model
{

    public $table = "user_absences_archive";
    
    protected $fillable = [
        'user_id',
        'absence_type',
        'absence_from',
        'absence_to'
    ];
}
