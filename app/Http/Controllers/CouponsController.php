<?php

namespace App\Http\Controllers;

use App\CouponsSedAtlantico;
use App\CouponsSedBolivar;
use App\CouponsSedCauca;
use App\CouponsSedChoco;
use App\CouponsSedFopep;
use App\CouponsSedMagdalena;
use App\CouponsSedValle;
use App\CouponsSemBarranquilla;
use App\CouponsSemPopayan;
use App\CouponsSemQuibdo;
use App\CouponsSemSahagun;
use App\CouponsSemCali;
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

        $models = [
            CouponsSedAtlantico::class => 'doc',
            CouponsSedBolivar::class => 'doc',
            CouponsSedCauca::class => 'doc',
            CouponsSedChoco::class => 'doc',
            CouponsSedFopep::class => 'doc',
            CouponsSedMagdalena::class => 'doc',
            CouponsSedValle::class => 'doc',
            CouponsSemBarranquilla::class => 'doc',
            CouponsSemPopayan::class => 'doc',
            CouponsSemQuibdo::class => 'doc',
            CouponsSemSahagun::class => 'doc',
            CouponsSemCali::class => 'doc',
        ];

        $results = [];

        foreach ($models as $model => $column) {
            $className = class_basename($model);

            if ($className == $couponType) {
                $results = $model::where($column, 'LIKE', '%' . $doc . '%')->get();
            }
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
