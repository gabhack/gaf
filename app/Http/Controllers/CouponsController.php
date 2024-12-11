<?php

namespace App\Http\Controllers;

use App\CouponsSedAtlantico;
use App\CouponsSemMonteria;
use App\CouponsSedBolivar;
use App\CouponsSedCauca;
use App\CouponsSedCaldas;
use App\CouponsSedCordoba;
use App\CouponsSedChoco;
use App\CouponsSedFopep;
use App\CouponsSedMagdalena;
use App\CouponsSedValle;
use App\CouponsSemBarranquilla;
use App\CouponsSemPopayan;
use App\CouponsSemQuibdo;
use App\CouponsSemSahagun;
use App\CouponsSemCali;
use App\CouponsGen;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class CouponsController extends Controller
{

    public function showCouponsForm()
    {
        return view('Coupons.CouponsConsult');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
{
    $doc = $request->doc;
    $couponType = $request->pagaduria;     // Ejemplo: "SED HUILA"
    $pagaduriaLabel = $request->pagaduriaLabel; // Ejemplo: "SED HUILA"

    Log::info('Inicio de la consulta para CouponsGen.', [
        'doc' => $doc,
        'couponType' => $couponType,
        'pagaduriaLabel' => $pagaduriaLabel,
        'time' => now()
    ]);

    $results = [];

    try {
        // Normalizar
        $couponTypeNorm = trim(strtolower($couponType));
        $pagaduriaLabelNorm = trim(strtolower($pagaduriaLabel));

        // Separamos acrónimo y nombre
        $parts = explode(' ', $couponTypeNorm, 2);
        if (count($parts) < 2) {
            $parts = explode(' ', $pagaduriaLabelNorm, 2);
        }

        if (count($parts) < 2) {
            Log::warning('Formato de pagaduría no válido.', [
                'couponType' => $couponType,
                'pagaduriaLabel' => $pagaduriaLabel
            ]);
            return response()->json(['error' => 'Formato de pagaduría no válido.'], 400);
        }

        [$tipo, $nombrePagaduria] = $parts;

        // Consulta usando la columna 'acronimo' para el tipo (sed/sem) y 'nombre' para el departamento/entidad
        $idPagaduria = \DB::connection('pgsql')
            ->table('public.panel_pagaduria')
            ->whereRaw('LOWER(TRIM(nombre)) = ?', [$nombrePagaduria])
            ->whereRaw('LOWER(TRIM(acronimo)) = ?', [$tipo])
            ->value('id');

        if (!$idPagaduria) {
            Log::warning('Pagaduría no encontrada.', [
                'couponType' => $couponType,
                'pagaduriaLabel' => $pagaduriaLabel,
                'tipo' => $tipo,
                'nombre' => $nombrePagaduria
            ]);
            return response()->json(['error' => 'Pagaduría no encontrada.'], 404);
        }

        // Consulta en CouponsGen con el idpagaduria
        $dataGen = CouponsGen::where('doc', $doc)
            ->where('idpagaduria', $idPagaduria)
            ->get()
            ->toArray();

        Log::info('Consulta CouponsGen finalizada.', [
            'time' => now(),
            'total_records' => count($dataGen)
        ]);

        $results = array_merge($results, $dataGen);

    } catch (\Exception $e) {
        Log::error('Error en la consulta para CouponsGen.', [
            'message' => $e->getMessage(),
            'time' => now()
        ]);
        return response()->json(['error' => 'Error al ejecutar la consulta.'], 500);
    }

    Log::info('Consulta completada y datos devueltos.', ['time' => now()]);
    return response()->json($results, 200);
}







    public function getCouponsByPagaduria(Request $request)
    {
        try {
            if (!$request->has('month') || !$request->has('year') || !$request->has('pagaduria')) {
                return response()->json(['error' => 'Month, year, and pagaduria are required.'], 400);
            }
    
            $pagaduria = $request->pagaduria;
            $concept = $request->concept;
            $code = $request->code;
            $month = str_pad($request->month, 2, '0', STR_PAD_LEFT);
            $year = $request->year;
    
            $startDate = Carbon::createFromFormat('Y-m', $year . '-' . $month)->startOfMonth()->toDateString();
            $endDate = Carbon::createFromFormat('Y-m', $year . '-' . $month)->endOfMonth()->toDateString();
    
            $query = CouponsGen::query();
    
            if ($concept) {
                $query->where('concept', 'ILIKE', '%' . $concept . '%');
            }
    
            if ($code) {
                $query->where('code', 'ILIKE', '%' . $code . '%');
            }
    
            $query->where('pagaduria', 'ILIKE', '%' . $pagaduria . '%');
    
            $query->where('inicioperiodo', '<=', $endDate);
            $query->where('finperiodo', '>=', $startDate);
    
            $sql = $query->toSql();
            $bindings = $query->getBindings();
    
            Log::info('Ejecutando consulta SQL: ' . $sql, $bindings);
    
            $results = $query->get()->toArray();
    
            return response()->json($results, 200);
        } catch (\Exception $e) {
            Log::error('Error in getCouponsByPagaduria:', ['message' => $e->getMessage(), 'trace' => $e->getTrace()]);
    
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
    
    
             
    public function getIncapacidadByDoc(Request $request)
    {
        try {
            $doc = $request->doc;
            $month = $request->month;
            $year = $request->year;
    
            // Asegurar que el mes tenga 2 dígitos
            $month = str_pad($month, 2, '0', STR_PAD_LEFT);
    
            // Crear fechas de inicio y fin para el mes actual
            $startDateCurrentMonth = Carbon::createFromFormat('Y-m', $year . '-' . $month)->startOfMonth()->toDateString();
            $endDateCurrentMonth = Carbon::createFromFormat('Y-m', $year . '-' . $month)->endOfMonth()->toDateString();
    
            // Calcular el mes anterior
            $previousMonthDate = Carbon::createFromFormat('Y-m', $year . '-' . $month)->subMonth();
            $startDatePreviousMonth = $previousMonthDate->startOfMonth()->toDateString();
            $endDatePreviousMonth = $previousMonthDate->endOfMonth()->toDateString();
    
            // Consulta para el mes actual y el anterior con el concepto específico
            $count = CouponsGen::where('doc', 'LIKE', '%' . $doc . '%')
                ->where('concept', '=', 'Pago Incapacidad Comun Ambulatoria')
                ->where(function ($query) use ($startDateCurrentMonth, $endDateCurrentMonth, $startDatePreviousMonth, $endDatePreviousMonth) {
                    $query->whereBetween('inicioperiodo', [$startDatePreviousMonth, $endDateCurrentMonth])
                          ->orWhereBetween('finperiodo', [$startDatePreviousMonth, $endDateCurrentMonth]);
                })->count();
    
            // Verificar si la incapacidad dura dos meses o más
            $response = $count > 0 ? "Sí" : "No";
    
            return response()->json(['incapacidad_dura_dos_meses_o_mas' => $response], 200);
        } catch (\Exception $e) {
            Log::error('Error in getIncapacidadByDoc:', ['message' => $e->getMessage(), 'trace' => $e->getTrace()]);
    
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
    
    
}
