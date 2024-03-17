<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\User;
use App\Models\UserAbsences;
use App\Services\AbsenceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToHome() {

        $user = User::findOrFail(1);

        try {

            $daysOfWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

            // Get the current year and month
            $year = date('Y');
            $month = date('n');

            // Get the total number of days in the current month
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            $firstDayOfMonth = Carbon::create($year, $month, 1);

            // (0 for Sunday, 1 for Monday, ..., 6 for Saturday)
            $startDayOfWeek = $firstDayOfMonth->dayOfWeek;

            // If the starting day is Sunday (0), adjust it to Monday (6)
            if ($startDayOfWeek === 0) {
                $startDayOfWeek = 6;
            } else {
                // For other days, subtract 1 to shift the starting day to Monday
                $startDayOfWeek -= 1;
            }

            $calendar = [];

            // Add empty cells for the days before the first day of the month
            for ($i = 0; $i < $startDayOfWeek; $i++) {
                $calendar[] = null;
            }

            // Add the days of the month to the calendar
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $calendar[] = $day;
            }

            $absenceTypes = Absence::all()->pluck('name', 'type')->toArray();

            $userAbsences = (new AbsenceService)->getUserAbsencesDataForCalendar($user->id);

            $data = [];
            foreach($calendar as $key => $day) {
                $data[$key] = [
                    'day'      => $day,
                    'absence'  => null,
                    'archived' => 0
                ];
            }

            foreach($userAbsences as $day => $absence) {
                $key = array_search($day, $calendar); // $key = 2;
                $data[$key] = [
                    'day'      => $day,
                    'absence'  => $absence['type'],
                    'archived' => $absence['archived']
                ];
            }

            // Chunk the data(calendar) array into weeks
            $weeks = array_chunk($data, 7);

            return view('index', [
                'daysOfWeek'   => $daysOfWeek,
                'weeks'        => $weeks,
                'absenceTypes' => $absenceTypes,
                'userAbsences' => $userAbsences,
            ]);

        } catch (\Exception $e) {
            Log::alert(__METHOD__ . ' - ' . $e->getMessage() . ' - '. $e->getFile() . ' - ' . $e->getLine());

            return view('index', [
                'daysOfWeek'   => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                'weeks'        => [],
                'absenceTypes' => [],
                'userAbsences' => [],
            ]);
        }
    }

}
