<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class JsonTerecuperamosController extends Controller
{
	/**
     * @params Request  vinculacion, ingresos, valor_aportes, asignacion_adicional, total_dctos (incluidos_aportes), 
     */
    public function calcularDecision(Request $request)
	{
		$smlv = \App\Parametros::where('llave', 'SMLV')->first();
		
		return calcularCapacidad(
					$request->input('vinculacion'), $request->input('ingresos'),
					$request->input('valor_aportes'), $request->input('asignacion_adicional'),
					$request->input('total_dctos'), $smlv->valor
				);
	}
	
	
	public function paso1(Request $request)
	{
		parse_str( $request->input('form'), $data);		
		
		\DB::BeginTransaction();
		
		// Cliente
		$cliente = \App\Clientes::find($data['cliente']);
		$cliente->tipodocumento = strtoupper($data['tipo_doc']);
		$cliente->nombres = strtoupper($data['nombres']);
		$cliente->apellidos = strtoupper($data['apellidos']);
		$cliente->sexo = strtoupper($data['sexo']);
		$cliente->fechanto = $data['fecha_nto'];
		$cliente->direccion = strtoupper($data['direccion']);
		$cliente->telefono = $data['telefono'];
		$cliente->celular = $data['celular'];
		$cliente->correo = $data['email'];
		$cliente->save();
		
		
		// Cliente Adicional
		$adicional = \App\Clientesadicionales::where('estudios_id', $data['estudio'])->first();
		if($adicional == null)
		{
			$adicional = new \App\Clientesadicionales;
			$adicional->estudios_id = $data['estudio'];
		}
		$adicional->cargos_id = $data['cargo'] == '' ? null : $data['cargo'];
		$adicional->clave = $data['clave'] == '' ? null : $data['clave'];
		$adicional->foncolpuertos = $data['foncolpuertos'] == '' ? null : $data['foncolpuertos'];
		$adicional->historialembargos = $data['historial_emabrgos'] == '' ? null : $data['historial_emabrgos'];
		$adicional->gaf = $data['gaf'] == '' ? null : $data['gaf'];
		$adicional->save();
		
		
		// Base
		$base = \App\Bases::find($data['base']);
		$base->fecha_ingreso = $data['fecha_ingreso'] == '' ? null : $data['fecha_ingreso'];
		$base->save();
		
		
		// Estudio
		$estudio = \App\Estudiostr::find($data['estudio']);
		$estudio->mes_desprendible = $data['mes_desprendible'] == '' ? null : $data['mes_desprendible'];
		$estudio->ano_desprendible = $data['ano_desprendible'] == '' ? null : $data['ano_desprendible'];
		$estudio->tiposembargos_id = $data['tipo_embargo'] == '' ? null : $data['tipo_embargo'];
		$estudio->save();
		
		
		\DB::Commit();
	}
	
	
	public function paso2(Request $request)
	{
		parse_str( $request->input('form'), $data);
		
		$capacidad = \App\Capacidades::where('estudios_id', $data['estudio'])->first();
		if($capacidad == null)
		{
			$capacidad = new \App\Capacidades;		
			$capacidad->estudios_id = $data['estudio'];
		}
		
		$capacidad->ingresos = $data['ingresos'];
		$capacidad->asignacion_adicional = $data['asignacion_adicional'];
		$capacidad->aportes = $data['valor_aportes'];
		$capacidad->descuentos = $data['total_dctos'];
		$capacidad->compra_cartera = $data['compra_cartera'];
		$capacidad->libre_inversion = $data['libre_inversion'];
		$capacidad->decision = $data['decision_capacidad'];
		$capacidad->save();
	}
	
	
	public function paso3(Request $request)
	{		
		parse_str( $request->input('form'), $data);
		
		if($data['sector'] != ''){
			if($data['cartera'] != "")
			{
				$cartera = \App\Carteras::find($data['cartera']);
			}
			else
			{
				$cartera = new \App\Carteras;
			}
			
			$cartera->sectores_id = $data['sector'];
			$cartera->estadoscarteras_id = $data['estado_cartera'];
			$cartera->entidades_id = $data['entidad'];
			$cartera->estudios_id = $data['estudio'];
			$cartera->comprado_por = $data['comprado_por'];
			$cartera->cuota = $data['cuota'];
			$cartera->saldo = $data['saldoCartera'];
			$cartera->valor_ini = $data['valor_inicial'];
			$cartera->dcto_logrado = $data['dcto_logrado'];
			$cartera->saldo_negociado = $data['saldoNegociado'];
			$cartera->fecha_vence = $data['fecha_vence'];
			$cartera->porc_negociado = $data['porc_negociado'];
			$cartera->save();			
		}
		
		$carteras = \App\Carteras::where('estudios_id', $data['estudio'])->get();
		return view("terecuperamos/carteras")->with(["carteras" => $carteras]);
	}
	
	
	public function paso4(Request $request)
	{
		parse_str( $request->input('form'), $data);
		
		$central = \App\Centrales::where('estudios_id', $data['estudio'])->first();
		if($central == null)
		{
			$central = new \App\Centrales;
			$central->estudios_id = $data['estudio'];
		}
		
		$central->aliados_id = $data['aliados'];		
		$central->calificacion_data = $data['calificacion_data'];
		$central->puntaje_data = $data['puntaje_data'];
		$central->calificacion_sifin = $data['calificacion_sifin'];
		$central->puntaje_sifin = $data['puntaje_sifin'];
		$central->save();
	}
	
	
	public function paso5(Request $request)
	{
		parse_str( $request->input('form'), $data);
		
		$condicionTr = \App\Condicionestr::where('estudios_id', $data['estudio'])->first();
		if($condicionTr == null)
		{
			$condicionTr = new \App\Condicionestr;
			$condicionTr->estudios_id = $data['estudio'];
		}
		
		$condicionTr->costoservicios = $data['costos'];
		$condicionTr->costojuridico = $data['costo_juridico'];
		$condicionTr->costocertificados = $data['valor_certificado'];
		$condicionTr->gmf = $data['gmf'];
		$condicionTr->save();
	}
	
	
	public function paso6(Request $request)
	{
		parse_str( $request->input('form'), $data);
		
		$condicionCK = \App\Condicionesck::where('estudios_id', $data['estudio'])->first();
		if($condicionCK == null)
		{
			$condicionCK = new \App\Condicionesck;
			$condicionCK->estudios_id = $data['estudio'];
		}
		
		$condicionCK->tasa 		= $data['tasa_ck'];
		$condicionCK->plazo 	= $data['plazo'];
		$condicionCK->anticipo 	= $data['anticipo'];
		$condicionCK->costos 	= $data['porc_costos'];
		$condicionCK->seguro 	= $data['porc_seguro'];
		$condicionCK->gmf 		= $data['gmf_ck'];
		$condicionCK->cuota 	= $data['cuota_ck'];
		$condicionCK->total 	= $data['total_ck'];
		$condicionCK->save();
		
		$saldo 	=  $data['total_ck'];		
		$amortizaciones = array();
		
		for ($i=0; $i < $data['plazo']; $i++)
		{
			$interes 	= $saldo * $data['tasa_ck'] / 100;
			$capital	= ceil( $data['cuota_ck'] - $interes - ($data['total_ck'] * $data['porc_seguro']) );
			$saldo 		= $saldo - $capital;
			
			$amortizaciones[] = [
				'condicionesck_id' => $condicionCK->id,
				'capital'	=> $capital,
				'seguros'	=> $data['total_ck'] * $data['porc_seguro'],
				'interes'	=> $interes,
				'cuota'		=> $data['cuota_ck'],
				'saldo'		=> $saldo < 0 ? 0 : $saldo,
				'created_at'=> \Carbon\Carbon::now(),
				'updated_at'=> \Carbon\Carbon::now(),				
			];			
		}
		
		\DB::table('amortizaciones')->where('condicionesck_id', $condicionCK->id)->delete();
		\DB::table('amortizaciones')->insert($amortizaciones);

		return \App\Amortizaciones::where('condicionesck_id', $condicionCK->id)->get()->toArray();
	}
	
	
	public function paso7(Request $request)
	{
		parse_str( $request->input('form'), $data);
		
		$condicionAF = \App\Condicionesaf::where('estudios_id', $data['estudio'])->first();
		if($condicionAF == null)
		{
			$condicionAF = new \App\Condicionesaf;
			$condicionAF->estudios_id = $data['estudio'];
		}
		
		$condicionAF->aliados_id = $data['aliado_af'];
		$condicionAF->plazo = $data['plazo_af'];
		$condicionAF->tasa = $data['tasa_af'];
		$condicionAF->factor = $data['factor'];
		$condicionAF->cuota = $data['cuotaAliado'];
		$condicionAF->valor_titulos = $data['valorTitulos'];
		$condicionAF->decision = $data['decisionAF'];
		$condicionAF->save();
	}
}
