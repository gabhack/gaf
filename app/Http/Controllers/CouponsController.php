<?php

namespace App\Http\Controllers;

use App\CouponsSedAtlantico;
use App\CouponsSedBarranquilla;
use App\CouponsSedBolivar;
use App\CouponsSedCauca;
use App\CouponsSedChoco;
use App\CouponsSedFopep;
use App\CouponsSedMagdalena;
use App\CouponsSedPopayan;
use App\CouponsSedQuibdo;
use App\CouponsSemSahagun;
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
            case 'SEDBARRANQUILLA':
                $coupons = CouponsSedBarranquilla::where('doc', $userDoc)->get();
                break;
            case 'SEDATLANTICO':
                $coupons = CouponsSedAtlantico::where('doc', $userDoc)->get();
                break;
            case 'SEDCAUCA':
                $coupons = CouponsSedCauca::where('doc', $userDoc)->get();
                break;
            case 'SEDMAGDALENA':
                $coupons = CouponsSedMagdalena::where('doc', $userDoc)->get();
                break;
            case 'SEDBOLIVAR':
                $coupons = CouponsSedBolivar::where('doc', $userDoc)->get();
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
            case 'SEMSAHAGUN':
                $coupons = CouponsSemSahagun::where('doc', $userDoc)->get();
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
            case 'FOPEP':
                $coupons = CouponsSedFopep::where('doc', $userDoc)->get();
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
