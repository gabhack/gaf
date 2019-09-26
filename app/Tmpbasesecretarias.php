<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tmpbasesecretarias extends Model
{
    use SoftDeletes;
    
	protected $table = 'tmp_base_secretarias';
	
	protected $fillable = [	'numvinculacion', 
							'codempleado', 
							'empleado', 
							'fechaingresoempresa', 
							'retirofecha', 
							'tiempodeservicio', 
							'fechanacimiento', 
							'edad', 
							'esquema', 
							'codcargoempresa', 
							'cargoempresa', 
							'grado', 
							'escalafon', 
							'basico', 
							'codencargo', 
							'gradoencargo', 
							'coddependenciaencargo', 
							'nombramientofecha', 
							'nombramientonumero', 
							'posesionfecha', 
							'posesionnumero', 
							'continuidad', 
							'continuidadfecha', 
							'codcontinuidadmotivo', 
							'continuidad_motivo', 
							'fuente_recursos', 
							'nivelcontratacion', 
							'situacionlaboral', 
							'refnumvinculacion', 
							'vinculacionnovedad', 
							'codcentrocosto', 
							'centrocosto', 
							'ciudad', 
							'genero', 
							'profesion', 
							'telefono', 
							'direccion', 
							'email', 
							'cargotipo', 
							'status', 
							'niveleducdb', 
							'niveleducpp', 
							'codareaeducativa', 
							'areaeducativa', 
							'codareaeducativatecnica', 
							'areaeducativatecnica', 
							'otraareaeducativatecnica', 
							'niveldicta', 
							'dependenciatipo', 
							'codependenciapp', 
							'dependencia', 
							'codesteduc', 
							'estableducat', 
							'dane_esteduc', 
							'ubicacion'
						];
}
