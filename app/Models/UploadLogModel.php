<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadLogModel extends Model
{
    protected $table = 'log_upload';
    protected $fillable = [
        'file_name',
        'training_name',
        'uploader_name'
    ]
}
