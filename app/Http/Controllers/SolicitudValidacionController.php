<?php

namespace App\Http\Controllers;

use App\SolicitudValidacion;
use Illuminate\Http\Request;

class SolicitudValidacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = SolicitudValidacion::orderBy('id','DESC')->get();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = new SolicitudValidacion();
        $data->GuidConv = $request->GuidConv;
        $data->TipoValidacion = $request->TipoValidacion;
        $data->Asesor = $request->Asesor;
        $data->Sede = $request->Sede;
        $data->TipoDoc = $request->TipoDoc;
        $data->NumDoc = $request->NumDoc;
        $data->Email = $request->Email;
        $data->Celular = $request->Celular;
        $data->PrefCelular  = $request->PrefCelular;
        $data->ProcesoConvenioGuid = $request->ProcesoConvenioGuid;
        
        $data->save();

        return response()->json('Solicitud Guardada');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SolicitudValidacion  $solicitudValidacion
     * @return \Illuminate\Http\Response
     */
    public function show(SolicitudValidacion $solicitudValidacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SolicitudValidacion  $solicitudValidacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = SolicitudValidacion::findOrFail($id);
        $data->GuidConv = $request->GuidConv;
        $data->TipoValidacion = $request->TipoValidacion;
        $data->Asesor = $request->Asesor;
        $data->Sede = $request->Sede;
        $data->TipoDoc = $request->TipoDoc;
        $data->NumDoc = $request->NumDoc;
        $data->Email = $request->Email;
        $data->Celular = $request->Celular;
        $data->PrefCelular  = $request->PrefCelular;
        $data->ProcesoConvenioGuid = $request->ProcesoConvenioGuid;
        
        $data->update();

        return response()->json('Solicitud Actualizada');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SolicitudValidacion  $solicitudValidacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(SolicitudValidacion $solicitudValidacion)
    {
        $data = SolicitudValidacion::findOrFail($id);
        $data->delete();

        return response()->json('Solicitud Eliminada');
    }
}
