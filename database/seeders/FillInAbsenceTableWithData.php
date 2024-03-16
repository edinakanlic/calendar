<?php

namespace Database\Seeders;

use App\Models\Absence;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class FillInAbsenceTableWithData extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $absences = [
            '0' => ['type' => Absence::BOLOVANJE, 'name' => 'Bolovanje'],
            '1' => ['type' => Absence::GODISNJI_ODMOR, 'name' => 'Godišnji odmor'],
            '2' => ['type' => Absence::DRZAVNI_PRAZNIK, 'name' => 'Državni praznik'],
            '3' => ['type' => Absence::VJERSKI_PRAZNIK, 'name' => 'Vjerski praznik'],
            '4' => ['type' => Absence::POSLOVNO_PUTOVANJE, 'name' => 'Poslovno putovanje'],
            '5' => ['type' => Absence::TRUDNIČNO_ODSUSTVO, 'name' => 'Trudničko odsustvo'],
        ];

        foreach($absences as $absence) {
            Absence::create($absence);
        }

    }
}
