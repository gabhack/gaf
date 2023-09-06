<?php

namespace App\Http\Controllers;

use App\Estudiostr;
use Illuminate\Http\Request;
use App\Simulacion;
use Illuminate\Support\Facades\DB as DB;

class TesoreriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $options = $this->getOptions($request);

        return view("estudios/tesoreria")->with($options);
    }

    public function getOptions(Request $request)
    {
        //Parametros de entrada para busqueda y filtrado
        $search = '';
        $fechadesde = '';
        $fechahasta = '';
        $asesor = array();
        $periodo = '';
        $decision = '';

        if (isset($request->busq)) {
            $search = $request->busq;
        }

        if (isset($request->filtro['fecha_desde']) && $request->filtro['fecha_desde'] !== '') {
            $fechadesde = $request->filtro['fecha_desde'];
        } else {
            $fechadesde = '1800-01-01';
        }

        if (isset($request->filtro['fecha_hasta']) && $request->filtro['fecha_hasta'] !== '') {
            $fechahasta = $request->filtro['fecha_hasta'];
        } else {
            $fechahasta = date("Y-m-d");
        }

        if (isset($request->filtro['asesor']) && $request->filtro['asesor'] !== '') {
            $asesor = '';
            $asesor = $request->filtro['asesor'];
        }

        if (isset($request->filtro['decision']) && $request->filtro['decision'] !== '') {
            $decision = '';
            $decision = $request->filtro['decision'];
        }

        if (isset($request->filtro['periodo']) && $request->filtro['periodo'] !== '') {
            $periodo = $request->filtro['periodo'];
        }

        //Query
        $subestados_tesoreria = [58, 2, 54];

        $lista = Estudiostr::where('decision', 'APRO')
            // ->orWhere(function ($query) use ($subestados_tesoreria) {
            //     $query->where('estado', 'EST')
            //         ->where('decision', 'VIABLE')
            //         ->whereIn('subestado_id', $subestados_tesoreria);
            // })
            ->WhereHas('asesor', function ($q) use ($asesor) {
                if (!is_array($asesor)) {
                    $q->where('id', $asesor);
                }
            })
            ->where(function ($q) use ($decision) {
                if ($decision !== '') {
                    $q->where('decision', $decision);
                }
            })
            ->where(function ($q) use ($periodo) {
                if ($periodo !== '') {
                    $q->where('periodo_estudio', $periodo);
                }
            })
            ->whereBetween('fecha', [$fechadesde, $fechahasta])
            ->WhereHas('cliente', function ($q) use ($search) {
                $q->where('nombres', 'like', '%' . $search . '%');
                $q->orWhere('apellidos', 'like', '%' . $search . '%');
                $q->orWhere('documento', 'like', '%' . $search . '%');
                $q->orWhere(DB::raw('CONCAT_WS(" ", nombres, apellidos)'), 'like', '%' . $search . '%');
            });

        //Preparar la salida
        $listaOut = $lista->paginate(5)->appends(request()->except('page'));
        $links = $listaOut->links();
        $options = array(
            "lista" => $listaOut,
            "links" => $links
        );

        //Parametros de busqueda y filtrado para front
        if (isset($request->busq) && $request->busq !== '') {
            $options['busq'] = $request->busq;
        }

        if (isset($request->filtro['fecha_desde']) && $request->filtro['fecha_desde'] !== '') {
            $options['filtro']['fecha_desde'] = $request->filtro['fecha_desde'];
        }

        if (isset($request->filtro['fecha_hasta']) && $request->filtro['fecha_hasta'] !== '') {
            $options['filtro']['fecha_hasta'] = $request->filtro['fecha_hasta'];
        }

        if (isset($request->filtro['asesor']) && $request->filtro['asesor'] !== '') {
            $options['filtro']['asesor'] = $request->filtro['asesor'];
        }

        if (isset($request->filtro['decision']) && $request->filtro['decision'] !== '') {
            $options['filtro']['decision'] = $request->filtro['decision'];
        }

        if (isset($request->filtro['periodo']) && $request->filtro['periodo'] !== '') {
            $options['filtro']['periodo'] = $request->filtro['periodo'];
        }

        return $options;
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
