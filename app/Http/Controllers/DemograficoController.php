<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DatamesGen;
use App\CouponsGen;
use App\EmbargosGen;
use App\DescuentosGen;
use App\Colpensiones;
use App\Fiducidiaria;
use App\DemographicConsultLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
class DemograficoController extends Controller
{
    public function upload(Request $request)
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '600');

        Log::info('Inicio del proceso de carga de archivo');

        try {
            $file = $request->file('file');
            if (!$file || !$file->isValid()) {
                throw new \Exception('Error uploading file');
            }

            Log::info('Archivo cargado correctamente');

            $filePath = $file->getRealPath();
            Log::info('Inicio de la carga del archivo Excel');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($filePath);
            Log::info('Archivo Excel cargado correctamente');

            $worksheet = $spreadsheet->getActiveSheet();
            Log::info('Inicio de la lectura del encabezado');
            $highestColumn = $worksheet->getHighestColumn();
            $headerRange = 'A1:' . $highestColumn . '1';
            $header = $worksheet->rangeToArray($headerRange)[0];
            Log::info('Excel header:', ['header' => $header]);

            $cedulasColumn = array_search('cedulas', array_map('strtolower', $header));
            if ($cedulasColumn === false) {
                throw new \Exception('No se encontró la columna "cedulas"');
            }

            Log::info('Cedulas column index:', ['cedulasColumn' => $cedulasColumn]);

            $cedulas = [];
            foreach ($worksheet->getRowIterator(2) as $row) {
                $cell = $worksheet->getCellByColumnAndRow($cedulasColumn + 1, $row->getRowIndex());
                $cedulas[] = $cell->getValue();
            }

            Log::info('Cedulas extraídas:', ['cedulas' => $cedulas]);

            $request->session()->put('cedulas', $cedulas);

            Log::info('Fin del proceso de carga de archivo');
            return response()->json(['message' => 'Cédulas cargadas correctamente']);
        } catch (\Exception $e) {
            Log::error('Error processing file upload: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json(['error' => 'Error procesando el archivo: ' . $e->getMessage()], 500);
        }
    }

    public function fetchPaginatedResults(Request $request)
{
    Log::info('Inicio del proceso de fetchPaginatedResults');

    try {
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 30);
        
        $mes = $request->query('mes');
        $año = $request->query('año');
        
        if (!$mes || !$año) {
            return response()->json(['error' => 'Mes y año son requeridos'], 400);
        }

        $cedulas = $request->session()->get('cedulas', []);
        $offset = ($page - 1) * $perPage;
        $cedulasChunk = array_slice($cedulas, $offset, $perPage);

        if (empty($cedulasChunk)) {
            Log::info('No se encontraron cédulas para esta página');
            return response()->json(['data' => [], 'total' => count($cedulas)], 200);
        }

        // Aquí es donde se puede lanzar una excepción
        $results = $this->processCedulas($cedulasChunk, $mes, $año);

        Log::info('Fin del proceso de fetchPaginatedResults');
        return response()->json([
            'data' => $results,
            'total' => count($cedulas),
            'page' => $page,
            'perPage' => $perPage
        ]);
    } catch (\Exception $e) {
        Log::error('Error en fetchPaginatedResults: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        return response()->json(['error' => 'Error en la obtención de resultados paginados'], 500);
    }
}
private function processCedulas($cedulas, $mes, $año)
{
    try {
        $startDate = Carbon::createFromFormat('Y-m', $año . '-' . $mes)->startOfMonth()->toDateString();
        $endDate = Carbon::createFromFormat('Y-m', $año . '-' . $mes)->endOfMonth()->toDateString();

        $latestRecords = DatamesGen::whereIn('doc', $cedulas)
            ->orderBy('created_at', 'desc')
            ->get()
            ->keyBy('doc');

        $colpensionesDocs = Colpensiones::whereIn('documento', $cedulas)->pluck('documento')->toArray();
        $fiducidiariaDocs = Fiducidiaria::whereIn('documento', $cedulas)->pluck('documento')->toArray();

        $results = collect();
        $salarioMinimo = 1300000;

        foreach ($cedulas as $cedula) {
            $cedulaStr = (string)$cedula;

            $existsInColpensiones = in_array($cedulaStr, $colpensionesDocs);
            $existsInFiducidiaria = in_array($cedulaStr, $fiducidiariaDocs);

            // Obtener el último registro de DatamesGen para 'situacion_laboral'
            $record = $latestRecords->get($cedulaStr);
            $situacionLaboral = $record ? $record->situacion_laboral : 'No disponible';

            // Obtener el cargo desde CouponsGen (cualquier registro)
            $cargoRecord = CouponsGen::where('doc', $cedulaStr)
                ->whereBetween('inicioperiodo', [$startDate, $endDate])
                ->first();
            $cargo = $cargoRecord ? $cargoRecord->cargo : 'No disponible';

            Log::info("Procesando cédula: {$cedulaStr}", [
                'colpensiones' => $existsInColpensiones,
                'fiducidiaria' => $existsInFiducidiaria,
                'cargo' => $cargo,
                'situacion_laboral' => $situacionLaboral
            ]);

            $pagadurias = CouponsGen::where('doc', $cedulaStr)
                ->whereBetween('inicioperiodo', [$startDate, $endDate])
                ->distinct()
                ->pluck('pagaduria');

            if ($pagadurias->isEmpty()) {
                $pagadurias = collect(['No disponible']);
            }

            $edad = null;
            $fechaNacimiento = null;
            if ($record && $record->fecha_nacimiento) {
                $cleanedFechaNacimiento = trim($record->fecha_nacimiento); 
                try {
                    $fechaNacimiento = Carbon::createFromFormat('d/m/Y', $cleanedFechaNacimiento);
                    if (!$record->edad) {
                        $edad = $fechaNacimiento->age;
                    }
                    Log::info("Fecha de nacimiento y edad calculada para {$cedulaStr}: ", [
                        'fecha_nacimiento' => $record->fecha_nacimiento,
                        'edad' => $edad
                    ]);
                } catch (\Exception $e) {
                    Log::error("Error al procesar la fecha de nacimiento para {$cedulaStr}: " . $e->getMessage());
                }
            } else {
                Log::warning("Fecha de nacimiento no disponible para {$cedulaStr}");
            }

            foreach ($pagadurias as $pagaduria) {
                $cupones = CouponsGen::where('doc', $cedulaStr)
                    ->where('pagaduria', $pagaduria)
                    ->where('egresos', '>', 0)
                    ->whereBetween('inicioperiodo', [$startDate, $endDate])
                    ->get();

                $embargos = EmbargosGen::where('doc', $cedulaStr)
                    ->where('pagaduria', $pagaduria)
                    ->whereBetween('nomina', [$startDate, $endDate])
                    ->select('docdeman', 'entidaddeman', 'fembini', 'temb as valor')
                    ->get();

                $descuentos = DescuentosGen::where('doc', $cedulaStr)
                    ->where('pagaduria', $pagaduria)
                    ->whereBetween('nomina', [$startDate, $endDate])
                    ->get()
                    ->filter(function ($descuento) {
                        return $descuento->mliquid != 'ALERTA';
                    });

                $ingresoRecord = CouponsGen::where('doc', $cedulaStr)
                    ->where('pagaduria', $pagaduria)
                    ->where('code', 'SUEBA')
                    ->whereBetween('inicioperiodo', [$startDate, $endDate])
                    ->orderBy('inicioperiodo', 'desc')
                    ->first();

                $ingreso = $ingresoRecord ? $ingresoRecord->ingresos : 0;
                Log::info("Ingreso encontrado para {$cedulaStr}: ", ['ingreso' => $ingreso]);

                $increase = 0;
                if ($cargo == 'Rector Institucion Educativa Completa') {
                    $increase = $ingreso * 0.3;
                } elseif ($cargo == 'Coordinador') {
                    $increase = $ingreso * 0.2;
                } elseif ($cargo == 'Director De Nucleo') {
                    $increase = $ingreso * 0.35;
                }
                $ingreso += $increase;

                Log::info("Ingreso ajustado por tipo de cargo para {$cedulaStr}: ", ['ingreso' => $ingreso]);

                $descuento = 0.08;
                if (in_array($pagaduria, ['FOPEP', 'FIDUPREVISORA'])) {
                    if ($ingreso == $salarioMinimo) {
                        $descuento = 0.04;
                    } elseif ($ingreso > $salarioMinimo && $ingreso < $salarioMinimo * 2) {
                        $descuento = 0.08;
                    } elseif ($ingreso >= $salarioMinimo * 2) {
                        $descuento = 0.12;
                    }
                }
                $ingresoConDescuento = $ingreso - ($ingreso * $descuento);

                Log::info("Ingreso con descuento aplicado para {$cedulaStr}: ", ['ingresoConDescuento' => $ingresoConDescuento]);

                $egresos = $cupones->sum('egresos');
                Log::info("Total egresos para {$cedulaStr}: ", ['egresos' => $egresos]);

                $cupoLibreInversion = ($ingresoConDescuento / 2) - $egresos;

                Log::info("Cupo libre de inversión calculado para {$cedulaStr}: ", [
                    'cupoLibreInversion' => $cupoLibreInversion,
                    'ingresoConDescuento' => $ingresoConDescuento,
                    'egresos' => $egresos,
                ]);

                $results->push([
                    'doc' => $cedulaStr,
                    'nombre_usuario' => $record ? $record->nombre_usuario : null,
                    'tipo_contrato' => $record ? $record->tipo_contrato : null,
                    'edad' => $edad,
                    'fecha_nacimiento' => $record ? $record->fecha_nacimiento : null,
                    'pagaduria' => $pagaduria,
                    'cargo' => $cargo,
                    'situacion_laboral' => $situacionLaboral,
                    'cupo_libre' => $cupoLibreInversion,
                    'cupones' => $cupones,
                    'embargos' => $embargos,
                    'descuentos' => $descuentos,
                    'colpensiones' => $existsInColpensiones,
                    'fiducidiaria' => $existsInFiducidiaria,
                ]);
            }
        }

        return $results;
    } catch (\Exception $e) {
        throw $e;
    }
}




    public function getRecentConsultations()
    {
        Log::info('Inicio del proceso de getRecentConsultations');
        $recentConsultations = DemographicConsultLog::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        Log::info('Fin del proceso de getRecentConsultations', ['recentConsultations' => $recentConsultations]);
        return response()->json($recentConsultations);
    }

    public function show()
    {
        Log::info('Inicio del proceso de show');
        Log::info('Fin del proceso de show');
        return view('Demographic.DemographicData');
    }

   
    public function getDemograficoPorDoc($doc)
    {
        Log::info('Inicio del proceso de getDemograficoPorDoc', ['doc' => $doc]);

        try {
            $record = DatamesGen::where('doc', $doc)
                ->select('doc', 'nombre_usuario', 'cencosto as centro_costo', 'tipo_contrato', 'edad', 'fecha_nacimiento', 'created_at')
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$record) {
                Log::info('Documento no encontrado:', ['doc' => $doc]);
                return response()->json(['error' => 'Documento no encontrado'], 404);
            }

            Log::info('Fin del proceso de getDemograficoPorDoc', ['record' => $record]);
            return response()->json($record);
        } catch (\Exception $e) {
            Log::error('Error fetching demographic data: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json(['error' => 'Error fetching demographic data'], 500);
        }
    }


    public function getCouponsPerDoc(Request $request)
    {
        try {
            $doc = $request->doc;
            $month = str_pad($request->month, 2, '0', STR_PAD_LEFT);
            $year = $request->year;

            $startDate = Carbon::createFromFormat('Y-m', $year . '-' . $month)->startOfMonth()->toDateString();
            $endDate = Carbon::createFromFormat('Y-m', $year . '-' . $month)->endOfMonth()->toDateString();

            $coupons = CouponsGen::where('doc', $doc)
                ->whereBetween('inicioperiodo', [$startDate, $endDate])
                ->orWhereBetween('finperiodo', [$startDate, $endDate])
                ->orderBy('pagaduria', 'asc')
                ->get()
                ->groupBy('pagaduria');

            Log::info("Consulta ejecutada para obtener cupones del documento {$doc}, mes {$month} y año {$year}: ", ['coupons' => $coupons]);

            return response()->json($coupons, 200);
        } catch (\Exception $e) {
            Log::error("Error al obtener cupones para el documento {$doc}: " . $e->getMessage());
            return response()->json(['error' => 'Error al obtener cupones'], 500);
        }
    }

    public function getEmbargosPerDoc(Request $request)
    {
        try {
            $doc = $request->doc;
            $month = str_pad($request->month, 2, '0', STR_PAD_LEFT);
            $year = $request->year;

            $startDate = Carbon::createFromFormat('Y-m', $year . '-' . $month)->startOfMonth()->toDateString();
            $endDate = Carbon::createFromFormat('Y-m', $year . '-' . $month)->endOfMonth()->toDateString();

            $embargos = EmbargosGen::where('doc', $doc)
                ->whereBetween('fembini', [$startDate, $endDate])
                ->orWhereBetween('fembfin', [$startDate, $endDate])
                ->orderBy('pagaduria', 'asc')
                ->get()
                ->groupBy('pagaduria');

            Log::info("Consulta ejecutada para obtener embargos del documento {$doc}, mes {$month} y año {$year}: ", ['embargos' => $embargos]);

            return response()->json($embargos, 200);
        } catch (\Exception $e) {
            Log::error("Error al obtener embargos para el documento {$doc}: " . $e->getMessage());
            return response()->json(['error' => 'Error al obtener embargos'], 500);
        }
    }

    public function getDescuentosPerDoc(Request $request)
    {
        try {
            $doc = $request->doc;
            $month = str_pad($request->month, 2, '0', STR_PAD_LEFT);
            $year = $request->year;

            $startDate = Carbon::createFromFormat('Y-m', $year . '-' . $month)->startOfMonth()->toDateString();
            $endDate = Carbon::createFromFormat('Y-m', $year . '-' . $month)->endOfMonth()->toDateString();

            $descuentos = DescuentosGen::where('doc', $doc)
                ->whereBetween('fecdata', [$startDate, $endDate])
                ->orderBy('pagaduria', 'asc')
                ->get()
                ->groupBy('pagaduria');

            Log::info("Consulta ejecutada para obtener descuentos del documento {$doc}, mes {$month} y año {$year}: ", ['descuentos' => $descuentos]);

            return response()->json($descuentos, 200);
        } catch (\Exception $e) {
            Log::error("Error al obtener descuentos para el documento {$doc}: " . $e->getMessage());
            return response()->json(['error' => 'Error al obtener descuentos'], 500);
        }
    }

    //antiguo modulo deografico

    public function fetchPaginatedResultsDemografico(Request $request)
    {
        Log::info('Inicio del proceso de fetchPaginatedResults');

        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 30);

        $cedulas = $request->session()->get('cedulas', []);
        $offset = ($page - 1) * $perPage;
        $cedulasChunk = array_slice($cedulas, $offset, $perPage);

        if (empty($cedulasChunk)) {
            Log::info('No se encontraron cédulas para esta página');
            return response()->json(['data' => [], 'total' => count($cedulas)], 200);
        }

        $results = $this->processCedulasDemografico($cedulasChunk);

        Log::info('Fin del proceso de fetchPaginatedResults');
        return response()->json([
            'data' => $results,
            'total' => count($cedulas),
            'page' => $page,
            'perPage' => $perPage
        ]);
    }

    private function processCedulasDemografico($cedulas)
{
    $formats = ['Y-m-d', 'd/m/Y', 'm-d-Y'];
    Log::info('Inicio del proceso de processCedulas', ['cedulas' => $cedulas]);
    
    // Asegurar que todas las cédulas sean tratadas como enteros
    $cedulas = array_map('intval', $cedulas);

    $latestRecords = DatamesGen::whereIn('doc', $cedulas)
        ->select('doc', 'nombre_usuario', 'cel', 'telefono', 'correo_electronico', 'ciudad', 'direccion_residencial', 'cencosto as centro_costo', 'tipo_contrato', 'edad', 'fecha_nacimiento', 'created_at')
        ->orderBy('created_at', 'desc')
        ->get()
        ->unique('doc')
        ->keyBy('doc');
    
    $couponsgenRecords = CouponsGen::whereIn('doc', $cedulas)
        ->select('doc', 'centro_costo', 'id')
        ->orderBy('id', 'desc')
        ->get()
        ->keyBy('doc');
    
    $results = collect();

    foreach ($cedulas as $cedula) {
        if (is_string($cedula) || is_int($cedula)) {
            if ($record = $latestRecords->get($cedula)) {
                $cellphones = [];
                $landlines = [];

                if ($record->telefono) {
                    $phones = explode(',', $record->telefono);
                    foreach ($phones as $phone) {
                        $phone = preg_replace('/\.\d+$/', '', trim($phone));
                        if (strlen($phone) === 10) {
                            $cellphones[] = $phone;
                        } else {
                            $landlines[] = $phone;
                        }
                    }
                }

                if ($record->cel) {
                    $phones = explode(',', $record->cel);
                    foreach ($phones as $phone) {
                        $phone = preg_replace('/\.\d+$/', '', trim($phone));
                        if (strlen($phone) === 10) {
                            $cellphones[] = $phone;
                        } else {
                            $landlines[] = $phone;
                        }
                    }
                }

                Log::info('Datos originales:', ['record' => $record->toArray()]);
                Log::info('Teléfonos transformados:', [
                    'cel' => $cellphones,
                    'tel' => $landlines
                ]);

                $centroCosto = $record->centro_costo;
                if ($coupon = $couponsgenRecords->get($cedula)) {
                    $centroCosto = $coupon->centro_costo;
                }

                $results->push([
                    'doc' => $record->doc,
                    'nombre_usuario' => $record->nombre_usuario,
                    'cel' => implode(', ', $cellphones),
                    'tel' => implode(', ', $landlines),
                    'correo_electronico' => $record->correo_electronico,
                    'ciudad' => $record->ciudad,
                    'direccion_residencial' => $record->direccion_residencial,
                    'centro_costo' => $centroCosto,
                    'tipo_contrato' => $record->tipo_contrato,
                    'edad' => $record->edad,
                    'fecha_nacimiento' => $record->fecha_nacimiento
                ]);
            } else {
                Log::info('Documento no encontrado:', ['cedula' => $cedula]);
            }
        } else {
            Log::warning('Cedula con tipo inválido', ['cedula' => $cedula, 'type' => gettype($cedula)]);
        }
    }

    Log::info('Fin del proceso de processCedulas', ['results' => $results]);

    return $results;
}

    



}