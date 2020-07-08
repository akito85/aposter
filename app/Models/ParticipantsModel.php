<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipantsModel extends Model
{
    protected $table = 'participants';

    protected $fillable = [
        'nip',
        'name',
        'satker',
        'class',
        'status',
        'note',
        'nickname',
        'prefix_degree',
        'suffix_degree',
        'ktp',
        'npwp',
        'pob',
        'dob',
        'gender',
        'homepage',
        'address',
        'phone',
        'email',
        'work_address',
        'work_phone',
        'work_fax',
        'marrital_status',
        'campus_name',
        'work_position_id',
        'work_position',
        'work_organisation',
        'no_sk',
        'tmt_sk',
        'religion',
        'bracket'
    ];
}
