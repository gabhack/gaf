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
        Log::info('Index method called', $request->all());

        $doc = $request->doc;
        Log::info('doc param: ' . $doc);

        $embargoType = $request->pagaduria;
        Log::info('embargoType param: ' . $embargoType);

        $pagaduriaLabel = $request->pagaduriaLabel;
        Log::info('pagaduriaLabel param: ' . $pagaduriaLabel);

        $results = [];

        $models = [
            EmbargosSedCauca::class => 'doc',
            EmbargosSedChoco::class => 'doc',
            EmbargosSedCordoba::class => 'doc',
            EmbargosSemBarranquilla::class => 'doc',
            EmbargosSedAtlantico::class => 'doc',
            EmbargosSedValle::class => 'doc',
            EmbargosSedCaldas::class => 'doc',
            EmbargosSemCali::class => 'doc',
            EmbargosSemPopayan::class => 'doc',
            EmbargosSemMonteria::class => 'doc',
            EmbargosSemQuibdo::class => 'idemp',
            EmbargosSedBolivar::class => 'idemp',
        ];

        foreach ($models as $model => $column) {
            $className = class_basename($model);
            if ($className == $embargoType) {
                Log::info("Querying model: $className with column: $column");
                $partialResults = $model::where($column, 'LIKE', '%' . $doc . '%')->get()->toArray();
                Log::info('Partial results count: ' . count($partialResults));
                $results = array_merge($results, $partialResults);
            }
        }

        $month = $request->month;
        $year = $request->year;
        Log::info('Month param: ' . $month . ' - Year param: ' . $year);

        $dataGenQuery = EmbargosGen::where('doc', 'LIKE', '%' . $doc . '%');

        if (is_numeric($month) && is_numeric($year)) {
            $month = str_pad($month, 2, '0', STR_PAD_LEFT);
            $startDate = Carbon::createFromFormat('Y-m', $year . '-' . $month)->startOfMonth();
            $endDate = Carbon::createFromFormat('Y-m', $year . '-' . $month)->endOfMonth();
            Log::info('Start date: ' . $startDate . ' - End date: ' . $endDate);

            $dataGenQuery = $dataGenQuery->where(function ($query) use ($embargoType, $pagaduriaLabel, $startDate, $endDate) {
                $query->where('pagaduria', $embargoType)
                      ->orWhere('pagaduria', $pagaduriaLabel)
                      ->whereBetween('nomina', [$startDate, $endDate]);
            });
        } else {
            $dataGenQuery = $dataGenQuery->where(function ($query) use ($embargoType, $pagaduriaLabel) {
                $query->where('pagaduria', $embargoType)
                      ->orWhere('pagaduria', $pagaduriaLabel);
            });
        }

        $dataGen = $dataGenQuery->get()->toArray();
        Log::info('EmbargosGen partial results count: ' . count($dataGen));
        Log::info('EmbargosGen partial results: ', $dataGen);

        $results = array_merge($results, $dataGen);
        Log::info('Final results count: ' . count($results));

        return response()->json($results, 200);
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
        Log::info('Request data:', $request->all());
        try {
            if (!$request->has('month') || !$request->has('year') || !$request->has('pagaduria')) {
                return response()->json(['error' => 'Month, year, and pagaduria are required.'], 400);
            }
            $pagaduria = $request->pagaduria;
            $entidadDemandante = $request->entidadDemandante;
            $month = str_pad($request->month, 2, '0', STR_PAD_LEFT);
            $year = $request->year;
            $startDate = Carbon::createFromFormat('Y-m', $year . '-' . $month)->startOfMonth();
            $endDate = Carbon::createFromFormat('Y-m', $year . '-' . $month)->endOfMonth();
            $query = EmbargosGen::query();
            if ($entidadDemandante) {
                $query->where('entidaddeman', 'ILIKE', '%' . $entidadDemandante . '%');
            }
            $query->where('pagaduria', 'ILIKE', '%' . $pagaduria . '%');
            $query->whereBetween('nomina', [$startDate, $endDate]);
            $query->select('id', 'doc', 'nomp', 'docdeman', 'entidaddeman', 'motemb', 'temb');
            $sql = $query->toSql();
            $bindings = $query->getBindings();
            Log::info('Executing SQL in getEmbargosByPagaduria: ' . $sql, $bindings);
            $results = $query->get()->toArray();
            Log::info('Results: ', $results);
            return response()->json($results, 200);
        } catch (\Exception $e) {
            Log::error('Error in getEmbargosByPagaduria:', ['message' => $e->getMessage(), 'trace' => $e->getTrace()]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
