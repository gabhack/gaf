<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Traits\ParsesPgTzDates;
use App\Helpers\PagaduriaHelper;

/* modelos SED / SEM */
use App\EmbargosSedCauca;
use App\EmbargosSedChoco;
use App\EmbargosSedCordoba;
use App\EmbargosSedAtlantico;
use App\EmbargosSedValle;
use App\EmbargosSedCaldas;
use App\EmbargosSedBolivar;
use App\EmbargosSemBarranquilla;
use App\EmbargosSemCali;
use App\EmbargosSemPopayan;
use App\EmbargosSemMonteria;
use App\EmbargosSemQuibdo;
/* modelo genérico */
use App\EmbargosGen;

class EmbargosController extends Controller
{
    use ParsesPgTzDates;

    private static array $modelsMap = [];
    private static array $columnMap = [];
    private static array $pagaduriasMap = [];
    private static array $pagaduriasNoSpaces = [];

    public function __construct()
    {
        /* mapa de modelos particulares */
        if (empty(self::$modelsMap)) {
            self::$modelsMap = [
                'sedcauca'        => EmbargosSedCauca::class,
                'sedchoco'        => EmbargosSedChoco::class,
                'sedcordoba'      => EmbargosSedCordoba::class,
                'semcali'         => EmbargosSemCali::class,
                'sedvalle'        => EmbargosSedValle::class,
                'sedcauca'        => EmbargosSedCauca::class,
                'sedcaldas'       => EmbargosSedCaldas::class,
                'sedbolivar'      => EmbargosSedBolivar::class,
                'sembarranquilla' => EmbargosSemBarranquilla::class,
                'sempopayan'      => EmbargosSemPopayan::class,
                'semmonteria'     => EmbargosSemMonteria::class,
                'semquibdo'       => EmbargosSemQuibdo::class,
            ];

            /* columna primaria para cada modelo */
            foreach (self::$modelsMap as $key => $cls) {
                self::$columnMap[$cls] = in_array($cls, [EmbargosSemQuibdo::class, EmbargosSedBolivar::class])
                    ? 'idemp'
                    : 'doc';
            }
        }

        /* mapa de pagadurías → id numérico (panel_pagaduria) */
        if (empty(self::$pagaduriasMap)) {
            self::$pagaduriasMap      = PagaduriaHelper::map();
            self::$pagaduriasNoSpaces = collect(self::$pagaduriasMap)
                ->mapWithKeys(fn($id, $name) => [str_replace(' ', '', mb_strtolower($name)) => $id])
                ->all();
        }
    }

    public function index(Request $request)
    {
        Log::info('EmbargosController@index start', $request->all());

        $doc            = $request->input('doc');
        $rawType        = $request->input('pagaduria');      // p.ej. EmbargosSedMagdalena
        $rawLabel       = $request->input('pagaduriaLabel'); // p.ej. SEDMAGDALENA
        $monthParam     = $request->input('month');
        $yearParam      = $request->input('year');

        $typeNorm  = trim(mb_strtolower($rawType));
        $labelNorm = trim(mb_strtolower($rawLabel));

        /* ------------- buscar id pagaduría ------------- */
        $idPagaduria = $this->getPagaduriaIdFromString($typeNorm)
            ?: $this->getPagaduriaIdFromString($labelNorm);

        Log::info('Resolved pagaduria id', ['idPagaduria' => $idPagaduria]);

        $results = [];

        /* -------- 1 ▸ modelo particular (si existe) ------- */
        $modelKey = str_replace(['embargos', 'descuentos', 'coupons'], '', $typeNorm);
        if (isset(self::$modelsMap[$modelKey])) {
            $modelClass = self::$modelsMap[$modelKey];
            $column     = self::$columnMap[$modelClass];
            Log::info('Querying specific model', ['model' => $modelClass, 'column' => $column]);

            $modelRows = $modelClass::on('pgsql')
                ->where($column, 'LIKE', "%{$doc}%")
                ->get()
                ->map
                ->getAttributes()
                ->all();

            Log::info('Specific model returned', ['count' => count($modelRows)]);
            $results = array_merge($results, $modelRows);
        } else {
            Log::info('No specific model matched', ['key' => $modelKey]);
        }

        /* -------- 2 ▸ EmbargosGen filtrando por id ------- */
        $queryGen = EmbargosGen::on('pgsql')->where('doc', 'LIKE', "%{$doc}%");

        if ($idPagaduria) {
            $queryGen->where('idpagaduria', $idPagaduria);
        } else {
            $queryGen->where(function ($q) use ($rawType, $rawLabel) {
                $q->where('pagaduria', $rawType)
                  ->orWhere('pagaduria', $rawLabel);
            });
        }

        if (is_numeric($monthParam) && is_numeric($yearParam)) {
            $month     = str_pad($monthParam, 2, '0', STR_PAD_LEFT);
            $startDate = Carbon::createFromFormat('Y-m', "{$yearParam}-{$month}")->startOfMonth();
            $endDate   = Carbon::createFromFormat('Y-m', "{$yearParam}-{$month}")->endOfMonth();
            $queryGen->whereRaw("to_date(nomina, 'YYYY-MM-DD') BETWEEN ? AND ?", [
                $startDate->toDateString(),
                $endDate->toDateString()
            ]);
        }

        $sqlGen     = $queryGen->toSql();
        $bindingsGen = $queryGen->getBindings();
        Log::info('EmbargosGen SQL', ['sql' => $sqlGen, 'bindings' => $bindingsGen]);

        $genRows = $queryGen->get()->map->getAttributes()->all();
        Log::info('EmbargosGen results', ['count' => count($genRows)]);

        $results = array_merge($results, $genRows);

        /* -------- normalizar fechas nomina -------- */
        $results = $this->normalizeNominaDates($results);

        Log::info('EmbargosController@index end', ['total' => count($results)]);

        return response()->json($results, 200);
    }

    /* ---------------- helpers ---------------- */

    private function getPagaduriaIdFromString(string $input): ?int
    {
        if (!$input) return null;
        $clean = mb_strtolower($input);
        $clean = preg_replace('/^(coupons|embargos|descuentos)/', '', $clean);

        if (isset(self::$pagaduriasMap[$clean])) {
            return self::$pagaduriasMap[$clean];
        }
        $noSpace = str_replace(' ', '', $clean);
        return self::$pagaduriasNoSpaces[$noSpace] ?? null;
    }

    private function normalizeNominaDates(array $rows): array
    {
        foreach ($rows as &$row) {
            if (isset($row['nomina']) && $row['nomina']) {
                if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $row['nomina'])) {
                    $row['nomina'] = Carbon::createFromFormat('d/m/Y', $row['nomina'])->toDateString();
                } else {
                    $row['nomina'] = Carbon::parse($row['nomina'])->toDateString();
                }
            }
        }
        return $rows;
    }
}
