<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProvidersModel extends Model
{
    protected $table = 'providers';

    protected $fillable = [
        'organization_id',
        'organization_name'
    ];
}
