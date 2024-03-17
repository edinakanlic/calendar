<?php

namespace App\Services;

use App\Models\UserAbsences;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class AbsenceService
{

    public function __construct(){
        //
    }

    public function getUserAbsencesDataForCalendar(int $userId) {

        try {

            $userAbsences = UserAbsences::withTrashed()
                                ->join('absences', 'type', 'absence_type')
                                ->where('user_id', $userId)
                                ->get();

            $data = [];

            foreach($userAbsences as $absence) {

                $absenceFrom  = Carbon::create($absence->absence_from);
                $absenceTo    = Carbon::create($absence->absence_to);
                $countOfDays  = $absenceFrom->diffInDays($absenceTo) + 1;

                for($i = 1; $i <= $countOfDays; $i++) {

                    $day = $absenceFrom->day;

                    $data[$day] = [
                        'type'     => $absence->name,
                        'archived' => $absence->archived
                    ];

                    $absenceFrom->addDay();
                }

            }

            return $data;

        } catch (\Exception $e) {
            Log::alert(__METHOD__ . ' - ' . $e->getMessage() . ' - '. $e->getFile() . ' - ' . $e->getLine());
            return [];
        }
    }

    public function getUserAbsencesForCurrentMonth(int $userId) :Collection {

        try {

            $firstDayOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
            $lastDayOfMonth  = Carbon::now()->endOfMonth()->format('Y-m-d');

            $userAbsences = UserAbsences::where('user_id', $userId)->where('archived', 0)->where('absence_from', '>', $firstDayOfMonth)->where('absence_to', '<', $lastDayOfMonth)->get();

            return $userAbsences;

        } catch (\Exception $e) {
            Log::alert(__METHOD__ . ' - ' . $e->getMessage() . ' - '. $e->getFile() . ' - ' . $e->getLine());
            return new Collection();
        }
    }
}
