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
    


    //CONSULTAR LAS TABLAS PASADAS
    //LO QUITE POR VELOCIDAD

     /*$models = [
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
                $results = array_merge($results, $model::where($column, 'LIKE', '%' . $doc . '%')->get()->toArray());
            }
        }*/

        public function index(Request $request)
        {
            $doc = $request->doc;
            $embargoType = $request->pagaduria;
            $pagaduriaLabel = $request->pagaduriaLabel;
        
            $month = str_pad($request->month, 2, '0', STR_PAD_LEFT);
            $year = $request->year;
            $results = [];

            $dataGen = EmbargosGen::where('doc', 'LIKE', '%' . $doc . '%')
                ->where(function ($query) use ($embargoType, $pagaduriaLabel, $month, $year) {        
                    $startDate = Carbon::createFromFormat('Y-m', $year . '-' . $month)->startOfMonth();
                    $endDate = Carbon::createFromFormat('Y-m', $year . '-' . $month)->endOfMonth();
        
                    $query->where('pagaduria', $embargoType)
                        ->orWhere('pagaduria', $pagaduriaLabel)
                        ->whereBetween('nomina', [$startDate, $endDate]);
                })->get()->toArray();
        
            $results = array_merge($results, $dataGen);
        
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
    
            // Capturar el SQL y los bindings
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
