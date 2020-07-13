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
        $i = 6;

        do {
            $i++;

            if(!empty($rows[$i])) {
                TrxTrainingsModel::updateOrCreate([
                    'nip' => $rows[$i][2],
                    'training_name' => $rows[1][0],
                    'training_year' => $rows[2][0][-4]
                ]);

            } else {
                break;
            }
        } while ($i >= 6);
    }
}
