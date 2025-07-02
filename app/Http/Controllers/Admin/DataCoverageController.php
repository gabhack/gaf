<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\CouponsGen;
use App\DatamesGen;
use App\DescuentosGen;
use App\EmbargosGen;

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

    public function pagaduria(Request $r)
    {
        $year  = (int) $r->query('year', date('Y'));
        $month = str_pad((int) $r->query('month', date('m')), 2, '0', STR_PAD_LEFT);
        $type  = $r->query('type', 'all');
        $id    = (int) $r->query('id');

        if (in_array($id, [308, 296, 297], true)) {
            return [];
        }

        $p = DB::connection('pgsql')
            ->table('panel_pagaduria')
            ->select('id', 'nombre')
            ->where('id', $id)
            ->first();

        if (! $p) {
            return [];
        }

        $from = Carbon::create($year, $month, 1)->startOfMonth()->format('Y-m-d');
        $to   = Carbon::create($year, $month, 1)->endOfMonth()->format('Y-m-d');

        $resp = [
            'id'         => $p->id,
            'pagaduria'  => $p->nombre,
            'datames'    => null,
            'cupones'    => null,
            'descuentos' => null,
            'embargos'   => null,
        ];

        $need = $type === 'all'
            ? ['datames', 'cupones', 'descuentos', 'embargos']
            : [$type];

        if (in_array('datames', $need, true)) {
            $resp['datames'] = DatamesGen::on('pgsql')
                ->where('idpagaduria', $p->id)
                ->whereBetween('periodo', [$from, $to])
                ->limit(1)
                ->exists();
        }

        if (in_array('cupones', $need, true)) {
            $resp['cupones'] = CouponsGen::on('pgsql')
                ->where('idpagaduria', $p->id)
                ->whereBetween('inicioperiodo', [$from, $to])
                ->limit(1)
                ->exists();
        }

        if (in_array('descuentos', $need, true)) {
            $resp['descuentos'] = DescuentosGen::on('pgsql')
                ->where('idpagaduria', $p->id)
                ->whereBetween('nomina', [$from, $to])
                ->limit(1)
                ->exists();
        }

        if (in_array('embargos', $need, true)) {
            $resp['embargos'] = EmbargosGen::on('pgsql')
                ->where('idpagaduria', $p->id)
                ->whereRaw("nomina::date BETWEEN ? AND ?", [$from, $to])
                ->limit(1)
                ->exists();
        }
        

        return $resp;
    }
}
