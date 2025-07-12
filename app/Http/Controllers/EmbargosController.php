<?php

namespace App\Http\Controllers;

use App\EmbargosSemBarranquilla;
use App\EmbargosSemCali;
use App\EmbargosSemMonteria;
use App\EmbargosSemPopayan;
use App\EmbargosSemQuibdo;
use App\EmbargosGen;
use App\EmbargosSedAtlantico;
use App\EmbargosSedBolivar;
use App\EmbargosSedCaldas;
use App\EmbargosSedCauca;
use App\EmbargosSedChoco;
use App\EmbargosSedCordoba;
use App\EmbargosSedValle;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Traits\ParsesPgTzDates;

class EmbargosController extends Controller
{
    use ParsesPgTzDates;

    public function index(Request $request)
    {
        Log::info('Entering EmbargosController@index', $request->all());

        try {
            $doc            = $request->input('doc');
            $pagaduria      = $request->input('pagaduria');
            $pagaduriaLabel = $request->input('pagaduriaLabel');
            $monthParam     = $request->input('month');
            $yearParam      = $request->input('year');

            Log::info('Parameters', [
                'doc'            => $doc,
                'pagaduria'      => $pagaduria,
                'pagaduriaLabel' => $pagaduriaLabel,
                'month'          => $monthParam,
                'year'           => $yearParam,
            ]);

            $results = [];

            $models = [
                EmbargosSedCauca::class        => 'doc',
                EmbargosSedChoco::class        => 'doc',
                EmbargosSedCordoba::class      => 'doc',
                EmbargosSemBarranquilla::class => 'doc',
                EmbargosSedAtlantico::class    => 'doc',
                EmbargosSedValle::class        => 'doc',
                EmbargosSedCaldas::class       => 'doc',
                EmbargosSemCali::class         => 'doc',
                EmbargosSemPopayan::class      => 'doc',
                EmbargosSemMonteria::class     => 'doc',
                EmbargosSemQuibdo::class       => 'idemp',
                EmbargosSedBolivar::class      => 'idemp',
            ];

            foreach ($models as $modelClass => $column) {
                $name = class_basename($modelClass);
                if ($name === $pagaduria) {
                    Log::info("Querying {$name} by {$column} LIKE %{$doc}%");
                    $partial = $modelClass::on('pgsql')
                        ->where($column, 'LIKE', "%{$doc}%")
                        ->get()
                        ->map
                        ->getAttributes()
                        ->all();

                    Log::info("{$name} returned " . count($partial) . ' records');
                    $results = array_merge($results, $partial);
                }
            }

            $queryGen = EmbargosGen::on('pgsql')
                ->where('doc', 'LIKE', "%{$doc}%");

            if (is_numeric($monthParam) && is_numeric($yearParam)) {
                $month     = str_pad($monthParam, 2, '0', STR_PAD_LEFT);
                $startDate = Carbon::createFromFormat('Y-m', "{$yearParam}-{$month}")->startOfMonth();
                $endDate   = Carbon::createFromFormat('Y-m', "{$yearParam}-{$month}")->endOfMonth();

                Log::info("Filtering EmbargosGen by pagaduria and date between {$startDate} and {$endDate}");

                $queryGen->where(function ($q) use ($pagaduria, $pagaduriaLabel) {
                    $q->where('pagaduria', $pagaduria)
                        ->orWhere('pagaduria', $pagaduriaLabel);
                })->whereRaw("to_date(nomina, 'YYYY-MM-DD') BETWEEN ? AND ?", [$startDate->toDateString(), $endDate->toDateString()]);
            } else {
                Log::info('Filtering EmbargosGen by pagaduria only');

                $queryGen->where(function ($q) use ($pagaduria, $pagaduriaLabel) {
                    $q->where('pagaduria', $pagaduria)
                        ->orWhere('pagaduria', $pagaduriaLabel);
                });
            }

            $dataGen = $queryGen->get()->map->getAttributes()->all();

            Log::info('EmbargosGen returned ' . count($dataGen) . ' records');

            $results = array_merge($results, $dataGen);

            $results = $this->normalizeNominaDates($results);

            Log::info('Exiting EmbargosController@index with total results: ' . count($results));

            return response()->json($results, 200);
        } catch (\Exception $e) {
            Log::error('Error in EmbargosController@index', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function buscar($doc = null, $pagaduria = null)
    {
        $embargoType = $pagaduria;

        $models = [
            EmbargosSedCauca::class => 'doc',
            EmbargosSedChoco::class => 'doc',
            EmbargosSedValle::class => 'doc',
            EmbargosSemCali::class  => 'doc',
            EmbargosSemPopayan::class => 'doc',
            EmbargosSemQuibdo::class  => 'idemp',
        ];

        $results = [];

        foreach ($models as $model => $column) {
            $className = class_basename($model);

            if ($className === $embargoType) {
                $results = $model::on('pgsql')
                    ->where($column, 'LIKE', "%{$doc}%")
                    ->get()
                    ->map
                    ->getAttributes()
                    ->all();
            }
        }

        return $this->normalizeNominaDates($results);
    }

    public function getEmbargosByPagaduria(Request $request)
    {
        if (!$request->has(['month', 'year', 'pagaduria'])) {
            return response()->json(['error' => 'month, year y pagaduria son requeridos.'], 400);
        }

        $month             = str_pad($request->input('month'), 2, '0', STR_PAD_LEFT);
        $year              = $request->input('year');
        $pagaduria         = $request->input('pagaduria');
        $entidadDemandante = $request->input('entidadDemandante');
        $perPage           = (int) $request->input('perPage', 20);
        $page              = (int) $request->input('page', 1);

        $start = Carbon::createFromFormat('Y-m', "{$year}-{$month}")->startOfMonth()->format('Y-m-d');
        $end   = Carbon::createFromFormat('Y-m', "{$year}-{$month}")->endOfMonth()->format('Y-m-d');

        Log::info('EmbargosGen filtro', [
            'pagaduria' => $pagaduria,
            'start'     => $start,
            'end'       => $end,
            'entidad'   => $entidadDemandante
        ]);

        $base = EmbargosGen::on('pgsql')
            ->where('pagaduria', 'ILIKE', "%{$pagaduria}%")
            ->whereRaw("to_date(nomina, 'YYYY-MM-DD') BETWEEN ? AND ?", [$start, $end]);

        if ($entidadDemandante) {
            $base->where('entidaddeman', 'ILIKE', "%{$entidadDemandante}%");
        }

        $total = $base->count();

        $rows = $base->select(
                    'id','doc','nomp','docdeman','entidaddeman','motemb','temb',
                    DB::raw("to_char(to_date(nomina, 'YYYY-MM-DD'), 'YYYY-MM-DD') as nomina")
                )
                ->orderBy('doc')
                ->forPage($page, $perPage)
                ->get();

        Log::info('EmbargosGen resultados', ['total' => $total, 'page' => $page]);

        return response()->json(['data' => $rows, 'total' => $total], 200);
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
