<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataCoverageController extends Controller
{
    public function index()
    {
        return DB::connection('pgsql')
            ->table('panel_pagaduria')
            ->select('id', 'nombre')
            ->whereNotIn('id', [308, 296, 297])
            ->orderBy('nombre')
            ->get();
    }

    public function batch(Request $r)
    {
        $year   = (int)$r->query('year', date('Y'));
        $month  = str_pad((int)$r->query('month', date('m')), 2, '0', STR_PAD_LEFT);
        $ym     = $year . '-' . $month;
        $limit  = (int)$r->query('limit', 50);
        $offset = (int)$r->query('offset', 0);

        $pagadurias = DB::connection('pgsql')
            ->table('panel_pagaduria')
            ->select('id', 'nombre')
            ->whereNotIn('id', [308, 296, 297])
            ->orderBy('nombre')
            ->offset($offset)
            ->limit($limit)
            ->get();

        if ($pagadurias->isEmpty()) {
            return [];
        }

        $ids = $pagadurias->pluck('id')->all();

        $counts = DB::connection('pgsql')
            ->table('mv_data_coverage')
            ->where('ym', $ym)
            ->whereIn('idpagaduria', $ids)
            ->get()
            ->keyBy('idpagaduria');

        $out = [];
        foreach ($pagadurias as $p) {
            $c = $counts[$p->id] ?? null;
            $out[] = [
                'id'         => $p->id,
                'pagaduria'  => $p->nombre,
                'datames'    => (int)($c->datames ?? 0),
                'cupones'    => (int)($c->cupones ?? 0),
                'descuentos' => (int)($c->descuentos ?? 0),
                'embargos'   => (int)($c->embargos ?? 0),
            ];
        }

        return $out;
    }
}
