<?php

namespace App\Http\Controllers;

use App\dataCotizer;
use Illuminate\Http\Request;

class dataCotizerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Query
        $lista = dataCotizer::orderBy('id', 'desc');

        //Preparar la salida
        $listaOut = $lista->paginate(20)->appends(request()->except('page'));
        $links = $listaOut->links();
        $options = array(
            "lista" => $listaOut,
            "links" => $links
        );

        //Parametros de busqueda y filtrado para front
        if (isset($request->busq) && $request->busq !== '') {
            $options['busq'] = $request->busq;
        }

        return view("cotizer/index")->with($options);
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
        $cotizador = new dataCotizer($request->all());
        $cotizador->save();
        return $cotizador;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\dataCotizer  $dataCotizer
     * @return \Illuminate\Http\Response
     */
    public function show(dataCotizer $dataCotizer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\dataCotizer  $dataCotizer
     * @return \Illuminate\Http\Response
     */
    public function edit(dataCotizer $dataCotizer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\dataCotizer  $dataCotizer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, dataCotizer $dataCotizer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\dataCotizer  $dataCotizer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        dataCotizer::find($id)->delete();
        return redirect()->route('cotizer-data.index');
    }
}
