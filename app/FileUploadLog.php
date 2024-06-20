<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileUploadLog extends Model
{
    protected $connection = 'pgsql';

    protected $table = 'file_upload_logs';

    protected $fillable = [
        'file_path', 'total_registros', 'registros_procesados', 'total_por_registrar', 'http_status'
    ];

    public $timestamps = false;
}
