<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Traits\ParsesPgTzDates;
use App\Helpers\PagaduriaHelper;
use App\DescuentosGen;

class DescuentosController extends Controller
{
    use ParsesPgTzDates;

    public function index(Request $r)
    {
        Log::info('DescuentosController@index start', $r->all());

        $doc        = $r->input('doc');
        $rawType    = $r->input('pagaduria');
        $rawLabel   = $r->input('pagaduriaLabel');
        $monthParam = $r->input('month');
        $yearParam  = $r->input('year');

        $typeNorm  = trim(mb_strtolower((string) $rawType));
        $labelNorm = trim(mb_strtolower((string) $rawLabel));

        $idPag = $this->resolvePagaduriaId($typeNorm) ?: $this->resolvePagaduriaId($labelNorm);
        Log::info('Resolved pagaduria id', ['id' => $idPag]);

        $gen = DescuentosGen::on('pgsql')->where('doc', 'LIKE', "%{$doc}%");

        if ($idPag) {
            $gen->where('idpagaduria', $idPag);
        } else {
            if ($rawType || $rawLabel) {
                $gen->where(function ($q) use ($rawType, $rawLabel) {
                    if ($rawType)  $q->orWhere('pagaduria', 'ILIKE', $rawType);
                    if ($rawLabel) $q->orWhere('pagaduria', 'ILIKE', $rawLabel);
                });
            }
        }

        if (is_numeric($monthParam) && is_numeric($yearParam)) {
            $m = str_pad($monthParam, 2, '0', STR_PAD_LEFT);
            $s = Carbon::createFromFormat('Y-m', "{$yearParam}-{$m}")->startOfMonth();
            $e = Carbon::createFromFormat('Y-m', "{$yearParam}-{$m}")->endOfMonth();
            $gen->whereBetween('nomina', [$s, $e]);
        }

        Log::info('DescuentosGen SQL', ['sql' => $gen->toSql(), 'bindings' => $gen->getBindings()]);

        $rows = $gen->get()->map(function ($r) {
            $attr = $r->getAttributes();
            if (!empty($attr['nomina'])) {
                $attr['nomina'] = Carbon::parse($attr['nomina'])->toDateString();
            }
            return $attr;
        })->all();

        Log::info('DescuentosGen rows', ['count' => count($rows)]);
        Log::info('DescuentosController@index end', ['total' => count($rows)]);

        return response()->json($rows, 200);
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

    private function resolvePagaduriaId(?string $str): ?int
    {
        if (!$str) return null;

        $clean = preg_replace('/^(coupons|descuentos|embargos)/', '', $str);

        $map = PagaduriaHelper::map();

        foreach ($map as $name => $id) {
            if (mb_strtolower($name) === mb_strtolower($clean)) {
                return (int) $id;
            }
        }

        $target = str_replace(' ', '', mb_strtolower($clean));
        foreach ($map as $name => $id) {
            $key = str_replace(' ', '', mb_strtolower($name));
            if ($key === $target) return (int) $id;
        }

        return null;
    }
}
