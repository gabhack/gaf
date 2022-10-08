<?php

namespace App\Http\Controllers;

use App\CouponsSedCauca;
use App\CouponsSedChoco;
use App\CouponsSedPopayan;
use App\CouponsSedQuibdo;
use App\Coupunsseccali;
use App\Coupunssecedu;
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
        $userDoc = $request->doc;
        $pagaduriaType = $request->pagaduria;

        $coupons = [];

        switch ($pagaduriaType) {
            case 'SEDCAUCA':
                $coupons = CouponsSedCauca::where('doc', $userDoc)->get();
                break;
            case 'SEDCHOCO':
                $coupons = CouponsSedChoco::where('doc', $userDoc)->get();
                break;
            case 'SEDPOPAYAN':
                $coupons = CouponsSedPopayan::where('doc', $userDoc)->get();
                break;
            case 'SEDQUIBDO':
                $coupons = CouponsSedQuibdo::where('doc', $userDoc)->get();
                break;
            case 'SECCALI':
                $coupons = Coupunsseccali::where('doc', $userDoc)->get();
                break;
            case 'SECEDUC':
                $coupons = Coupunssecedu::where('doc', $userDoc)->get();
                break;
            case 'FODE VALLE':
                $coupons = Coupunssecedu::where('doc', $userDoc)->get();
                break;
            default:
                $coupons = [];
                break;
        }

        return response()->json($coupons);
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
