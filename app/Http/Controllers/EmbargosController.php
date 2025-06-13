<?php

namespace App\Http\Controllers;

use App\EmbargosSemBarranquilla;
use App\EmbargosSedCauca;
use App\EmbargosSedAtlantico;
use App\EmbargosSedBolivar;
use App\EmbargosSedChoco;
use App\EmbargosSedCaldas;
use App\EmbargosSedCordoba;
use App\EmbargosSedValle;
use App\EmbargosSemCali;
use App\EmbargosSemPopayan;
use App\EmbargosSemMonteria;
use App\EmbargosSemQuibdo;
use App\EmbargosGen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class EmbargosController extends Controller
{
    public function index(Request $request)
{
    Log::info('Entering EmbargosController@index', $request->all());

    try {
        $doc             = $request->input('doc');
        $pagaduria       = $request->input('pagaduria');
        $pagaduriaLabel  = $request->input('pagaduriaLabel');
        $monthParam      = $request->input('month');
        $yearParam       = $request->input('year');

        Log::info('Parameters', [
            'doc'            => $doc,
            'pagaduria'      => $pagaduria,
            'pagaduriaLabel' => $pagaduriaLabel,
            'month'          => $monthParam,
            'year'           => $yearParam,
        ]);

        $results = [];

        $models = [
            EmbargosSedCauca::class       => 'doc',
            EmbargosSedChoco::class       => 'doc',
            EmbargosSedCordoba::class     => 'doc',
            EmbargosSemBarranquilla::class=> 'doc',
            EmbargosSedAtlantico::class   => 'doc',
            EmbargosSedValle::class       => 'doc',
            EmbargosSedCaldas::class      => 'doc',
            EmbargosSemCali::class        => 'doc',
            EmbargosSemPopayan::class     => 'doc',
            EmbargosSemMonteria::class    => 'doc',
            EmbargosSemQuibdo::class      => 'idemp',
            EmbargosSedBolivar::class     => 'idemp',
        ];

        foreach ($models as $modelClass => $column) {
            $name = class_basename($modelClass);
            if ($name === $pagaduria) {
                Log::info("Querying {$name} by {$column} LIKE %{$doc}%");
                $partial = $modelClass::where($column, 'LIKE', "%{$doc}%")->get()->toArray();
                Log::info("  -> {$name} returned " . count($partial) . ' records');
                $results = array_merge($results, $partial);
            }
        }

        $queryGen = EmbargosGen::where('doc', 'LIKE', "%{$doc}%");

        if (is_numeric($monthParam) && is_numeric($yearParam)) {
            $month     = str_pad($monthParam, 2, '0', STR_PAD_LEFT);
            $startDate = Carbon::createFromFormat('Y-m', "{$yearParam}-{$month}")->startOfMonth();
            $endDate   = Carbon::createFromFormat('Y-m', "{$yearParam}-{$month}")->endOfMonth();

            Log::info("Filtering EmbargosGen by pagaduria and date between {$startDate} and {$endDate}");
            $queryGen->where(function($q) use ($pagaduria, $pagaduriaLabel, $startDate, $endDate) {
                $q->where('pagaduria', $pagaduria)
                  ->orWhere('pagaduria', $pagaduriaLabel)
                  ->whereBetween('nomina', [$startDate, $endDate]);
            });
        } else {
            Log::info("Filtering EmbargosGen by pagaduria only");
            $queryGen->where(function($q) use ($pagaduria, $pagaduriaLabel) {
                $q->where('pagaduria', $pagaduria)
                  ->orWhere('pagaduria', $pagaduriaLabel);
            });
        }

        $dataGen = $queryGen->get()->toArray();
        Log::info('EmbargosGen returned ' . count($dataGen) . ' records', $dataGen);

        $results = array_merge($results, $dataGen);
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
            EmbargosSemCali::class => 'doc',
            EmbargosSemPopayan::class => 'doc',
            EmbargosSemQuibdo::class => 'idemp',
        ];
        $results = [];
        foreach ($models as $model => $column) {
            $className = class_basename($model);
            if ($className == $embargoType) {
                $results = $model::where($column, 'LIKE', '%' . $doc . '%')->get();
            }
        }
        return $results;
    }

    public function getEmbargosByPagaduria(Request $request)
    {
        Log::info('Entering EmbargosController@getEmbargosByPagaduria', $request->all());
    
        try {
            if (! $request->has(['month', 'year', 'pagaduria'])) {
                Log::warning('Missing required parameters in getEmbargosByPagaduria', $request->all());
                return response()->json(['error' => 'Month, year, and pagaduria are required.'], 400);
            }
    
            $pagaduria         = $request->input('pagaduria');
            $entidadDemandante = $request->input('entidadDemandante');
            $rawMonth          = $request->input('month');
            $year              = $request->input('year');
            $month             = str_pad($rawMonth, 2, '0', STR_PAD_LEFT);
    
            Log::info('Parsed parameters', [
                'pagaduria'         => $pagaduria,
                'entidadDemandante' => $entidadDemandante,
                'month'             => $month,
                'year'              => $year,
            ]);
    
            $startDate = Carbon::createFromFormat('Y-m', "{$year}-{$month}")->startOfMonth();
            $endDate   = Carbon::createFromFormat('Y-m', "{$year}-{$month}")->endOfMonth();
            Log::info("Filtering date range", [
                'startDate' => $startDate->toDateString(),
                'endDate'   => $endDate->toDateString(),
            ]);
    
            $query = EmbargosGen::query();
    
            if ($entidadDemandante) {
                Log::info("Applying entidadDemandante filter LIKE %{$entidadDemandante}%");
                $query->where('entidaddeman', 'ILIKE', "%{$entidadDemandante}%");
            }
    
            Log::info("Applying pagaduria filter ILIKE %{$pagaduria}%");
            $query->where('pagaduria', 'ILIKE', "%{$pagaduria}%");
            $query->whereBetween('nomina', [$startDate, $endDate]);
    
            $query->select('id', 'doc', 'nomp', 'docdeman', 'entidaddeman', 'motemb', 'temb');
    
            $sql      = $query->toSql();
            $bindings = $query->getBindings();
            Log::info('Executing SQL', ['sql' => $sql, 'bindings' => $bindings]);
    
            $results = $query->get()->toArray();
            Log::info('Query returned ' . count($results) . ' records', $results);
    
            Log::info('Exiting EmbargosController@getEmbargosByPagaduria');
            return response()->json($results, 200);
    
        } catch (\Exception $e) {
            Log::error('Exception in getEmbargosByPagaduria', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
    
}
