<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;

class Popayan
{
	
	public static function base(Request $request)
	{
		ini_set('memory_limit', '-1');
		
		$file = \File::get( $request->file('basicos') );
		
		$titulos 	= array();
		$tmpDatos 	= array();
		$datos 		= array();
		$array 		= array();
		
		foreach (explode("\n", $file) as $key => $line){
			if($key == 0)
			{
				$r = preg_replace(
								  '/
									^
									[\pZ\p{Cc}\x{feff}]+
									|
									[\pZ\p{Cc}\x{feff}]+$
								   /ux',
								  '', 
								  $line);
				$titulos = preg_split("/[\t]/", $r);				
			}
			else if($line == "" )
				continue;
			else
			{
				$tmpDatos = preg_split("/[\t]/", $line);
				$i = 0;
				foreach($titulos as $k => $titulo)
				{
					$datos[trim(strtolower($titulo))] = $tmpDatos[$i];
					$datos['created_at'] = \Carbon\Carbon::now();
					$datos['updated_at'] = \Carbon\Carbon::now();
					$i++;
				}
				
				$array[] = $datos;				
			}
			
			 if($key % 1500 == 0 && count($array) != 0)
			{				
				$plano = \DB::table('tmp_base_secretarias')->insert($array);
				$array = array();
			}
		}
		
		$plano = \DB::table('tmp_base_secretarias')->insert($array);
	}
	
}