<?php

namespace App\Http\Controllers;

use App\DataMes;
use Illuminate\Http\Request;
use DB;
use Excel;

class DataMesController extends Controller
{
  public function import (Request $request){    
    // $j = [];
    try {
      if($request->hasFile('file')){
        $path = $request->file('file')->getRealPath();        
        $data = Excel::selectSheetsByIndex(0)->load($path, function ($reader){})->get();
        dd($data);
          if($data->count()){
            $i = 2;
            $position = 0;
            dd($data);

            foreach ($data as $key => $value) {
              // $x= trim($value->codigo.$value->productoservicio.$value->precio_venta.$value->tipo_de_impuesto.
              //   $value->porcentaje_impuesto);
              // if(trim($value->codigo) == ''){
              //   $value->codigo = null;
              // }
              // if($x == ''){
              //
              // }
              // else{
                $arr[] = [
                  'fondo' => $value->fondo,
                  'td' => $value->td,
                  'doc' => $value->doc,
                  'x' => $value->x,
                  'nomp' => $value->nomp,
                  'fecnacimient' => $value->fecnacimient,
                  'dir' => $value->dir,
                  'dpto' => $value->dpto,
                  'mnpio' => $value->mnpio,
                  'tp' => $value->tp,
                  'nbanco' => $value->nbanco,
                  'sucursal' => $value->sucursal,
                  'tel' => $value->tel,
                  'cel' => $value->cel,
                  'correo' => $value->correo,
                  'vpension' => $value->vpension,
                  'vsalud' => $value->vsalud,
                  'vembargos' => $value->vembargos,
                  'vdesc' => $value->vdesc,
                  'cupo' => $value->cupo
               ];
                $i++;
                $position++;
              // }
            }
            $cant_reg = $i -2;
            if(!empty($arr)){
              foreach ($array as $key => $value) {
                $datos = [
                  'fondo' => $value->fondo,
                  'td' => $value->td,
                  'doc' => $value->doc,
                  'x' => $value->x,
                  'nomp' => $value->nomp,
                  'fecnacimient' => $value->fecnacimient,
                  'dir' => $value->dir,
                  'dpto' => $value->dpto,
                  'mnpio' => $value->mnpio,
                  'tp' => $value->tp,
                  'nbanco' => $value->nbanco,
                  'sucursal' => $value->sucursal,
                  'tel' => $value->tel,
                  'cel' => $value->cel,
                  'correo' => $value->correo,
                  'vpension' => $value->vpension,
                  'vsalud' => $value->vsalud,
                  'vembargos' => $value->vembargos,
                  'vdesc' => $value->vdesc,
                  'cupo' => $value->cupo
                ];
                DB::table('datames')->insert($datos);
              }
            }
          }
        }
        else {
          throw new \Exception("Debe cargar un archivo", 1);
        }
        DB::commit();
        $j['success'] = true;
        $j['msg'] = "Cargue realizado";
        $j['data'] = "";
    }
    catch (\Illuminate\Database\Query\Exception $e) {
      $j = $e;
      DB::rollBack();
    }
    catch (\Exception $e) {
      $j['success'] = false;
      $j['msg'] = "{$e->getMessage()}";
      $j['e'] = $e->getMessage();
      DB::rollBack();
    }

    return response()->json($j);
  }

    public function dumpDataMes(){
        DB::table('datames')->delete();
        return response()->json(['message'=>'Datos de tabla DataMes Borrada'],200);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\DataMes  $dataMes
     * @return \Illuminate\Http\Response
     */
    public function show(DataMes $dataMes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DataMes  $dataMes
     * @return \Illuminate\Http\Response
     */
    public function edit(DataMes $dataMes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DataMes  $dataMes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DataMes $dataMes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DataMes  $dataMes
     * @return \Illuminate\Http\Response
     */
    public function destroy(DataMes $dataMes)
    {
        //
    }
}
