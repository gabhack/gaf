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

     /**
     * Mapa estático de pagadurías a IDs.
     * 
     * Este mapa asocia la combinación "tipo nombre" en minúsculas
     * a una idpagaduria. Por ejemplo, "sed huila" => 17.
     * 
     * CONSECUENCIAS DEL MAPA:
     * - Acelera la consulta al no tener que consultar la base de datos
     *   para obtener idpagaduria.
     * - Requiere mantenimiento manual: si se agrega, elimina o cambia una
     *   pagaduría en la BD, este mapa debe actualizarse.
     * - No es escalable a largo plazo. Es una medida temporal de optimización.
     */
    private static $pagaduriasMap = [
        'sed amazonas' => 1,
        'sed antioquia' => 2,
        'sed arauca' => 3,
        'sed atlantico' => 4,
        'sed bolivar' => 5,
        'sed boyaca' => 6,
        'sed caldas' => 7,
        'sed caqueta' => 8,
        'sed casanare' => 9,
        'sed cauca' => 10,
        'sed cesar' => 11,
        'sed choco' => 12,
        'sed cordoba' => 13,
        'sed cundinamarca' => 14,
        'sed guajira' => 15,
        'sed guaviare' => 16,
        'sed huila' => 17,   // Aquí está la pagaduría Huila
        'sed magdalena' => 18,
        'sed meta' => 19,
        'sed narino' => 20,
        'sed norte de santander' => 22,
        'sed putumayo' => 23,
        'sed quindio' => 24,
        'sed risaralda' => 25,
        'sed santander' => 26,
        'sem sincelejo' => 27, // Ejemplo SEM
        'sed sucre' => 28,
        'sed tolima' => 29,
        'sed valle' => 30,
        'sed vaupes' => 31,
        'sed vichada' => 32,
        'sem apartado' => 33,
        'sem armenia' => 34,
        'sem barrancabermeja' => 36,
        'sem barranquilla' => 37,
        'sem bello' => 38,
        'sem bucaramanga' => 39,
        'sem buenaventura' => 40,
        'sem buga' => 41,
        'sem cali' => 42,
        'sem cartagena' => 43,
        'sem cartago' => 44,
        'sem chia' => 45,
        'sem cienaga' => 46,
        'sem cucuta' => 47,
        'sem dosquebradas' => 48,
        'sem duitama' => 49,
        'sem envigado' => 52,
        'sem estrella' => 53,
        'sem facatativa' => 54,
        'sem florencia' => 55,
        'sem floridablanca' => 56,
        'sem funza' => 57,
        'sem fusagasuga' => 58,
        'sem girardot' => 60,
        'sem giron' => 61,
        'sem guainia' => 62,
        'sem ibague' => 63,
        'sem ipiales' => 64,
        'sem itagui' => 65,
        'sem jamundi' => 66,
        'sem lorica' => 67,
        'sem magangue' => 68,
        'sem maicao' => 69,
        'sem malambo' => 70,
        'sem manizales' => 71,
        'sem medellin' => 72,
        'sem monteria' => 73,
        'sem mosquera' => 74,
        'sem neiva' => 75,
        'sem palmira' => 76,
        'sem pasto' => 77,
        'sem pereira' => 78,
        'sem piedecuesta' => 79,
        'sem pitalito' => 80,
        'sem popayan' => 81,
        'sem quibdo' => 82,
        'sem riohacha' => 83,
        'sem rionegro' => 84,
        'sem sabaneta' => 85,
        'sem sahagun' => 86,
        'sem san andres' => 87,
        'sem santa marta' => 88,
        'sem soacha' => 89,
        'sem sogamoso' => 90,
        'sem soledad' => 91,
        'sem tulua' => 92,
        'sem tumaco' => 93,
        'sem tunja' => 94,
        'sem turbo' => 95,
        'sem tutlua' => 96,
        'sem uribia' => 97,
        'sem valledupar' => 98,
        'sem villavicencio' => 99,
        'sem yopal' => 100,
        'sem yumbo' => 101,
        'sem zipaquira' => 102,
        // Agregar más si es necesario...
    ];

    public function showCouponsForm()
    {
        return view('Coupons.CouponsConsult');
    }

    public function index(Request $request)
{
    $doc = $request->doc;
    $couponType = $request->pagaduria;
    $pagaduriaLabel = $request->pagaduriaLabel;

    Log::info('Inicio de la consulta para fast_couponsgen.', [
        'doc' => $doc,
        'couponType' => $couponType,
        'pagaduriaLabel' => $pagaduriaLabel,
        'time' => now()
    ]);

    $results = [];

    try {
        // Normalización
        $couponTypeNorm = trim(strtolower($couponType));
        $pagaduriaLabelNorm = trim(strtolower($pagaduriaLabel));
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

        $key = $tipo . ' ' . $nombrePagaduria;
        $idPagaduria = self::$pagaduriasMap[$key] ?? null;

        if (!$idPagaduria) {
            Log::warning('Pagaduría no encontrada en el mapa estático.', [
                'couponType' => $couponType,
                'pagaduriaLabel' => $pagaduriaLabel,
                'tipo' => $tipo,
                'nombre' => $nombrePagaduria
            ]);
            return response()->json(['error' => 'Pagaduría no encontrada.'], 404);
        }

        // Consulta en fast_couponsgen
        $dataGen = \DB::connection('pgsql')
            ->table('fast_couponsgen_visado')
            ->where('doc', $doc)
            ->where('idpagaduria', $idPagaduria)
            ->get()
            ->toArray();

        Log::info('Consulta fast_couponsgen finalizada.', [
            'time' => now(),
            'total_records' => count($dataGen)
        ]);

        $results = array_merge($results, $dataGen);

    } catch (\Exception $e) {
        Log::error('Error en la consulta para fast_couponsgen.', [
            'message' => $e->getMessage(),
            'time' => now()
        ]);
        return response()->json(['error' => 'Error al ejecutar la consulta.'], 500);
    }

    Log::info('Consulta completada y datos devueltos.', [
        'total_records' => count($results),
        'time' => now()
    ]);

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