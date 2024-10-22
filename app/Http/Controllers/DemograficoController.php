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
            return response()->json(['message' => 'Cédulas cargadas correctamente, por favor proceda a consultar los resultados paginados.']);
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
    Log::info('Inicio del proceso de processCedulas', ['cedulas' => $cedulas, 'mes' => $mes, 'año' => $año]);

    try {
        $startDate = Carbon::createFromFormat('Y-m', $año . '-' . $mes)->startOfMonth()->toDateString();
        $endDate = Carbon::createFromFormat('Y-m', $año . '-' . $mes)->endOfMonth()->toDateString();

        $cedulasList = implode(", ", array_map(function($cedula) {
            return "'" . addslashes((string)$cedula) . "'";
        }, $cedulas));

        $sql = "SELECT DISTINCT ON (doc) *
                FROM datamesgen
                WHERE doc IN ($cedulasList)
                ORDER BY doc, created_at DESC;";

        $latestRecords = collect(DB::connection('pgsql')->select($sql))->keyBy('doc');

        // Consulta de cupones
        $coupons = CouponsGen::whereIn('doc', $cedulas)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('inicioperiodo', [$startDate, $endDate])
                      ->orWhereBetween('finperiodo', [$startDate, $endDate]);
            })
            ->get()
            ->groupBy('doc');

        // Consulta de embargos (usando 'nomina' para filtrar por fecha)
        $embargos = EmbargosGen::whereIn('doc', $cedulas)
            ->whereBetween('nomina', [$startDate, $endDate])
            ->get()
            ->groupBy('doc');

        // Consulta de descuentos (usando 'nomina' para filtrar por fecha)
        $descuentos = DescuentosGen::whereIn('doc', $cedulas)
            ->whereBetween('nomina', [$startDate, $endDate])
            ->get()
            ->groupBy('doc');

        $results = collect();
        $salarioMinimo = 1300000; // Ajusta este valor según corresponda

        foreach ($cedulas as $cedula) {
            $cedulaStr = (string)$cedula;

            // Verificar si la cédula existe en Colpensiones
            $existsInColpensiones = Colpensiones::where('documento', $cedulaStr)->exists();

            // Verificar si la cédula existe en Fiducidiaria
            $existsInFiducidiaria = Fiducidiaria::where('documento', $cedulaStr)->exists();

            if ($record = $latestRecords->get($cedulaStr)) {
                // Obtener el último ingreso para el documento
                $ingresoRecord = CouponsGen::where('doc', $cedulaStr)
                    ->where('concept', 'IgresosCupon')
                    ->orderBy('inicioperiodo', 'desc')
                    ->first();

                $ingreso = $ingresoRecord ? $ingresoRecord->ingresos : 0;

                // Sumar los egresos de los cupones para el documento
                $cuponesDoc = $coupons->get($cedulaStr, collect());
                $sumEgresosCupones = $cuponesDoc->sum('egresos');

                // Calcular el cupo libre de inversión
                if ($ingreso > 2 * $salarioMinimo) {
                    $cupoLibreInversion = ($ingreso / 2) - $sumEgresosCupones;
                } else {
                    $cupoLibreInversion = (($ingreso - $salarioMinimo) / 2) - $sumEgresosCupones;
                }

                // Asegurarse de que el cupo libre no sea negativo
                $cupoLibreInversion = max(0, $cupoLibreInversion);

                $results->push([
                    'doc' => $record->doc,
                    'nombre_usuario' => $record->nombre_usuario,
                    'tipo_contrato' => $record->tipo_contrato,
                    'edad' => $record->edad,
                    'fecha_nacimiento' => $record->fecha_nacimiento,
                    'cupo_libre' => $cupoLibreInversion,
                    'cupones' => $cuponesDoc->values(),
                    'embargos' => $embargos->get($cedulaStr, collect())->values(),
                    'descuentos' => $descuentos->get($cedulaStr, collect())->values(),
                    'colpensiones' => $existsInColpensiones,
                    'fiducidiaria' => $existsInFiducidiaria,
                ]);
            } else {
                Log::info('Documento no encontrado en DatamesGen', ['cedula' => $cedulaStr]);
                // Si deseas agregar una entrada vacía para las cédulas no encontradas, puedes hacerlo aquí
                $results->push([
                    'doc' => $cedulaStr,
                    'nombre_usuario' => null,
                    'tipo_contrato' => null,
                    'edad' => null,
                    'fecha_nacimiento' => null,
                    'cupo_libre' => null,
                    'cupones' => collect(),
                    'embargos' => collect(),
                    'descuentos' => collect(),
                    'colpensiones' => $existsInColpensiones,
                    'fiducidiaria' => $existsInFiducidiaria,
                ]);
            }
        }
        Log::info('Resultados completos de processCedulas', ['results' => $results]);
        return $results;
    } catch (\Exception $e) {
        Log::error('Error en processCedulas: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
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

}