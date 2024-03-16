<?php

namespace App\Services;

use App\Models\UserAbsences;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AbsenceService
{

    public function __construct(){
        //
    }

    public function getAbsencesForUser(int $user_id) {


        try {
            
            $userAbsences = UserAbsences::join('absences', 'type', 'absence_type')
                                ->where('user_id', 1)
                                ->get()->pluck('name','absence_from')
                                ->toArray();

            foreach($userAbsences as $date => $name) {

                $day = Carbon::create($date)->day;
                $userAbsences[$day] = $name;

                unset($userAbsences[$date]);

            }

            return $userAbsences;

        } catch (\Exception $e) {
            Log::alert(__METHOD__ . ' - ' . $e->getMessage() . ' - '. $e->getFile() . ' - ' . $e->getLine());
            return [];
        }
    }
}
