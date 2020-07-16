<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingsModel extends Model
{
    protected $table = 'trainings';
    protected $fillable = [
        'org_id',
        'training_name',
        'start_date',
        'end_date'
    ];
}
