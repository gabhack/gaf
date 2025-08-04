<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Traits\ParsesPgTzDates;
use App\Helpers\PagaduriaHelper;

/* modelos SED / SEM */
use App\DescuentosSedAtlantico;
use App\DescuentosSedCauca;
use App\DescuentosSedCordoba;
use App\DescuentosSedChoco;
use App\DescuentosSedCaldas;
use App\DescuentosSedValle;
use App\DescuentosSemCali;
use App\DescuentosSemBarranquilla;
use App\DescuentosSemPopayan;
use App\DescuentosSemMonteria;
use App\DescuentosSemQuibdo;
/* modelo genérico */
use App\DescuentosGen;

class DescuentosController extends Controller
{
    use ParsesPgTzDates;

    private static array $modelsMap = [];
    private static array $columnMap = [];
    private static array $pagaduriasMap = [];
    private static array $pagaduriasNoSpaces = [];

    public function __construct()
    {
        if (empty(self::$modelsMap)) {
            self::$modelsMap = [
                'sedatlantico'     => DescuentosSedAtlantico::class,
                'sedcauca'         => DescuentosSedCauca::class,
                'sedcordoba'       => DescuentosSedCordoba::class,
                'sedchoco'         => DescuentosSedChoco::class,
                'sedcaldas'        => DescuentosSedCaldas::class,
                'sedvalle'         => DescuentosSedValle::class,
                'semcali'          => DescuentosSemCali::class,
                'sembarranquilla'  => DescuentosSemBarranquilla::class,
                'sempopayan'       => DescuentosSemPopayan::class,
                'semmonteria'      => DescuentosSemMonteria::class,
                'semquibdo'        => DescuentosSemQuibdo::class,
            ];

            foreach (self::$modelsMap as $k => $cls) {
                self::$columnMap[$cls] = $cls === DescuentosSemQuibdo::class ? 'idemp' : 'doc';
            }
        }

        if (empty(self::$pagaduriasMap)) {
            self::$pagaduriasMap      = PagaduriaHelper::map();
            self::$pagaduriasNoSpaces = collect(self::$pagaduriasMap)
                ->mapWithKeys(fn($id, $name) => [str_replace(' ', '', mb_strtolower($name)) => $id])
                ->all();
        }
    }

    public function index(Request $request)
    {
        Log::info('DescuentosController@index start', $request->all());

        $doc        = $request->input('doc');
        $rawType    = $request->input('pagaduria');
        $rawLabel   = $request->input('pagaduriaLabel');
        $monthParam = $request->input('month');
        $yearParam  = $request->input('year');

        $typeNorm  = trim(mb_strtolower($rawType));
        $labelNorm = trim(mb_strtolower($rawLabel));

        $idPagaduria = $this->getPagaduriaIdFromString($typeNorm)
            ?: $this->getPagaduriaIdFromString($labelNorm);

        Log::info('Resolved pagaduria id', ['idPagaduria' => $idPagaduria]);

        $results   = [];
        $modelKey  = str_replace(['coupons', 'descuentos', 'embargos'], '', $typeNorm);

        if (isset(self::$modelsMap[$modelKey])) {
            $modelClass = self::$modelsMap[$modelKey];
            $column     = self::$columnMap[$modelClass];

            Log::info('Querying specific model', ['model' => $modelClass, 'column' => $column]);

            $specificRows = $modelClass::on('pgsql')
                ->where($column, 'LIKE', "%{$doc}%")
                ->get()
                ->map
                ->getAttributes()
                ->all();

            Log::info('Specific model results', ['count' => count($specificRows)]);
            $results = array_merge($results, $specificRows);
        } else {
            Log::info('No specific model matched', ['key' => $modelKey]);
        }

        $queryGen = DescuentosGen::on('pgsql')->where('doc', 'LIKE', "%{$doc}%");

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
            $queryGen->whereBetween('nomina', [$startDate, $endDate]);
        }

        $sql   = $queryGen->toSql();
        $bind  = $queryGen->getBindings();
        Log::info('DescuentosGen SQL', ['sql' => $sql, 'bindings' => $bind]);

        $genRows = $queryGen->get()->map->getAttributes()->all();
        Log::info('DescuentosGen results', ['count' => count($genRows)]);

        $results = array_merge($results, $genRows);

        $results = $this->normalizeNominaDates($results);

        Log::info('DescuentosController@index end', ['total' => count($results)]);

        return response()->json($results, 200);
    }

    public function getDescuentosByPagaduria(Request $request)
    {
        if (!$request->has(['month', 'year', 'pagaduria'])) {
            Log::info('getDescuentosByPagaduria missing params', $request->all());
            return response()->json(['error' => 'month, year y pagaduria son requeridos.'], 400);
        }

        $month     = str_pad($request->input('month'), 2, '0', STR_PAD_LEFT);
        $year      = $request->input('year');
        $name      = trim($request->input('pagaduria'));
        $mliquid   = $request->input('mliquid');
        $perPage   = (int) $request->input('perPage', 20);
        $page      = (int) $request->input('page', 1);

        $panel = DB::connection('pgsql')
            ->table('panel_pagaduria')
            ->where('nombre', 'ILIKE', $name)
            ->first();

        $start = Carbon::createFromFormat('Y-m', "{$year}-{$month}")->startOfMonth();
        $end   = Carbon::createFromFormat('Y-m', "{$year}-{$month}")->endOfMonth();

        $base = DescuentosGen::on('pgsql')
            ->when($panel, fn($q) => $q->where('idpagaduria', $panel->id))
            ->when(!$panel, fn($q) => $q->where('pagaduria', 'ILIKE', "%{$name}%"))
            ->whereBetween('nomina', [$start, $end]);

        if ($mliquid) {
            $base->where('mliquid', 'ILIKE', "%{$mliquid}%");
        }

        $total = $base->count();

        $rows = $base->select('id', 'doc', 'nomp', 'mliquid', 'nomina', 'valor')
            ->orderBy('doc')
            ->forPage($page, $perPage)
            ->get()
            ->map(function ($r) {
                $r->nomina = Carbon::parse($r->nomina)->toDateString();
                return $r;
            });

        Log::info('getDescuentosByPagaduria results', ['total' => $total]);

        return response()->json(['data' => $rows, 'total' => $total], 200);
    }

    private function getPagaduriaIdFromString(string $input): ?int
    {
        if (!$input) return null;
        $clean = preg_replace('/^(coupons|descuentos|embargos)/', '', mb_strtolower($input));

        if (isset(self::$pagaduriasMap[$clean]))             return self::$pagaduriasMap[$clean];
        $noSpace = str_replace(' ', '', $clean);
        return self::$pagaduriasNoSpaces[$noSpace] ?? null;
    }

    private function normalizeNominaDates(array $rows): array
    {
        foreach ($rows as &$row) {
            if (isset($row['nomina']) && $row['nomina']) {
                $row['nomina'] = Carbon::parse($row['nomina'])->toDateString();
            }
        }
        return $rows;
    }
}
