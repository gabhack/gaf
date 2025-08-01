<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bases extends Model
{
    use SoftDeletes;
    
	protected $table = 'bases';
	
	public function cliente()
	{
		return $this->hasOne('\App\Clientes', 'id', 'clientes_id');
	}
	
	public function pagaduria()
	{
		return $this->hasOne('\App\Pagadurias', 'id', 'pagadurias_id');
	}
}
