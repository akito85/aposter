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
                    'email'=> $rows[$i][15],
                    'homepage'=> $rows[$i][16],
                    'address' => $rows[$i][17],
                    'phone'=> $rows[$i][18],
                    'work_email' => $rows[$i][19],
                    'work_address'=> $rows[$i][20],
                    'work_phone'=> $rows[$i][21],
                    'work_fax'=> $rows[$i][22],
                    'marrital_status'=> $rows[$i][23],
                    'blood_type' => $rows[$i][24],
                    'degree' => $rows[$i][25],
                    'campus_name'=> $rows[$i][26],
                    'work_position_id'=> $rows[$i][27],
                    'work_position'=> $rows[$i][28],
                    'work_organisation'=> $rows[$i][29],
                    'no_sk'=> $rows[$i][30],
                    'tmt_sk'=> $rows[$i][31],
                    'religion'=> $rows[$i][32],
                    'bracket' => $rows[$i][33]
                ]);
            }
            else {
                break;
            }
        }

    }
}
