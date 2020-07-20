<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrxResultsModel extends Model
{
    protected $table = 'trx_results';
    protected $fillable = [
        'training_id',
        'training_name',
        'start',
        'end',
        'hours',
        'days',
        'organization_name',
        'type',
        'cost',
        'cost_detail',
        'elearning',
        'type_test',
        'class',
        'student_id',
        'name',
        'nip',
        'nrp_nik',
        'rank_class',
        'born',
        'birthday',
        'gender',
        'phone',
        'email',
        'office_address',
        'education',
        'education_desc',
        'position',
        'position_desc',
        'married',
        'religion',
        'main_unit',
        'eselon2',
        'eselon3',
        'eselon4',
        'satker',
        'test_result',
        'graduate_status',
        'activity',
        'presence',
        'pre_test',
        'post_test',
        'number',
        'date',
        'execution_value',
        'trainer_value'
    ];
}
