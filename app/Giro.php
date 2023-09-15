<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Giro extends Model
{
	// use SoftDeletes;
	protected $table = 'giros';

	protected $fillable = [
		"estudio_id",
		"identificacion",
		"id_beneficiario",
		"beneficiario",
		"forma_pago",
		"id_cuentabancaria",
		"referencia",
		"valor_girar",
		"id_tipogiro",
	];

	public function beneficiario()
	{
		return $this->hasOne('\App\EntidadesDesembolso', 'id', 'id_beneficiario');
	}

	public function formapago()
	{
		return $this->hasOne('\App\FormaPago', 'id', 'forma_pago');
	}

	public function tipogiro()
	{
		return $this->hasOne('\App\TipoGiro', 'id', 'forma_pago');
	}

	public function cuentabancaria()
	{
		return $this->hasOne('\App\CuentasBancarias', 'id', 'id_cuentabancaria');
	}
}
