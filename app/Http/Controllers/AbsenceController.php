<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\User;
use App\Models\UserAbsences;
use App\Models\UserAbsencesArchive;
use App\Services\AbsenceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AbsenceController extends Controller
{
    public function storeAbsence(Request $request) {

        $user = User::findOrFail(1);

        try {

            $absenceFrom = isset($request->dateFrom) ? Carbon::create($this->formatDateISO($request->dateFrom)) : Carbon::now();
            $absenceTo   = isset($request->dateTo) ? Carbon::create($this->formatDateISO($request->dateTo)) : Carbon::now();
            $absenceType = isset($request->type) ? $request->type : Absence::BOLOVANJE;
            $absenceName = Absence::where('type', $absenceType)->first()->name;

            $firstDayOfMonth = Carbon::now()->startOfMonth();
            $lastDayOfMonth  = Carbon::now()->endOfMonth();

            $countUserAbsences = UserAbsences::withoutTrashed()->where('user_id', $user->id)->where('absence_from', '>', $firstDayOfMonth)->where('absence_to', '<', $lastDayOfMonth)->sum('count');

            $countOfDays = $absenceFrom->diffInDays($absenceTo) + 1;
            $daysOfWeekendInPeriod = 0;
            $days = [];

            for($i = 1; $i <= $countOfDays; $i++) {

                $days[] = $absenceFrom->day;

                if ($absenceFrom->isWeekend()) {
                    $daysOfWeekendInPeriod++;
                }
                $absenceFrom->addDay();
            }

            $absenceFrom = $absenceFrom->subDays($countOfDays);

            if($countUserAbsences >= 7  || ($countUserAbsences + $countOfDays) > 7) {
                $response = [
                    'data' => [],
                    'status' => 200,
                    'message_type' => 'danger',
                    'message' => 'Maksimalan broj odsustva u mjesecu je 7.'
                ];

            } else if ($daysOfWeekendInPeriod > 0) {
                $response = [
                    'data' => [],
                    'status' => 200,
                    'message_type' => 'danger',
                    'message' => 'Odsustva nije moguće unijeti za dane vikenda.'
                ];

            } else {

                UserAbsences::create([
                    'user_id' => 1,
                    'absence_type' => $absenceType,
                    'absence_from' => $absenceFrom,
                    'absence_to'   => $absenceTo,
                    'count'        => $countOfDays,
                ]);

                $response = [
                    'data' => [
                        'days'  => $days,
                        'typeName' => $absenceName
                    ],
                    'status' => 200,
                    'message_type' => 'success',
                    'message' => 'Uspješno ste registrovali odsustvo.'
                ];
            }

            return response()->json($response, 200);

        } catch (\Exception $e) {
            Log::alert(__METHOD__ . ' - ' . $e->getMessage() . ' - '. $e->getFile() . ' - ' . $e->getLine());
            return  response()->json([
                'data' => [],
                'status'=> 500,
                'message_type' => 'danger',
                'message' => 'Server error'], 500);
        }
    }

    public function archiveAbsences() {

        $user = User::findOrFail(1);

        try {

            $userAbsences = (new AbsenceService)->getUserAbsencesForCurrentMonth($user->id);

            $userAbsences->each(function($absence){

                UserAbsencesArchive::create([
                    'user_id'      => $absence->user_id,
                    'absence_type' => $absence->absence_type,
                    'absence_from' => $absence->absence_from,
                    'absence_to'   => $absence->absence_to
                ]);

                $absence->update(['archived' => 1]);
                $absence->delete();

            });

            return  response()->json([
                'status' => 200,
                'message_type' => 'success',
                'message' => 'Uspješno ste arhivirali odsustva'], 200);

        } catch (\Exception $e) {
            Log::alert(__METHOD__ . ' - ' . $e->getMessage() . ' - '. $e->getFile() . ' - ' . $e->getLine());
            return  response()->json(['status'=> 500, 'message_type' => 'danger', 'message' => 'Server error'], 500);
        }
    }


    public function formatDateISO($dateString) {

        try {

            $dateFormat = 'd.m.Y';

            $carbonDate = Carbon::createFromFormat($dateFormat, $dateString);

            // Format the Carbon instance as "Y-m-d"
            return $carbonDate->format('Y-m-d');

        } catch (\Exception $e) {
            Log::alert(__METHOD__ . ' - ' . $e->getMessage() . ' - '. $e->getFile() . ' - ' . $e->getLine());
        }

    }
}
