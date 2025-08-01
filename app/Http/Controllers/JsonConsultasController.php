<?php

namespace App\Http\Controllers;

use App\Clientes as Clientes;
use Illuminate\Http\Request;

class JsonConsultasController extends Controller
{
    /**
     * Trae los registros financieros del ultimo periodo presente en la BD.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getDesprendiblesXDocumento(Request $request)
    {
        try {
            
            $cliente = Clientes::where('documento', $request->documento)->first();
            if ($cliente) {
                $adicionales = array();
                $desprendibles = $cliente->registrosfinancieros->where('periodo', $cliente->registrosfinancieros->last()->periodo);
                if ($desprendibles->count() > 0) {
                    foreach ($desprendibles as $key => $desprendible) {
                        $adicionales[$desprendible->id] = $desprendible->pagaduria->pagaduria;
                    }
                    return array(
                        "desprendibles" => $desprendibles,
                        "adicionales" => $adicionales
                    );
                } else {
                    return array("error" => "No se encontró información de desprendibles de pago");
                }
            } else {
                return array("error" => "No se encontró cliente");
            }
        } catch (\Exception $e) {
            return array("error" => $e->getMessage());
        }
    }
}
