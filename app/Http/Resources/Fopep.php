<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;

class Fopep
{
	
	public static function base(Request $request)
	{
		ini_set('memory_limit', '-1');
		
		$file = \File::get( $request->file('basicos') );
		
		$titulos = array();
		$datos = array();
		
		foreach (explode("\n", $file) as $key => $line){
			if($key == 0 || $line == "" )
				continue;
			else
			{			
				$array[ $key ] = preg_split("/[\t]/", $line);
				/*
				$ciudad = \App\Ciudades::where('ciudad', strtoupper($array[$key][32]) )->first();
				
				$user = \App\Clientes::firstOrNew(array(
					'users_id' 	=> \Auth::user()->id,
					'ciudades_id' => $ciudad->id,
					'tipodocumento' => 'CC',
					'documento' => trim($array[$key][1]),
					'nombres' 	=> trim($array[$key][2]),
					'apellidos' 	=> trim($array[$key][2]),
					'fechanto' 	=> date( 'Y-m-d', strtotime( trim( $array[$key][6] ) ) ),
					'sexo' 		=> trim($array[$key][33]),
					'telefono'	=> trim($array[$key][35]),
					'direccion'	=> trim($array[$key][36]),
					'correo'	=> trim($array[$key][37]),				
				));
				$user->save();
				
				$datos[] = array(
					'documento' => trim($array[$key][1]),
					'nombres' 	=> trim($array[$key][2]),
					'fechanto' 	=> date( 'Y-m-d', strtotime( trim( $array[$key][6] ) ) ),
					'sexo' 		=> trim($array[$key][33]),
					'telefono'	=> trim($array[$key][35]),
					'direccion'	=> trim($array[$key][36]),
					'correo'	=> trim($array[$key][37]),
				);
				*/
			}
		}
		
		dd($array);
		
	}
	
}