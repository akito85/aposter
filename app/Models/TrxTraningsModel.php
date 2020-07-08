<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrxTraningsModel extends Model
{
    protected $table = 'trx_trainings';
    protected $fillable = [
        'nip',
        'training_name',
        'training_year'
    ];
}
