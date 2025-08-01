<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use DB;

class ReportePlantaExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function view(): View
    {
        $queryDB = "SELECT us.name as nombre_comercial, CASE WHEN us.freelance = 1 THEN 'SI' ELSE 'NO' END as freelance, of.oficina as oficina, us.email, us.telefono, CASE WHEN us.estado = 1 THEN 'SI' ELSE 'NO' END as estado from users us left join oficinas_usuarios ou on us.id = ou.id_usuario left join oficinas of on ou.id_oficina = of.id where us.tipo = 'COMERCIAL'";

        $reporte_planta_comerciales = DB::select($queryDB);

        return view('exports.reporte_planta_comerciales', [
            'reporte_planta_comerciales' => $reporte_planta_comerciales,
        ]);
    }
}
