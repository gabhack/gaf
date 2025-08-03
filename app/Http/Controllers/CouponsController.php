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
use App\Helpers\PagaduriaHelper;


class CouponsController extends Controller
{

    // CouponsController (o donde tengas el mapa)
private static array $pagaduriasMap      = [];  // nombre exacto  → id
private static array $pagaduriasNoSpaces = [];  // nombre-sin-esp → id

public function __construct()
{
    if (empty(self::$pagaduriasMap)) {
        self::$pagaduriasMap = PagaduriaHelper::map();            // nombre → id
        // índice auxiliar: “sedhuila” → id
        self::$pagaduriasNoSpaces = collect(self::$pagaduriasMap)
            ->mapWithKeys(fn($id, $name) =>
                [str_replace(' ', '', mb_strtolower($name)) => $id]
            )
            ->all();
    }
}


    private static function loadPagadurias(): void
{
    if (empty(self::$pagaduriasMap)) {
        self::$pagaduriasMap = PagaduriaHelper::map();
    }
}

    public function showCouponsForm()
    {
        return view('Coupons.CouponsConsult');
    }

    public function index(Request $request)
{
    $doc = $request->doc;
    $couponType = $request->pagaduria;       // p.ej. "casur" o "fuerzas casur"
    $pagaduriaLabel = $request->pagaduriaLabel; // p.ej. "casur", "otra casur", etc.

    Log::info('Inicio de la consulta para fast_couponsgen.', [
        'doc' => $doc,
        'couponTypeRaw' => $couponType,
        'pagaduriaLabelRaw' => $pagaduriaLabel,
        'time' => now()
    ]);

    $results = [];

    try {
        // 1. Normalizar (quitar espacios sobrantes y convertir a minúsculas)
        $couponTypeNorm = trim(strtolower($couponType));
        $pagaduriaLabelNorm = trim(strtolower($pagaduriaLabel));

        Log::debug('Parámetros normalizados.', [
            'couponTypeNorm' => $couponTypeNorm,
            'pagaduriaLabelNorm' => $pagaduriaLabelNorm
        ]);

        // 2. Intentamos generar una clave a partir de $couponType
        $idPagaduria = $this->getPagaduriaIdFromString($couponTypeNorm);

        // 3. Si no se logró encontrar en el mapa, probamos con $pagaduriaLabel
        if (!$idPagaduria && !empty($pagaduriaLabelNorm)) {
            $idPagaduria = $this->getPagaduriaIdFromString($pagaduriaLabelNorm);
        }

        // 4. Si sigue sin encontrarse la pagaduría, es un error (formato o no existe en el mapa)
        if (!$idPagaduria) {
            Log::warning('No se encontró pagaduría en el mapa. Puede ser formato inválido o falta en la configuración.', [
                'couponTypeNorm' => $couponTypeNorm,
                'pagaduriaLabelNorm' => $pagaduriaLabelNorm
            ]);
            return response()->json(['error' => 'Pagaduría no encontrada o formato inválido.'], 400);
        }

        Log::debug('ID de la pagaduría resuelto', [
            'idPagaduria' => $idPagaduria
        ]);

        // 5. Construimos la query antes de ejecutarla (para loguear)
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
private function getPagaduriaIdFromString(string $input): ?int
{
    if (!$input) return null;

    $clean = mb_strtolower($input);

    // quita prefijos “coupons”, “embargos” y “descuentos”
    $clean = preg_replace('/^(coupons|embargos|descuentos)/', '', $clean);

    /* 1 ▸ intento directo (con espacio) */
    if (isset(self::$pagaduriasMap[$clean])) {
        return self::$pagaduriasMap[$clean];
    }

    /* 2 ▸ intento sin espacios */
    $noSpace = str_replace(' ', '', $clean);
    if (isset(self::$pagaduriasNoSpaces[$noSpace])) {
        return self::$pagaduriasNoSpaces[$noSpace];
    }

    /* 3 ▸ nada encontrado */
    return null;
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

            $query = \DB::connection('pgsql')->table('fast_couponsgen_visado');

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
            Log::error('Error in getCouponsByPagaduria:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ]);

            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }


/**
 * Paginación ligera de “Cartera al Día” sin COUNT(*) para evitar timeouts.
 */

 public function getFastCouponsByPagaduria(Request $request)
{
    $request->validate([
        'pagaduria' => 'required|string',
        'month'     => 'required|integer|min:1|max:12',
        'year'      => 'required|digits:4',
        'concept'   => 'nullable|string',
        'code'      => 'nullable|string',
        'perPage'   => 'nullable|integer|min:1',
        'page'      => 'nullable|integer|min:1',
    ]);

    $pagaduriaName = trim($request->input('pagaduria'));
    $month         = str_pad($request->input('month'), 2, '0', STR_PAD_LEFT);
    $year          = $request->input('year');
    $concept       = $request->input('concept');
    $code          = $request->input('code');
    $perPage       = (int) $request->input('perPage', 50);
    $page          = (int) $request->input('page', 1);

    $panel = \DB::connection('pgsql')
                ->table('panel_pagaduria')
                ->where('nombre', 'ILIKE', $pagaduriaName)
                ->first();

    if (! $panel) {
        return response()->json(['error' => "Pagaduría '{$pagaduriaName}' no encontrada."], 404);
    }

    $start = Carbon::createFromFormat('Y-m', "{$year}-{$month}")->startOfMonth();
    $end   = Carbon::createFromFormat('Y-m', "{$year}-{$month}")->endOfMonth();

    $query = \DB::connection('pgsql')
        ->table('fast_couponsgen_visado')
        ->where('idpagaduria', $panel->id)
        ->where('inicioperiodo', '<=', $end)
        ->where('finperiodo', '>=', $start);

    if ($concept) {
        $query->where('concept', 'ILIKE', "%{$concept}%");
    }

    if ($code) {
        $query->where('code', 'ILIKE', "%{$code}%");
    }

    $items = $query->orderBy('doc')->forPage($page, $perPage)->get();
    $total = $query->count();

    return response()->json([
        'data'  => $items,
        'total' => $total,
    ], 200);
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