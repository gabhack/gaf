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
            DescuentosSedAtlantico::class,
            DescuentosSemMonteria::class,
            DescuentosSemBarranquilla::class,
            DescuentosSedCauca::class,
            DescuentosSedCaldas::class,
            DescuentosSedCordoba::class,
            DescuentosSedChoco::class,
            DescuentosSedValle::class,
            DescuentosSemCali::class,
            DescuentosSemPopayan::class,
            DescuentosSemQuibdo::class,
        ];
    
        $results = [];
    
        foreach ($models as $model) {
            if (class_basename($model) === $descuentoType) {
                $collection = $model::on('pgsql')
                    ->where('doc', 'LIKE', "%{$doc}%")
                    ->get();
                foreach ($collection as $item) {
                    $dateFields = array_keys(array_filter(
                        $item->getCasts(),
                        fn($t) => in_array($t, ['date','datetime','immutable_date','immutable_datetime'])
                    ));
                    foreach ($dateFields as $field) {
                        $raw = $item->getOriginal($field);
                        if (preg_match('/^\d{1,2}-[a-z]{3}-\d{2}$/i', $raw)) {
                            Log::error('Formato inválido de fecha', [
                                'model'     => $model,
                                'id'        => $item->id,
                                'column'    => $field,
                                'raw_value' => $raw,
                            ]);
                        }
                    }
                }
                $results = array_merge($results, $collection->toArray());
            }
        }
    
        $genCollection = DescuentosGen::on('pgsql')
            ->where('doc', 'LIKE', "%{$doc}%")
            ->where(fn($q) => $q->where('pagaduria', $descuentoType)->orWhere('pagaduria', $pagaduriaLabel))
            ->get();
    
        foreach ($genCollection as $item) {
            $dateFields = array_keys(array_filter(
                $item->getCasts(),
                fn($t) => in_array($t, ['date','datetime','immutable_date','immutable_datetime'])
            ));
            foreach ($dateFields as $field) {
                $raw = $item->getOriginal($field);
                if (preg_match('/^\d{1,2}-[a-z]{3}-\d{2}$/i', $raw)) {
                    Log::error('Formato inválido de fecha', [
                        'model'     => DescuentosGen::class,
                        'id'        => $item->id,
                        'column'    => $field,
                        'raw_value' => $raw,
                    ]);
                }
            }
        }
    
        $results = array_merge($results, $genCollection->toArray());
    
        return response()->json($this->normalizeNominaDates($results), 200);
    }
    
    
    

    public function getDescuentosByPagaduria(Request $request)
{
    if (!$request->has(['month', 'year', 'pagaduria'])) {
        Log::info('getDescuentosByPagaduria request missing params', $request->all());
        return response()->json(['error' => 'month, year y pagaduria son requeridos.'], 400);
    }

    Log::info('getDescuentosByPagaduria request', $request->all());

    $month     = str_pad($request->input('month'), 2, '0', STR_PAD_LEFT);
    $year      = $request->input('year');
    $pagaduria = $request->input('pagaduria');
    $mliquid   = $request->input('mliquid');
    $perPage   = (int) $request->input('perPage', 20);
    $page      = (int) $request->input('page', 1);

    $start = Carbon::createFromFormat('Y-m', "{$year}-{$month}")->startOfMonth();
    $end   = Carbon::createFromFormat('Y-m', "{$year}-{$month}")->endOfMonth();

    $base = DescuentosGen::on('pgsql')
        ->where('pagaduria', 'ILIKE', "%{$pagaduria}%")
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

    Log::info('getDescuentosByPagaduria resultados', [
        'total' => $total,
        'data'  => $rows->toArray(),
    ]);

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
