<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EntidadesDesembolso extends Model
{
    use SoftDeletes;
    
	protected $table = 'entidades_desembolso';
	
	
}
