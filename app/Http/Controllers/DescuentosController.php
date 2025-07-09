<?php

namespace App\Http\Controllers;

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
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Traits\ParsesPgTzDates;

class DescuentosController extends Controller
{
    use ParsesPgTzDates;

    public function index(Request $request)
    {
        $doc            = $request->input('doc');
        $descuentoType  = $request->input('pagaduria');
        $pagaduriaLabel = $request->input('pagaduriaLabel');

        $models = [
            DescuentosSedAtlantico::class    => 'doc',
            DescuentosSemMonteria::class     => 'doc',
            DescuentosSemBarranquilla::class => 'doc',
            DescuentosSedCauca::class        => 'doc',
            DescuentosSedCaldas::class       => 'doc',
            DescuentosSedCordoba::class      => 'doc',
            DescuentosSedChoco::class        => 'doc',
            DescuentosSedValle::class        => 'doc',
            DescuentosSemCali::class         => 'doc',
            DescuentosSemPopayan::class      => 'doc',
            DescuentosSemQuibdo::class       => 'doc',
        ];

        $results = [];

        foreach ($models as $model => $column) {
            if (class_basename($model) === $descuentoType) {
                $partial = $model::on('pgsql')
                    ->where($column, 'LIKE', "%{$doc}%")
                    ->get()
                    ->toArray();
                $results = array_merge($results, $partial);
            }
        }

        $dataGen = DescuentosGen::on('pgsql')
            ->where('doc', 'LIKE', "%{$doc}%")
            ->where(function ($q) use ($descuentoType, $pagaduriaLabel) {
                $q->where('pagaduria', $descuentoType)
                  ->orWhere('pagaduria', $pagaduriaLabel);
            })
            ->get()
            ->toArray();

        $results = array_merge($results, $dataGen);

        return response()->json($this->normalizeNominaDates($results), 200);
    }

    public function getDescuentosByPagaduria(Request $request)
{
    if (!$request->has(['month', 'year', 'pagaduria'])) {
        return response()->json(['error' => 'month, year y pagaduria son requeridos.'], 400);
    }

    $month     = str_pad($request->input('month'), 2, '0', STR_PAD_LEFT);
    $year      = $request->input('year');
    $pagaduria = $request->input('pagaduria');
    $mliquid   = $request->input('mliquid');

    $perPage = (int)$request->input('perPage', 20);
    $page    = (int)$request->input('page', 1);

    $start = Carbon::createFromFormat('Y-m', "{$year}-{$month}")->startOfMonth();
    $end   = Carbon::createFromFormat('Y-m', "{$year}-{$month}")->endOfMonth();

    $base = DescuentosGen::on('pgsql')
        ->where('pagaduria', 'ILIKE', "%{$pagaduria}%")
        ->whereBetween('nomina', [$start, $end]);

    if ($mliquid) {
        $base->where('mliquid', 'ILIKE', "%{$mliquid}%");
    }

    $total = $base->count();

    $rows  = $base->select(
                'id','doc','nomp','mliquid','nomina','valor'
            )
            ->orderBy('doc')
            ->forPage($page, $perPage)
            ->get()
            ->map(function ($r) {
                $r->nomina = Carbon::parse($r->nomina)->toDateString();
                return $r;
            });

    return response()->json(['data' => $rows, 'total' => $total], 200);
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
