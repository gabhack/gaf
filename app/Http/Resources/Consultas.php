<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;

class Consultas
{
	
	public static function base(Request $request)
	{
		$base = \App\Bases::with('cliente', 'pagaduria')
								->whereHas('cliente', function($q) use($request){
									$q->where('documento', $request->input('documento'));
								})
								->where('pagadurias_id', $request->input('pagaduria'))
								->OrderBy('fecha', 'DESC')
								->first();
		
		$descApli = \App\Descuentosaplicados::with('cliente', 'pagaduria')
								->whereHas('cliente', function($q) use($request){
									$q->where('documento', $request->input('documento'));
								})
								->where('pagadurias_id', $request->input('pagaduria'))
								->OrderBy('periodo', 'DESC')
								->get();
		return array(
			'base' => $base,
			'descApli' => $descApli,
		);
	}
	
    public static function basico(Request $request)
	{
		$base = self::base($request);
								
		$descNoApli = \App\Descuentosnoaplicados::with('cliente', 'pagaduria')
								->whereHas('cliente', function($q) use($request){
									$q->where('documento', $request->input('documento'));
								})
								->where('pagadurias_id', $request->input('pagaduria'))
								->count();
								
		if($base['base'] != null)
			return view('reportes/consultas/basico')->with([
							'base' => $base['base'], 
							'descApli' => $base['descApli'], 
							'descNoApli' => $descNoApli
						]);
		else
		{
			setMessage('El Cliente no existe en esta Pagadur&iacute;a', 'info');
			echo getMessage();
		}
	}
	
	
	public static function plus(Request $request)
	{
		$base = self::base($request);
		
		$descNoApli = \App\Descuentosnoaplicados::with('cliente', 'pagaduria')
								->whereHas('cliente', function($q) use($request){
									$q->where('documento', $request->input('documento'));
								})
								->where('pagadurias_id', $request->input('pagaduria'))
								->OrderBy('periodo', 'DESC')
								->get();
								
		if($base['base'] != null)
			return view('reportes/consultas/plus')->with([
							'base' => $base['base'], 
							'descApli' => $base['descApli'], 
							'descNoApli' => $descNoApli
						]);
		else
		{
			setMessage('El Cliente no existe en esta Pagadur&iacute;a', 'info');
			echo getMessage();
		}
	}
	
	
	public static function premium(Request $request)
	{
		$base = self::base($request);
		
		$descNoApli = \App\Descuentosnoaplicados::with('cliente', 'pagaduria')
								->whereHas('cliente', function($q) use($request){
									$q->where('documento', $request->input('documento'));
								})
								->where('pagadurias_id', $request->input('pagaduria'))
								->OrderBy('periodo', 'DESC')
								->get();
								
		$embargos = \App\Embargos::with('cliente', 'pagaduria')
								->whereHas('cliente', function($q) use($request){
									$q->where('documento', $request->input('documento'));
								})
								->where('pagadurias_id', $request->input('pagaduria'))
								->count();
								
		if($base['base'] != null)
			return view('reportes/consultas/premium')->with([
							'base' => $base['base'], 
							'descApli' => $base['descApli'], 
							'descNoApli' => $descNoApli,
							'embargos' => $embargos,
						]);
		else
		{
			setMessage('El Cliente no existe en esta Pagadur&iacute;a', 'info');
			echo getMessage();
		}
	}
	
	
	public static function premiumplus(Request $request)
	{
		$base = self::base($request);
		
		$descNoApli = \App\Descuentosnoaplicados::with('cliente', 'pagaduria')
								->whereHas('cliente', function($q) use($request){
									$q->where('documento', $request->input('documento'));
								})
								->where('pagadurias_id', $request->input('pagaduria'))
								->OrderBy('periodo', 'DESC')
								->get();
								
		$embargos = \App\Embargos::with('cliente', 'pagaduria')
								->whereHas('cliente', function($q) use($request){
									$q->where('documento', $request->input('documento'));
								})
								->where('pagadurias_id', $request->input('pagaduria'))
								->get();
		$otrosIng = \App\Bases::with('cliente', 'pagaduria')
								->whereHas('cliente', function($q) use($request){
									$q->where('documento', $request->input('documento'));
								})
								->where('pagadurias_id', '<>', $request->input('pagaduria'))
								->count();
								
		if($base['base'] != null)
			return view('reportes/consultas/premiumplus')->with([
							'base' => $base['base'], 
							'descApli' => $base['descApli'], 
							'descNoApli' => $descNoApli,
							'embargos' => $embargos,
							'otrosIng' => $otrosIng,
						]);
		else
		{
			setMessage('El Cliente no existe en esta Pagadur&iacute;a', 'info');
			echo getMessage();
		}
	}
	
	
	public static function elite(Request $request)
	{
		$base = self::base($request);
		
		$descNoApli = \App\Descuentosnoaplicados::with('cliente', 'pagaduria')
								->whereHas('cliente', function($q) use($request){
									$q->where('documento', $request->input('documento'));
								})
								->where('pagadurias_id', $request->input('pagaduria'))
								->OrderBy('periodo', 'DESC')
								->get();
								
		$embargos = \App\Embargos::with('cliente', 'pagaduria')
								->whereHas('cliente', function($q) use($request){
									$q->where('documento', $request->input('documento'));
								})
								->where('pagadurias_id', $request->input('pagaduria'))
								->get();

		$otrosIng = \App\Bases::with('cliente', 'pagaduria')
								->whereHas('cliente', function($q) use($request){
									$q->where('documento', $request->input('documento'));
								})
								->where('pagadurias_id', '<>', $request->input('pagaduria'))
								->OrderBy('fecha', 'DESC')
								->groupBy('id', 'pagadurias_id', 'clientes_id', 'fecha_ingreso', 'fecha_pago', 'sucursal', 'pago_neto', 'cupo_disponible', 'fondo', 'valor_pension', 'valor_pensiones', 'valor_descuentos', 'valor_salud', 'valor_embargos', 'valor_deducido', 'tipo_pension', 'fecha', 'created_at', 'updated_at', 'deleted_at')
								->get();
		
		if($base['base'] != null)
			return view('reportes/consultas/elite')->with([
							'base' => $base['base'], 
							'descApli' => $base['descApli'], 
							'descNoApli' => $descNoApli,
							'embargos' => $embargos,
							'otrosIng' => $otrosIng,
						]);
		else
		{
			setMessage('El Cliente no existe en esta Pagadur&iacute;a', 'info');
			echo getMessage();
		}
	}
	
	
	public static function elitepremium(Request $request)
	{
		$base = self::base($request);
		
		$descNoApli = \App\Descuentosnoaplicados::with('cliente', 'pagaduria')
								->whereHas('cliente', function($q) use($request){
									$q->where('documento', $request->input('documento'));
								})
								->where('pagadurias_id', $request->input('pagaduria'))
								->OrderBy('periodo', 'DESC')
								->get();
								
		$embargos = \App\Embargos::with('cliente', 'pagaduria')
								->whereHas('cliente', function($q) use($request){
									$q->where('documento', $request->input('documento'));
								})
								->where('pagadurias_id', $request->input('pagaduria'))
								->get();

		$otrosIng = \App\Bases::with('cliente', 'pagaduria')
								->whereHas('cliente', function($q) use($request){
									$q->where('documento', $request->input('documento'));
								})
								->where('pagadurias_id', '<>', $request->input('pagaduria'))
								->OrderBy('fecha', 'DESC')
								->groupBy('id', 'pagadurias_id', 'clientes_id', 'fecha_ingreso', 'fecha_pago', 'sucursal', 'pago_neto', 'cupo_disponible', 'fondo', 'valor_pension', 'valor_pensiones', 'valor_descuentos', 'valor_salud', 'valor_embargos', 'valor_deducido', 'tipo_pension', 'fecha', 'created_at', 'updated_at', 'deleted_at')
								->get();
		
		if($base['base'] != null)
			return view('reportes/consultas/elitepremium')->with([
							'base' => $base['base'], 
							'descApli' => $base['descApli'], 
							'descNoApli' => $descNoApli,
							'embargos' => $embargos,
							'otrosIng' => $otrosIng,
						]);
		else
		{
			setMessage('El Cliente no existe en esta Pagadur&iacute;a', 'info');
			echo getMessage();
		}
	}
}