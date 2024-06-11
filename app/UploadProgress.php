<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UploadProgress extends Model
{
    protected $connection = 'pgsql'; 
    protected $table = 'upload_progress';

    protected $fillable = ['file_name', 'total_rows', 'processed_rows', 'status'];
}
