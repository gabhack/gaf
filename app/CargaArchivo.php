<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CargaArchivo extends Model
{
    use SoftDeletes;
    
	protected $table = 'carga_archivo';
}
