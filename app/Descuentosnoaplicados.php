<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Descuentosnoaplicados extends Model
{
    use SoftDeletes;
    
	protected $table = 'descuentosnoaplicados';
	
	public function cliente()
	{
		return $this->hasOne('\App\Clientes', 'id', 'clientes_id');
	}
	
	public function pagaduria()
	{
		return $this->hasOne('\App\Pagadurias', 'id', 'pagadurias_id');
	}
	
	public function tercero()
	{
		return $this->hasOne('\App\Entidades', 'id', 'entidades_id');
	}
}
