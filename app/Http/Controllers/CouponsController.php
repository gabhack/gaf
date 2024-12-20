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
        'sed antioquia' => 130,
        'sed arauca' => 109,
        'sed atlantico' => 121,
        'sed bolivar' => 5,
        'sed boyaca' => 110,
        'sed caldas' => 139,
        'sed caqueta' => 140,
        'sed casanare' => 104,
        'sed cauca' => 177,
        'sed cesar' => 11,
        'sed choco' => 12,
        'sed cordoba' => 182,
        'sed cundinamarca' => 163,
        'sed guajira' => 192,
        'sed guaviare' => 173,
        'sed huila' => 178,
        'sed magdalena' => 145,
        'sed meta' => 113,
        'sed narino' => 143,
        'sed norte de santander' => 154,
        'sed putumayo' => 184,
        'sed quindio' => 166,
        'sed risaralda' => 114,
        'sed santander' => 26,
        'sed sucre' => 175,
        'sed tolima' => 122,
        'sed valle' => 165,
        'sed vaupes' => 132,
        'sed vichada' => 32,
        'sem sincelejo' => 27,
        'sem armenia' => 34,
        'sem barrancabermeja' => 160,
        'sem barranquilla' => 106,
        'sem bello' => 111,
        'sem bucaramanga' => 39,
        'sem buenaventura' => 40,
        'sem buga' => 157,
        'sem cali' => 42,
        'sem cartagena' => 43,
        'sem cartago' => 136,
        'sem chia' => 45,
        'sem cienaga' => 103,
        'sem cucuta' => 47,
        'sem dosquebradas' => 112,
        'sem duitama' => 49,
        'sem envigado' => 115,
        'sem estrella' => 168,
        'sem facatativa' => 164,
        'sem florencia' => 55,
        'sem floridablanca' => 170,
        'sem funza' => 117,
        'sem fusagasuga' => 151,
        'sem girardot' => 179,
        'sem giron' => 61,
        'sem guainia' => 116,
        'sem ibague' => 147,
        'sem ipiales' => 134,
        'sem itagui' => 135,
        'sem jamundi' => 146,
        'sem lorica' => 67,
        'sem magangue' => 133,
        'sem maicao' => 69,
        'sem malambo' => 161,
        'sem manizales' => 174,
        'sem medellin' => 180,
        'sem monteria' => 176,
        'sem mosquera' => 153,
        'sem neiva' => 105,
        'sem palmira' => 152,
        'sem pasto' => 125,
        'sem pereira' => 78,
        'sem piedecuesta' => 79,
        'sem pitalito' => 138,
        'sem popayan' => 159,
        'sem quibdo' => 162,
        'sem riohacha' => 150,
        'sem rionegro' => 129,
        'sem sabaneta' => 108,
        'sem sahagun' => 142,
        'sem san andres' => 158,
        'sem santa marta' => 126,
        'sem soacha' => 119,
        'sem sogamoso' => 172,
        'sem soledad' => 123,
        'sem tulua' => 120,
        'sem tumaco' => 93,
        'sem tunja' => 141,
        'sem turbo' => 137,
        'sem uribia' => 144,
        'sem valledupar' => 171,
        'sem villavicencio' => 124,
        'sem yopal' => 100,
        'sem yumbo' => 169,
        'sem zipaquira' => 156
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

        Log::debug('Después de normalizar pagaduría', [
            'couponTypeNorm' => $couponTypeNorm,
            'pagaduriaLabelNorm' => $pagaduriaLabelNorm,
            'parts' => $parts
        ]);

        if (count($parts) < 2) {
            Log::warning('Formato de pagaduría no válido.', [
                'couponType' => $couponType,
                'pagaduriaLabel' => $pagaduriaLabel
            ]);
            return response()->json(['error' => 'Formato de pagaduría no válido.'], 400);
        }

        [$tipo, $nombrePagaduria] = $parts;

        $key = $tipo . ' ' . $nombrePagaduria;

        Log::debug('Clave generada para buscar en el mapa', [
            'key' => $key
        ]);

        $idPagaduria = self::$pagaduriasMap[$key] ?? null;

        Log::debug('ID Pagaduría encontrado', [
            'idPagaduria' => $idPagaduria
        ]);

        if (!$idPagaduria) {
            Log::warning('Pagaduría no encontrada en el mapa estático.', [
                'couponType' => $couponType,
                'pagaduriaLabel' => $pagaduriaLabel,
                'tipo' => $tipo,
                'nombre' => $nombrePagaduria,
                'key' => $key
            ]);
            return response()->json(['error' => 'Pagaduría no encontrada.'], 404);
        }

        // Construimos la query antes de ejecutarla para loggear
        $query = \DB::connection('pgsql')
            ->table('fast_couponsgen_visado')
            ->where('doc', $doc)
            ->where('idpagaduria', $idPagaduria);

        $sql = $query->toSql();
        $bindings = $query->getBindings();

        Log::info('Ejecutando consulta SQL en fast_couponsgen_visado', [
            'sql' => $sql,
            'bindings' => $bindings,
            'doc' => $doc,
            'idpagaduria' => $idPagaduria
        ]);

        $dataGen = $query->get()->toArray();

        Log::info('Consulta fast_couponsgen finalizada.', [
            'time' => now(),
            'total_records' => count($dataGen)
        ]);

        $results = array_merge($results, $dataGen);

    } catch (\Exception $e) {
        Log::error('Error en la consulta para fast_couponsgen.', [
            'message' => $e->getMessage(),
            'time' => now(),
            'trace' => $e->getTraceAsString()
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