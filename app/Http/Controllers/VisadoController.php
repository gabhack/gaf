<?php

namespace App\Http\Controllers;

use App\Datamesseccali;
use App\Descapli;
use App\Descnoap;
use App\Embargosseceduc;
use App\Visado;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Barryvdh\DomPDF\Facade\Pdf;

class VisadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function historialConsultas(Request $request)
    {
        // $data_formulario = $request->data;
        // $doc = $request->data['doc'];
        // $historial_consultas = \App\Descapli::Where('doc',$doc)->get();
        $historial_consultas = \App\Visado::query()->orderByDesc('created_at')->get();
        $resultados = json_decode($historial_consultas);
        if ($resultados == "" or $resultados == null) {
            return response()->json(['message' => 'No se encontraron registros.', 'data' => $resultados], 200);
        } else {
            return response()->json(['message' => 'Consulta exitosa.', 'data' => $resultados], 200);
        }
    }

    public function detalleConsulta(Request $request)
    {
        // $data_formulario = $request->data;
        // $doc = $request->data['doc'];
        // $historial_consultas = \App\Descapli::Where('doc',$doc)->get();
        $detalle_consulta = \App\Visado::where('id', $request->id)->first();
        $detalle_consulta = json_decode($detalle_consulta);
        $info_datames = \App\DataMes::Where('doc', $detalle_consulta->ced)->first();
        $info_fechavinc = \App\FechaVinc::Where('doc', $detalle_consulta->ced)->first();
        $datamesfidu = \App\Datamesfidu::Where('doc', $detalle_consulta->ced)->first();
        $datamesseceduc = \App\Datamesseceduc::Where('doc', $detalle_consulta->ced)->first();
        $datamesseccali = Datamesseccali::where('doc', $detalle_consulta->ced)->first();
        $embargosedu = Embargosseceduc::where('doc', $detalle_consulta->ced)->get();
        $descapli = Descapli::where('doc', $detalle_consulta->ced)->get();
        $descnoap = Descnoap::where('doc',$detalle_consulta->ced)->get();
        $resultado = [];
        $info_obligaciones = $detalle_consulta->info_obligaciones;
        $resultado['info_datames'] = $info_datames;
        $resultado['info_fechavinc'] = $info_fechavinc;
        $resultado['info_obligaciones'] = json_decode($info_obligaciones);
        $resultado['detalle_consulta'] = $detalle_consulta;
        $resultado['datamesfidu'] = $datamesfidu;
        $resultado['datamesseceduc'] = $datamesseceduc;
        $resultado['datamesseccali'] = $datamesseccali;
        $resultado['embargosedu'] = $embargosedu;
        $resultado['descapli'] = $descapli;
        $resultado['descnoap'] = $descnoap;
        if ($resultado == "" or $resultado == null) {
            return response()->json(['message' => 'No se encontraron registros.', 'data' => $resultado], 200);
        } else {
            return response()->json(['message' => 'Consulta exitosa.', 'data' => $resultado], 200);
        }
    }

    public function pdfDetalle(Request $request)
    {
        $detalle_consulta = \App\Visado::where('id', $request->id_consulta)->first();
        $detalle_consulta = json_decode($detalle_consulta);
        $info_datames = \App\DataMes::Where('doc', $detalle_consulta->ced)->first();
        $info_fechavinc = \App\FechaVinc::Where('doc', $detalle_consulta->ced)->first();
        $htmldata = "
      <html>
        <div style='font: bold 90% monospace; font-size: 5px; display:flex;'>Consecutivo: {$request->id_consulta}</div>
        <br>
        <div style='font: bold 90% monospace; font-size: 5px; display:flex;'>Estado: {$detalle_consulta->estado}</div>
        <br>
        <div style='font: bold 90% monospace; font-size: 5px; display:flex;'>Fecha consulta: {$detalle_consulta->fconsultaami}</div>
        <br>
        <div style='font: bold 90% monospace; font-size: 5px; display:flex;'>Cedula: {$detalle_consulta->ced}</div>
        <br>
        <div style='font: bold 90% monospace; font-size: 5px; display:flex;'>Nombre: {$detalle_consulta->nombre}</div>
        <br>
        <div style='font: bold 90% monospace; font-size: 5px; display:flex;'>Tipo de credito: {$detalle_consulta->tcredito}</div>
        <br>
        <div style='font: bold 90% monospace; font-size: 5px; display:flex;'>Cupo Lib inversión: {$detalle_consulta->clibinv}</div>
        <br>
        <div style='font: bold 90% monospace; font-size: 5px; display:flex;'>Pagaduria: {$detalle_consulta->pagaduria}</div>
        <br>
        <div style='font: bold 90% monospace; font-size: 5px; display:flex;'>Vr Credito: {$detalle_consulta->vcredito}</div>
        <br>
        <div style='font: bold 90% monospace; font-size: 5px; display:flex;'>Vr Desembolso: {$detalle_consulta->vdesembolso}</div>
        <br>
        <div style='font: bold 90% monospace; font-size: 5px; display:flex;'>Plazo: {$detalle_consulta->plazo}</div>
        <br>
        <div style='font: bold 90% monospace; font-size: 5px; display:flex;'>Cuota: {$detalle_consulta->cuotacredito}</div>
        <br>
        <div style='font: bold 90% monospace; font-size: 5px; display:flex;'>Aprobado: {$detalle_consulta->aprobado}</div>
        <br>
        <div style='font: bold 90% monospace; font-size: 5px; display:flex;'>% incorporacion: {$detalle_consulta->porcincorp}</div>
        <br>
        <div style='font: bold 90% monospace; font-size: 5px; display:flex;'>Cuota max incorporación: {$detalle_consulta->cmaxincorp}</div>
        <br>
        <div style='font: bold 90% monospace; font-size: 5px; display:flex;'>Fecha respuesta: {$detalle_consulta->frespuesta}</div>
        <br>
        <div style='font: bold 90% monospace; font-size: 5px; display:flex;'>Fecha vinculacion: {$detalle_consulta->fvinculacion}</div>
        <br>
        <div style='font: bold 90% monospace; font-size: 5px; display:flex;'>Tipo vinculacion: {$detalle_consulta->tvinculacion}</div>
        <br>
      </html>
      ";
        $dompdf = new DOMPDF();
        $dompdf->load_html($htmldata);
        $dompdf->render();
        return $htmldata;
        // $pdf = PDF::loadView('pdf.invoice', $htmldata);
        // return $pdf->download('invoice.pdf');

        // return $dompdf->download("Consulta{$request->id_consulta}.pdf");
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->buildData($request);
        $info = $data[$request->pagaduria];

        $nombre = "";
        if (isset($info['nombenef'])) {
            $nombre = $info['nombenef'];
        } elseif (isset($info['nomp'])) {
            $nombre = $info['nomp'];
        }

        $response = Visado::create([
            'ced' => $info['doc'],
            'nombre' => $nombre,
            'pagaduria' => $request->pagaduria,
            'entidad' => $request->pagaduria,
            'tipo_consulta' => 'Individual',
        ]);

        return response()->json($response);
    }


    protected function buildData($request)
    {
        $data = [
            'FOPEP' => $request->pagadurias['datames'],
            'FIDUPREVISORA' => $request->pagadurias['datamesfidu'],
            'FODE VALLE' => $request->pagadurias['datamesseceduc'],
            'SECCALI' => $request->pagadurias['datamesseccali'],
        ];

        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Visado $visado
     * @return \Illuminate\Http\Response
     */
    public function show(Visado $visado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Visado $visado
     * @return \Illuminate\Http\Response
     */
    public function edit(Visado $visado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Visado $visado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Visado $visado)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Visado $visado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Visado $visado)
    {
        //
    }
}
