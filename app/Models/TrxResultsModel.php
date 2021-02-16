<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrxResultsModel extends Model
{
    protected $table = 'trx_results';
    protected $fillable = [
        'trx_id',
        'trx_name',
        'trx_start_date',
        'trx_end_date',
        'trx_hours',
        'trx_days',
        'organization_name',
        'trx_type',
        'trx_cost',
        'trx_cost_detail',
        'elearning',
        'type_test',
        'stx_class',
        'stx_id',
        'stx_name',
        'nip',
        'nrp_nik',
        'rank_class',
        'born',
        'birthday',
        'gender',
        'phone',
        'email',
        'office_address',
        'office_phone',
        'education_level',
        'education_desc',
        'position_level',
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
        'cert_no',
        'cert_date',
        'cert_link',
        'execution_value',
        'trainer_value'
    ];
}
