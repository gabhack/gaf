<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registrosfinancieros extends Model
{
    use SoftDeletes;
    
	protected $table = 'registros_financieros';
	
	public function cliente()
	{
		return $this->hasOne('\App\Clientes', 'id', 'clientes_id');
	}
	
	public function pagaduria()
	{
		return $this->hasOne('\App\Pagadurias', 'id', 'pagadurias_id');
    }
    


	public function descuentosaplicados()
	{
		return $this-hasMany('App\Descuentosaplicados');
	}

	public function ingresosaplicados()
	{
		return $this-hasMany('App\Ingresosaplicados');
	}
}
