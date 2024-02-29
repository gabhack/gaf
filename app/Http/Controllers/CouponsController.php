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

class CouponsController extends Controller
{
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
        $pagaduria = $request->pagaduria;
        $concept = $request->concept;
        $code = $request->code;

        $models = [
            CouponsSedAtlantico::class,
            CouponsSedBolivar::class,
            CouponsSedCauca::class,
            CouponsSedChoco::class,
            CouponsSedCaldas::class,
            CouponsSedCordoba::class,
            CouponsSedFopep::class,
            CouponsSedMagdalena::class,
            CouponsSedValle::class,
            CouponsSemBarranquilla::class,
            CouponsSemPopayan::class,
            CouponsSemMonteria::class,
            CouponsSemQuibdo::class,
            CouponsSemSahagun::class,
            CouponsSemCali::class,
            CouponsGen::class,
        ];

        $results = [];

        foreach ($models as $model) {
            $results = array_merge($results, $model::where('pagaduria', 'LIKE', '%' . $pagaduria . '%')
                ->where('concept', 'LIKE', '%' . $concept . '%')
                ->where('code', 'LIKE', '%' . $code . '%')
                ->get()->toArray());
        }

        return response()->json($results, 200);
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
