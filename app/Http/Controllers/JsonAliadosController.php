<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JsonAliadosController extends Controller
{
	
    public function show(Request $request)
    {
        return \App\Aliados::find($request->input('aliado'));
    }

	
	public function total(Request $request)
    {
        return \App\Carteras::where('estudios_id', $request->input('estudio') )
							->where('comprado_por', 'CK')
							->sum('saldo_negociado');
    }
	
	
	public function compraCk(Request $request)
    {
         return \App\Carteras::where('estudios_id', $request->input('estudio') )
							->where('comprado_por', 'CK')
							->sum('cuota');
    }
	
	
	public function getFactor(Request $request)
    {
		$aliado 	= $request->input('aliado');
		$plazo 		= $request->input('plazo');
		$pagad 		= $request->input('pagaduria');
		$fechaNto 	= $request->input('fechaNto');
		$tasa 		= $request->input('tasa');
		
		$cumpleanos = new \DateTime($fechaNto . '00:00:00');
		$hoy = \Carbon\Carbon::now();
		$annos = $hoy->diff($cumpleanos);
		$edad = $annos->y;
		
		// Aliado	-Pagaduria	-Edad_min	-Edad_max	-Tasa
		$factor = \App\Factores::where('aliados_id', $aliado)
								->where('pagadurias_id', $pagad)
								->where('edad_min', '>=', $edad)
								->where('edad_max', '<=', $edad)
								->where('tasa', $tasa)
								->where('plazo', $plazo)
								->first();
								
		if($factor == null)
		{
			// Aliado	-Pagaduria	-Edad_min	-			-Tasa
			$factor = \App\Factores::where('aliados_id', $aliado)
								->where('pagadurias_id', $pagad)
								->where('edad_min', '>=', $edad)
								->where('tasa', $tasa)
								->where('plazo', $plazo)
								->first();
			if($factor == null)
			{
				// Aliado	-			-Edad_min	-Edad_max	-Tasa
				$factor = \App\Factores::where('aliados_id', $aliado)
									->where('edad_min', '>=', $edad)
									->where('edad_max', '<=', $edad)
									->where('tasa', $tasa)
									->where('plazo', $plazo)
									->first();
				if($factor == null)
				{
					// Aliado	-			-Edad_min	-			-Tasa
					$factor = \App\Factores::where('aliados_id', $aliado)
										->where('edad_min', '>=', $edad)
										->where('tasa', $tasa)
										->where('plazo', $plazo)
										->first();
					if($factor == null)
					{
						// Aliado	-Pagaduria	-			-			-Tasa
						$factor = \App\Factores::where('aliados_id', $aliado)
											->where('pagadurias_id', $pagad)
											->where('tasa', $tasa)
											->where('plazo', $plazo)
											->first();
						if($factor == null)
						{
							// Aliado	-Pagaduria	-Edad_min	-Edad_max	-
							$factor = \App\Factores::where('aliados_id', $aliado)
												->where('pagadurias_id', $pagad)
												->where('edad_min', '>=', $edad)
												->where('edad_max', '<=', $edad)
												->where('plazo', $plazo)
												->first();
							if($factor == null)
							{
								// Aliado	-Pagaduria	-Edad_min	-			-
								$factor = \App\Factores::where('aliados_id', $aliado)
													->where('pagadurias_id', $pagad)
													->where('edad_min', '>=', $edad)
													->where('plazo', $plazo)
													->first();
								if($factor == null)
								{
									// Aliado	-			-		-Tasa
									$factor = \App\Factores::where('aliados_id', $aliado)
														->where('tasa', $tasa)
														->where('plazo', $plazo)
														->first();
									if($factor == null)
									{
										// Aliado	-			-Edad_min	-Edad_max	-
										$factor = \App\Factores::where('aliados_id', $aliado)
															->where('edad_min', '>=', $edad)
															->where('edad_max', '<=', $edad)
															->where('plazo', $plazo)
															->first();
										if($factor == null)
										{
											// Aliado	-			-Edad_min	-			-
											$factor = \App\Factores::where('aliados_id', $aliado)
																->where('edad_min', '>=', $edad)
																->where('plazo', $plazo)
																->first();
											if($factor == null)
											{
												// Aliado	-Pagaduria	-		-
												$factor = \App\Factores::where('aliados_id', $aliado)
																	->where('pagadurias_id', $pagad)
																	->where('plazo', $plazo)
																	->first();
												if($factor == null)
												{												
													// echo 'nulo';
													return null;
												}else
												{
													// echo '-1';
													return $factor;
												}
											}else
											{
												// echo '0';
												return $factor;
											}
										}else
										{
											// echo '1';
											return $factor;
										}
									}else
									{
										// echo '2';
										return $factor;
									}
									
								}else
								{
									// echo '3';
									return $factor;
								}
								
							}else
							{
								// echo '4';
								return $factor;
							}
							
						}else
						{
							// echo '5';
							return $factor;
						}
						
					}else
					{
						// echo '6';
						return $factor;
					}
					
				}else
				{
					// echo '7';
					return $factor;
				}
				
			}else
			{
				// echo '8';
				return $factor;
			}
		}else
		{
			// echo '9';
			return $factor;
		}		
    }
}
