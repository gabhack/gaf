<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Simulacion;
use Illuminate\Http\Request;

class VentaCarteraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // necesitamos el sufijo para saver si es ventas de cartera externas o no
        $sufijo = "";
        if ($request->sufijo === "ext") {
            $sufijo = $request->sufijo;
        }

        // Relaciones para listar el index de ventas cartera (ventas, estudiostr, ventas_detalles, compradores, subestados_ventas y cuotas)
        $ventas_cartera = DB::table('ventas' . $sufijo . ' AS ve')
            ->select('ve.id_venta', 've.nro_venta', 've.fecha', 've.tasa_venta', 've.modalidad_prima', 'et.id', 'et.valor_credito', 'co.nombre')
            ->selectRaw("SUM(CASE WHEN cu.pagada = '1' THEN cu.capital ELSE CASE WHEN cu.valor_cuota <> cu.saldo_cuota THEN IF (cu.valor_cuota - cu.saldo_cuota - cu.interes - cu.seguro > 0, cu.valor_cuota - cu.saldo_cuota - cu.interes - cu.seguro, 0) ELSE 0 END END) as capital_recaudado")
            ->join('ventas_detalle' . $sufijo . ' AS vd', 've.id_venta', '=', 'vd.id_venta')
            ->join('compradores AS co', 've.id_comprador', '=', 'co.id_comprador')
            ->join('estudiostr' . $sufijo . ' AS et', 'vd.estudio_id', '=', 'et.id')
            ->leftJoin('subestados_ventas AS sv', 'vd.id_subestadoventa', '=', 'sv.id_subestadoventa')
            ->leftJoin('cuotas AS cu', 'et.id', '=', 'cu.estudio_id')
            ->whereNotNull('ve.id_venta')
            ->groupBy('ve.id_venta', 've.nro_venta', 've.fecha', 've.tasa_venta', 've.modalidad_prima', 'et.id', 'et.valor_credito', 'co.nombre')
            ->get();

        // por el momento API para mirar los datos en el navegador
        return response()->json($ventas_cartera);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
