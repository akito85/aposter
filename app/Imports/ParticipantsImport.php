<?php

namespace App\Imports;

use App\Models\ParticipantsModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ParticipantsImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $rows = $collection;

        for($i = 6; $i <= count($rows) - 4; $i++) {
            if(!empty($rows[$i])) {
                ParticipantsModel::updateOrCreate([
                    'nip' => $rows[$i][2],
                    'name' => $rows[$i][1],
                    'satker'=> $rows[$i][3],
                    'class'=> $rows[$i][4],
                    'status'=> $rows[$i][5],
                    'note' => $rows[$i][6],
                    'nickname'=> $rows[$i][7],
                    'prefix_degree' => $rows[$i][8],
                    'suffix_degree'=> $rows[$i][9],
                    'ktp' => $rows[$i][10],
                    'npwp'=> $rows[$i][11],
                    'pob'=> $rows[$i][12],
                    'dob' => $rows[$i][13],
                    'gender'=> $rows[$i][14],
                    'homepage'=> $rows[$i][15],
                    'address'=> $rows[$i][16],
                    'phone' => $rows[$i][17],
                    'email'=> $rows[$i][18],
                    'work_address'=> $rows[$i][19],
                    'work_phone'=> $rows[$i][20],
                    'work_fax'=> $rows[$i][21],
                    'marrital_status'=> $rows[$i][22],
                    'campus_name'=> $rows[$i][23],
                    'work_position_id'=> $rows[$i][24],
                    'work_position'=> $rows[$i][25],
                    'work_organisation'=> $rows[$i][26],
                    'no_sk'=> $rows[$i][27],
                    'tmt_sk'=> $rows[$i][28],
                    'religion'=> $rows[$i][29],
                    'bracket' => $rows[$i][30]
                ]);
            }
            else {
                break;
            }
        }

    }
}
