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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class DescuentosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $doc = $request->doc;
        $descuentoType = $request->pagaduria;
        $pagaduriaLabel = $request->pagaduriaLabel;

        $models = [
            DescuentosSedAtlantico::class => 'doc',
            DescuentosSemMonteria::class => 'doc',
            DescuentosSemBarranquilla::class => 'doc',
            DescuentosSedCauca::class => 'doc',
            DescuentosSedCaldas::class => 'doc',
            DescuentosSedCordoba::class => 'doc',
            DescuentosSedChoco::class => 'doc',
            DescuentosSedValle::class => 'doc',
            DescuentosSemCali::class => 'doc',
            DescuentosSemPopayan::class => 'doc',
            DescuentosSemQuibdo::class => 'doc',
        ];

        $results = [];

        foreach ($models as $model => $column) {
            $className = class_basename($model);

            if ($className == $descuentoType) {
                $results = array_merge($results, $model::where($column, 'LIKE', '%' . $doc . '%')->get()->toArray());
            }
        }

        // General descuentos
        $dataGen = DescuentosGen::where('doc', 'LIKE', '%' . $doc . '%')
            ->where(function ($query) use ($descuentoType, $pagaduriaLabel) {
                $query->where('pagaduria', $descuentoType)
                    ->orWhere('pagaduria', $pagaduriaLabel);
            })->get()->toArray();

        $results = array_merge($results, $dataGen);

        return response()->json($results, 200);
    }


    
public function getDescuentosByPagaduria(Request $request)
{
    try {
        if (!$request->has('month') || !$request->has('year') || !$request->has('pagaduria')) {
            return response()->json(['error' => 'month, year, y pagaduria son requeridos.'], 400);
        }

        $pagaduria = $request->pagaduria;
        $mliquid = $request->mliquid;
        $month = str_pad($request->month, 2, '0', STR_PAD_LEFT);
        $year = $request->year;

        $startDate = Carbon::createFromFormat('Y-m', $year . '-' . $month)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $year . '-' . $month)->endOfMonth();

        $query = DescuentosGen::query();

        if ($mliquid) {
            $query->where('mliquid', 'ILIKE', '%' . $mliquid . '%');
        }

        $query->where('pagaduria', 'ILIKE', '%' . $pagaduria . '%');

        $query->whereBetween('nomina', [$startDate, $endDate]);

        $sql = $query->toSql();
        $bindings = $query->getBindings();

        Log::info('Ejecutando consulta SQL en Descuentos: ' . $sql, $bindings);

        $results = $query->get()->toArray();

        return response()->json($results, 200);
    } catch (\Exception $e) {
        Log::error('Error en getDescuentosByPagaduria:', ['message' => $e->getMessage(), 'trace' => $e->getTrace()]);

        return response()->json(['error' => 'Internal Server Error'], 500);
    }
}
}
