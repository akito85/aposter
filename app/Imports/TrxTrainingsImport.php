<?php

namespace App\Imports;

use App\Models\TrxTrainingsModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class TrxTrainingsImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $rows = $collection;

        for($i = 6; $i <= count($rows) - 4; $i++) {
            if(!empty($rows[$i])) {
                TrxTrainingsModel::updateOrCreate(
                    [
                        'nip' => $rows[$i][2],
                        'training_name' => $rows[1][0],
                        'training_year' => substr($rows[2][0], -4)
                    ],
                    [
                        'nip' => $rows[$i][2],
                        'training_name' => $rows[1][0],
                        'training_year' => substr($rows[2][0], -4)
                    ]);

            } else {
                break;
            }
        }
    }
}
