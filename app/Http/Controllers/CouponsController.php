<?php

namespace App\Http\Controllers;

use App\CouponsSedAtlantico;
use App\CouponsSemMonteria;
use App\CouponsSedBolivar;
use App\CouponsSedCauca;
use App\CouponsSedCaldas;
use App\CouponsSedCordoba;
use App\CouponsSedChoco;
use App\CouponsSedFopep;
use App\CouponsSedMagdalena;
use App\CouponsSedValle;
use App\CouponsSemBarranquilla;
use App\CouponsSemPopayan;
use App\CouponsSemQuibdo;
use App\CouponsSemSahagun;
use App\CouponsSemCali;
use App\CouponsGen;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class CouponsController extends Controller
{

    public function showCouponsForm()
    {
        return view('Coupons.CouponsConsult');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $doc = $request->doc;
        $couponType = $request->pagaduria;
        $pagaduriaLabel = $request->pagaduriaLabel;

        $models = [
            CouponsSedAtlantico::class => 'doc',
            CouponsSedBolivar::class => 'doc',
            CouponsSedCauca::class => 'doc',
            CouponsSedChoco::class => 'doc',
            CouponsSedCaldas::class => 'doc',
            CouponsSedCordoba::class => 'doc',
            CouponsSedFopep::class => 'doc',
            CouponsSedMagdalena::class => 'doc',
            CouponsSedValle::class => 'doc',
            CouponsSemBarranquilla::class => 'doc',
            CouponsSemPopayan::class => 'doc',
            CouponsSemMonteria::class => 'doc',
            CouponsSemQuibdo::class => 'doc',
            CouponsSemSahagun::class => 'doc',
            CouponsSemCali::class => 'doc',
        ];

        $results = [];

        foreach ($models as $model => $column) {
            $className = class_basename($model);

            if ($className == $couponType) {
                $results = array_merge($results, $model::where($column, 'LIKE', '%' . $doc . '%')->get()->toArray());
            }
        }

        // General coupons
        $dataGen = CouponsGen::where('doc', 'LIKE', '%' . $doc . '%')
            ->where(function ($query) use ($couponType, $pagaduriaLabel) {
                $query->where('pagaduria', $couponType)
                    ->orWhere('pagaduria', $pagaduriaLabel);
            })->get()->toArray();

        $results = array_merge($results, $dataGen);

        return response()->json($results, 200);
    }


    public function getCouponsByPagaduria(Request $request)
    {
        try {
            if (!$request->has('month') || !$request->has('year') || !$request->has('pagaduria')) {
                return response()->json(['error' => 'Month, year, and pagaduria are required.'], 400);
            }
    
            $pagaduria = $request->pagaduria;
            $concept = $request->concept;
            $code = $request->code;
            $month = str_pad($request->month, 2, '0', STR_PAD_LEFT);
            $year = $request->year;
    
            $startDate = Carbon::createFromFormat('Y-m', $year . '-' . $month)->startOfMonth()->toDateString();
            $endDate = Carbon::createFromFormat('Y-m', $year . '-' . $month)->endOfMonth()->toDateString();
    
            $query = CouponsGen::query();
    
            if ($concept) {
                $query->where('concept', 'ILIKE', '%' . $concept . '%');
            }
    
            if ($code) {
                $query->where('code', 'ILIKE', '%' . $code . '%');
            }
    
            $query->where('pagaduria', 'ILIKE', '%' . $pagaduria . '%');
    
            $query->where('inicioperiodo', '<=', $endDate);
            $query->where('finperiodo', '>=', $startDate);
    
            $sql = $query->toSql();
            $bindings = $query->getBindings();
    
            Log::info('Ejecutando consulta SQL: ' . $sql, $bindings);
    
            $results = $query->get()->toArray();
    
            return response()->json($results, 200);
        } catch (\Exception $e) {
            Log::error('Error in getCouponsByPagaduria:', ['message' => $e->getMessage(), 'trace' => $e->getTrace()]);
    
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
    
    
             



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
