<?php

namespace App\Http\Controllers;

use App\ParametrosComparativa;
use App\DatamesGen;
use App\CouponsGen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;


class ParametrosComparativaController extends Controller
{
    public function index()
    {
        $parametros = ParametrosComparativa::first();
        if (!$parametros) {
            $parametros = new ParametrosComparativa();
        }
        return view('parametros_comparativa.index', compact('parametros'));
    }

    public function store(Request $request)
    {
        Log::info('Datos recibidos en store:', ['data' => $request->all()]);
        $parametros = ParametrosComparativa::firstOrNew(['id' => 1]);

        try {
            $parametros->fill([
                'tipo' => $request->input('tipo'),
                'masculino' => in_array('M', $request->input('generos', [])),
                'femenino' => in_array('F', $request->input('generos', [])),
                'edad_masculino' => $request->input('edad_M'),
                'tipo_contrato_masculino' => $request->input('tipo_contrato_M'),
                'cargo_masculino' => $request->input('cargo_M'),
                'horas_extras_masculino' => $request->has('horas_extras_M'),
                'asignacion_aa_masculino' => $request->has('asignacion_aa_M'),
                'asignacion_aaa_masculino' => $request->has('asignacion_aaa_M'),
                'edad_femenino' => $request->input('edad_F'),
                'tipo_contrato_femenino' => $request->input('tipo_contrato_F'),
                'cargo_femenino' => $request->input('cargo_F'),
                'horas_extras_femenino' => $request->has('horas_extras_F'),
                'asignacion_aa_femenino' => $request->has('asignacion_aa_F'),
                'asignacion_aaa_femenino' => $request->has('asignacion_aaa_F'),
                'codigo_cupon' => $request->input('codigo_cupon'),
            ]);

            $parametros->porcentaje_masculino = $request->input('porcentaje_M', 0);
            $parametros->porcentaje_femenino = $request->input('porcentaje_F', 0);

            Log::info('Parámetros llenados correctamente.');
            $parametros->save();
            Log::info('Parámetros guardados correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al guardar los parámetros:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }

        return response()->json(['success' => true]);
    }

    public function opciones()
    {
        try {
            $tiposContrato = DatamesGen::distinct()->pluck('tipo_contrato');
            $cargos = CouponsGen::distinct()->pluck('cargo');
            $pagadurias = CouponsGen::distinct()->pluck('pagaduria');

            Log::info('Opciones obtenidas:', ['tiposContrato' => $tiposContrato, 'cargos' => $cargos, 'pagadurias' => $pagadurias]);

            return response()->json([
                'tiposContrato' => $tiposContrato,
                'cargos' => $cargos,
                'pagadurias' => $pagadurias,
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener las opciones:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'No se pudieron cargar las opciones.'], 500);
        }
    }

    public function comparativa()
    {
        $parametros = ParametrosComparativa::first();
        return view('parametros_comparativa.comparativa', compact('parametros'));
    }

    public function upload(Request $request)
    {
        try {
            Log::info('Iniciando el proceso de comparación con el archivo Excel.');

            $parametros = ParametrosComparativa::first();
            if (!$parametros) {
                Log::error('No hay política general guardada.');
                return redirect()->back()->with('error', 'No hay política general guardada.');
            }

            if ($request->hasFile('excel_file')) {
                Log::info('Archivo Excel recibido correctamente.', ['fileName' => $request->file('excel_file')->getClientOriginalName()]);
                $filePath = $request->file('excel_file')->getRealPath();
                $spreadsheet = IOFactory::load($filePath);
                Log::info('Archivo Excel cargado correctamente.');
                $worksheet = $spreadsheet->getActiveSheet();
                $header = array_map('strtolower', $worksheet->rangeToArray('A1:' . $worksheet->getHighestColumn() . '1')[0]);
                Log::info('Encabezados del archivo Excel obtenidos.', ['header' => $header]);
                $cedulaIndex = array_search('cedula', $header);
                if ($cedulaIndex === false) {
                    Log::error('El encabezado "cedula" no se encontró en el archivo Excel.', ['header' => $header]);
                    return redirect()->back()->with('error', 'El archivo Excel no contiene una columna "cedula" o una variante reconocida.');
                }
                $rows = $worksheet->toArray(null, true, true, true);
                Log::info('Filas del archivo Excel procesadas.', ['rowsCount' => count($rows)]);
                $cedulas = [];
                foreach ($rows as $index => $row) {
                    if ($index === 0) continue; // Saltar encabezado
                    $cedula = isset($row[$cedulaIndex]) ? preg_replace('/\.\d+$/', '', strval($row[$cedulaIndex])) : null;
                    if ($cedula) {
                        $cedulas[] = $cedula;
                    }
                }

                $latestCoupon = CouponsGen::whereIn('id', function ($query) use ($cedulas) {
                    $query->selectRaw('MAX(id)')
                        ->from('couponsgen')
                        ->whereIn('doc', $cedulas)
                        ->groupBy('doc');
                })->first();
                
                $latestDatames = DatamesGen::whereIn('id', function ($query) use ($cedulas) {
                    $query->selectRaw('MAX(id)')
                        ->from('datamesgen')
                        ->whereIn('doc', $cedulas)
                        ->groupBy('doc');
                })->first();
                
                $matchingRecords = collect();
                
                if ($latestCoupon && $latestDatames) {
                    // Verificar si el registro de CouponsGen coincide con los parámetros
                    $couponMatches = true;
                    if ($parametros->tipo_contrato_masculino || $parametros->tipo_contrato_femenino) {
                        $couponMatches = false;
                        if ($parametros->tipo_contrato_masculino && strpos($latestCoupon->tipo_contrato, $parametros->tipo_contrato_masculino) !== false) {
                            $couponMatches = true;
                        }
                        if ($parametros->tipo_contrato_femenino && strpos($latestCoupon->tipo_contrato, $parametros->tipo_contrato_femenino) !== false) {
                            $couponMatches = true;
                        }
                    }
                    if ($parametros->codigo_cupon && $latestCoupon->code != $parametros->codigo_cupon) {
                        $couponMatches = false;
                    }
                
                    // Verificar si el registro de DatamesGen coincide con los parámetros
                    $datamesMatches = true;
                    if ($parametros->edad_masculino && $latestDatames->edad != $parametros->edad_masculino) {
                        $datamesMatches = false;
                    }
                    if ($parametros->edad_femenino && $latestDatames->edad != $parametros->edad_femenino) {
                        $datamesMatches = false;
                    }
                
                    // Solo añadir a los registros coincidentes si ambos coinciden
                    if ($couponMatches && $datamesMatches) {
                        $matchingRecords->push($latestCoupon);
                        $matchingRecords->push($latestDatames);
                    }
                }
                Log::info($matchingRecords);
                // Retornar la vista con los registros coincidentes
                return view('parametros_comparativa.results', compact('matchingRecords'));
                
            }

            return redirect()->back()->with('error', 'El archivo es requerido.');
        } catch (\Exception $e) {
            Log::error('Error al procesar el archivo de comparación:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error al procesar el archivo: ' . $e->getMessage());
        }
    }


}
