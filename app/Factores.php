<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factores extends Model
{
    use SoftDeletes;
    
	protected $table = 'factoresaliados';
	
	
	public function aliado()
	{
		return $this->hasOne('\App\Aliados', 'id', 'aliados_id');
	}
	
	public function pagaduria()
	{
		return $this->hasOne('\App\Pagadurias', 'id', 'pagadurias_id');
	}
	
}
