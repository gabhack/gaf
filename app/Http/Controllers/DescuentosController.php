<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Traits\ParsesPgTzDates;
use App\Helpers\PagaduriaHelper;
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
use App\DescuentosGen;

class DescuentosController extends Controller
{
    use ParsesPgTzDates;

    private static $modelsMap   = [];
    private static $columnMap   = [];
    private static $pagMap      = [];
    private static $pagNoSpaces = [];

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
            foreach (self::$modelsMap as $cls) {
                self::$columnMap[$cls] = $cls === DescuentosSemQuibdo::class ? 'idemp' : 'doc';
            }
        }
        if (empty(self::$pagMap)) {
            self::$pagMap = PagaduriaHelper::map();
            self::$pagNoSpaces = collect(self::$pagMap)->mapWithKeys(function ($id, $name) {
                return [str_replace(' ', '', mb_strtolower($name)) => $id];
            })->all();
        }
    }

    public function index(Request $r)
    {
        Log::info('DescuentosController@index start', $r->all());

        $doc        = $r->input('doc');
        $rawType    = $r->input('pagaduria');
        $rawLabel   = $r->input('pagaduriaLabel');
        $monthParam = $r->input('month');
        $yearParam  = $r->input('year');

        $typeNorm  = trim(mb_strtolower($rawType));
        $labelNorm = trim(mb_strtolower($rawLabel));

        $idPag = $this->pagId($typeNorm) ?: $this->pagId($labelNorm);
        Log::info('Resolved pagaduria id', ['id' => $idPag]);

        $results  = [];
        $modelKey = str_replace(['coupons', 'descuentos', 'embargos'], '', $typeNorm);

        if (isset(self::$modelsMap[$modelKey])) {
            $model   = self::$modelsMap[$modelKey];
            $column  = self::$columnMap[$model];
            Log::info('Querying specific model', ['model' => $model, 'column' => $column]);
            $part    = $model::on('pgsql')->where($column, 'LIKE', "%{$doc}%")->get()->map(function ($r) {
                return $r->getAttributes();
            })->all();
            Log::info('Specific model rows', ['count' => count($part)]);
            $results = array_merge($results, $part);
        } else {
            Log::info('No specific model matched', ['key' => $modelKey]);
        }

        $gen = DescuentosGen::on('pgsql')->where('doc', 'LIKE', "%{$doc}%");
        if ($idPag) {
            $gen->where('idpagaduria', $idPag);
        } else {
            $gen->where(function ($q) use ($rawType, $rawLabel) {
                $q->where('pagaduria', $rawType)->orWhere('pagaduria', $rawLabel);
            });
        }
        if (is_numeric($monthParam) && is_numeric($yearParam)) {
            $m  = str_pad($monthParam, 2, '0', STR_PAD_LEFT);
            $s  = Carbon::createFromFormat('Y-m', "{$yearParam}-{$m}")->startOfMonth();
            $e  = Carbon::createFromFormat('Y-m', "{$yearParam}-{$m}")->endOfMonth();
            $gen->whereBetween('nomina', [$s, $e]);
        }
        Log::info('DescuentosGen SQL', ['sql' => $gen->toSql(), 'bindings' => $gen->getBindings()]);
        $genRows = $gen->get()->map(function ($r) {
            return $r->getAttributes();
        })->all();
        Log::info('DescuentosGen rows', ['count' => count($genRows)]);

        $results = array_merge($results, $genRows);
        $results = $this->normNomina($results);

        Log::info('DescuentosController@index end', ['total' => count($results)]);
        return response()->json($results, 200);
    }

    public function getDescuentosByPagaduria(Request $r)
    {
        if (!$r->has(['month', 'year', 'pagaduria'])) {
            Log::info('getDescuentosByPagaduria missing', $r->all());
            return response()->json(['error' => 'month, year y pagaduria son requeridos.'], 400);
        }

        $month   = str_pad($r->input('month'), 2, '0', STR_PAD_LEFT);
        $year    = $r->input('year');
        $name    = trim($r->input('pagaduria'));
        $mliquid = $r->input('mliquid');
        $perPage = (int) $r->input('perPage', 20);
        $page    = (int) $r->input('page', 1);

        $panel = DB::connection('pgsql')->table('panel_pagaduria')->where('nombre', 'ILIKE', $name)->first();

        $start = Carbon::createFromFormat('Y-m', "{$year}-{$month}")->startOfMonth();
        $end   = Carbon::createFromFormat('Y-m', "{$year}-{$month}")->endOfMonth();

        $base = DescuentosGen::on('pgsql');
        if ($panel) {
            $base->where('idpagaduria', $panel->id);
        } else {
            $base->where('pagaduria', 'ILIKE', "%{$name}%");
        }
        $base->whereBetween('nomina', [$start, $end]);
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

        Log::info('getDescuentosByPagaduria rows', ['total' => $total]);
        return response()->json(['data' => $rows, 'total' => $total], 200);
    }

    private function pagId($str)
    {
        if (!$str) {
            return null;
        }
        $clean = preg_replace('/^(coupons|descuentos|embargos)/', '', $str);
        if (isset(self::$pagMap[$clean])) {
            return self::$pagMap[$clean];
        }
        $noSpace = str_replace(' ', '', $clean);
        return isset(self::$pagNoSpaces[$noSpace]) ? self::$pagNoSpaces[$noSpace] : null;
    }

    private function normNomina(array $rows)
    {
        foreach ($rows as &$row) {
            if (isset($row['nomina']) && $row['nomina']) {
                $row['nomina'] = Carbon::parse($row['nomina'])->toDateString();
            }
        }
        return $rows;
    }
}
