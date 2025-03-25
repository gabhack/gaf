<?php

namespace App\Http\Controllers;

use App\DatamesFidu;
use App\DatamesFopep;
use App\DatamesSedValle;
use App\DatamesSemCali;
use App\Descapli;
use App\Descnoap;
use App\EmbargosSedValle;
use App\FechaVinc;
use App\Visado;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

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
        // $historial_consultas = Descapli::Where('doc',$doc)->get();
        $historial_consultas = Visado::query()->orderByDesc('created_at')->paginate(15);

        $resultados = $historial_consultas;

        if (count($historial_consultas) == 0) {
            return response()->json([
                'message' => 'No se encontraron resultados',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Se encontraron resultados',
                'data' => $resultados,
            ], 200);
        }
    }

    public function detalleConsulta(Request $request)
    {
        // $data_formulario = $request->data;
        // $doc = $request->data['doc'];
        // $historial_consultas = Descapli::Where('doc',$doc)->get();
        $detalle_consulta = Visado::where('id', $request->id)->first();
        $detalle_consulta = json_decode($detalle_consulta);
        $info_datames = DatamesFopep::Where('doc', $detalle_consulta->ced)->first();
        $info_fechavinc = FechaVinc::Where('doc', $detalle_consulta->ced)->first();
        $datamesfidu = DatamesFidu::Where('doc', $detalle_consulta->ced)->first();
        $datamesSedValle = DatamesSedValle::Where('doc', $detalle_consulta->ced)->first();
        $datamesSemCali = DatamesSemCali::where('doc', $detalle_consulta->ced)->first();
        $embargosedu = EmbargosSedValle::where('doc', $detalle_consulta->ced)->get();
        $descapli = Descapli::where('doc', $detalle_consulta->ced)->get();
        $descnoap = Descnoap::where('doc', $detalle_consulta->ced)->get();
        $resultado = [];
        $info_obligaciones = $detalle_consulta->info_obligaciones;
        $resultado['info_datames'] = $info_datames;
        $resultado['info_fechavinc'] = $info_fechavinc;
        $resultado['info_obligaciones'] = json_decode($info_obligaciones);
        $resultado['detalle_consulta'] = $detalle_consulta;
        $resultado['datamesfidu'] = $datamesfidu;
        $resultado['datamessedvalle'] = $datamesSedValle;
        $resultado['datamessemcali'] = $datamesSemCali;
        $resultado['embargosedu'] = $embargosedu;
        $resultado['descapli'] = $descapli;
        $resultado['descnoap'] = $descnoap;

        if ($resultado == '' or $resultado == null) {
            return response()->json(['message' => 'No se encontraron registros.', 'data' => $resultado], 200);
        } else {
            return response()->json(['message' => 'Consulta exitosa.', 'data' => $resultado], 200);
        }
    }

    public function pdfDetalle(Request $request)
    {
        $detalle_consulta = \App\Visado::where('id', $request->id_consulta)->first();
        $detalle_consulta = json_decode($detalle_consulta);
        $info_datames = \App\DatamesFopep::Where('doc', $detalle_consulta->ced)->first();
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

  
    public function store(Request $request)
    {
        $user = auth()->user();
    
        \Log::info("📥 Iniciando proceso de creación de visado.", [
            'usuario' => $user->email,
            'request' => $request->all()
        ]);
    
        $data = $request->validate([
            'conc'             => 'nullable|string',
            'estado'           => 'nullable|string',
            'causal'           => 'nullable|string',
            'fconsultaami'     => 'nullable|date',
            'doc'              => 'required|string',
            'nombre'           => 'required|string',
            'pagaduria'        => 'required|string',
            'tcredito'         => 'nullable|string',
            'clibinv'          => 'nullable|numeric',
            'ccompra'          => 'nullable|numeric',
            'entidad'          => 'nullable|string',
            'pagare'           => 'nullable|string',
            'vcredito'         => 'nullable|numeric',
            'vdesembolso'      => 'nullable|numeric',
            'plazo'            => 'required|numeric',
            'cuotacredito'     => 'nullable|numeric',
            'monto'            => 'nullable|numeric',
            'aprobado'         => 'nullable|string',
            'porcincorp'       => 'nullable|numeric',
            'cmaxincorp'       => 'nullable|numeric',
            'frespuesta'       => 'nullable|date',
            'fvinculacion'     => 'nullable|date',
            'tvinculacion'     => 'nullable|string',
            'tipo_consulta'    => 'nullable|string',
            'info_obligaciones'=> 'nullable|string',
            'consultant_email' => 'nullable|email',
            'consultant_name'  => 'nullable|string',
            'observacion'      => 'nullable|string',
            'creditId'         => 'nullable|numeric',
        ]);
    
        DB::beginTransaction();
    
        try {
            $visado = Visado::create([
                'conc'              => $data['conc'] ?? null,
                'estado'            => $data['estado'] ?? null,
                'causal'            => $data['causal'] ?? null,
                'fconsultaami'      => $data['fconsultaami'] ?? null,
                'ced'               => $data['doc'],
                'nombre'            => $data['nombre'],
                'pagaduria'         => $data['pagaduria'],
                'tcredito'          => $data['tcredito'] ?? null,
                'clibinv'           => $data['clibinv'] ?? null,
                'ccompra'           => $data['ccompra'] ?? null,
                'entidad'           => $data['entidad'] ?? $data['pagaduria'],
                'pagare'            => $data['pagare'] ?? null,
                'vcredito'          => $data['vcredito'] ?? null,
                'vdesembolso'       => $data['vdesembolso'] ?? null,
                'plazo'             => $data['plazo'],
                'monto'         => isset($data['monto']) ? (int) floatval($data['monto']) : null,
'cuotacredito'  => isset($data['cuotacredito']) ? (int) floatval($data['cuotacredito']) : null,
                'aprobado'          => $data['aprobado'] ?? null,
                'porcincorp'        => $data['porcincorp'] ?? null,
                'cmaxincorp'        => $data['cmaxincorp'] ?? null,
                'frespuesta'        => $data['frespuesta'] ?? null,
                'fvinculacion'      => $data['fvinculacion'] ?? null,
                'tvinculacion'      => $data['tvinculacion'] ?? null,
                'tipo_consulta'     => $data['tipo_consulta'] ?? 'Diamond',
                'info_obligaciones' => $data['info_obligaciones'] ?? null,
                'consultant_email'  => $data['consultant_email'] ?? $user->email,
                'consultant_name'   => $data['consultant_name'] ?? $user->name,
                'observacion'       => $data['observacion'] ?? null,
            ]);
    
            DB::commit();
    
            \Log::info("✅ Visado creado exitosamente.", [
                'visado_id' => $visado->id,
                'ced'       => $visado->ced,
                'usuario'   => $user->email
            ]);
    
            return response()->json($visado, 201);
        } catch (\Throwable $th) {
            DB::rollBack();
    
            \Log::error("❌ Error creando visado.", [
                'mensaje' => $th->getMessage(),
                'usuario' => $user->email,
                'request' => $request->all(),
                'trace'   => $th->getTraceAsString()
            ]);
    
            return response()->json([
                'message' => 'Error al guardar la consulta',
                'error'   => $th->getMessage(),
            ], 500);
        }
    }
    
    
    public function update(Request $request, $id)
    {
        $visado = Visado::find($id);
        $visado->estado       = $request->estado;
        $visado->cuotacredito = $request->cuotacredito;
        $visado->monto        = $request->monto;
        $visado->causal       = $request->causal;
        // Ahora guardamos la observación
        $visado->observacion  = $request->observacion ?? null;
    
        $visado->save();
    }
    
}
