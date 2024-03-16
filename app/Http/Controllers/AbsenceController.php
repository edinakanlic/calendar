<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\UserAbsences;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AbsenceController extends Controller
{
    public function storeAbsence(Request $request) {

        try {

            $absenceFrom = isset($request->date) ? $this->formatDateISO($request->date) : Carbon::now()->format('Y-m-d');
            $absenceTo   = isset($request->date) ? $this->formatDateISO($request->date) : Carbon::now()->format('Y-m-d');
            $absenceType = isset($request->type) ? $request->type : Absence::BOLOVANJE;
            $absenceName = Absence::where('type', $absenceType)->first()->name;

            $firstDayOfMonth = Carbon::now()->startOfMonth();
            $lastDayOfMonth  = Carbon::now()->endOfMonth();

            $countUserAbsences = UserAbsences::where('user_id', 1)->where('absence_from', '>', $firstDayOfMonth)->where('absence_from', '<', $lastDayOfMonth)->count();

            if($countUserAbsences >= 7 ) {
                $response = [
                    'data' => [],
                    'status' => 200,
                    'message_type' => 'danger',
                    'message' => 'Maksimalan broj odsustva u mjesecu je 7.'
                ];

            } else if (Carbon::create($absenceFrom)->isWeekend()) {
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
                    'absence_to'   => $absenceTo
                ]);

                $response = [
                    'data' => [
                        'dateDay'  => Carbon::create($absenceFrom)->day,
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
            return  response()->json(['status'=> 500, 'message' => 500], 200);
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
