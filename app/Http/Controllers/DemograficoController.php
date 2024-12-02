<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DatamesGen;
use App\CouponsGen;
use App\EmbargosGen;
use App\DatamesFidu;
use App\DatamesFopep;
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
        $results = $this->processCedulas_vista($cedulasChunk, $mes, $año);

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

public function processCedulas($cedulas, $mes, $año)
{
    try {
        $startDate = Carbon::createFromFormat('Y-m', $año . '-' . $mes)->startOfMonth()->toDateString();
        $endDate = Carbon::createFromFormat('Y-m', $año . '-' . $mes)->endOfMonth()->toDateString();

        // Fetch all necessary data in batches before entering the loop

        // Get latest DatamesGen records for each cedula
        $latestRecords = DatamesGen::whereIn('doc', $cedulas)
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('doc') // Ensure one record per doc
            ->keyBy('doc');

        // Fetch Colpensiones and Fiducidiaria documents
        $colpensionesDocs = Colpensiones::whereIn('documento', $cedulas)->pluck('documento')->toArray();
        $fiducidiariaDocs = Fiducidiaria::whereIn('documento', $cedulas)->pluck('documento')->toArray();

        // Fetch CouponsGen records for all cedulas within the date range
        $couponsGenRecords = CouponsGen::whereIn('doc', $cedulas)
            ->whereBetween('inicioperiodo', [$startDate, $endDate])
            ->get()
            ->groupBy('doc'); // Group by doc for quick access

        // Fetch EmbargosGen records
        $embargosGenRecords = EmbargosGen::whereIn('doc', $cedulas)
            ->whereBetween('nomina', [$startDate, $endDate])
            ->select('doc', 'docdeman', 'entidaddeman', 'fembini', 'temb as valor', 'pagaduria')
            ->get()
            ->groupBy('doc');

        // Fetch DescuentosGen records
        $descuentosGenRecords = DescuentosGen::whereIn('doc', $cedulas)
            ->whereBetween('nomina', [$startDate, $endDate])
            ->get()
            ->groupBy('doc');

        // Fetch DatamesFopep and DatamesFidu records
        $datamesFopepRecords = DatamesFopep::whereIn('doc', $cedulas)
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('doc')
            ->keyBy('doc');

        $datamesFiduRecords = DatamesFidu::whereIn('doc', $cedulas)
            ->orderBy('created_at', 'desc')
            ->get()
            ->unique('doc')
            ->keyBy('doc');

        $results = collect();
        $salarioMinimo = 1300000;

        foreach ($cedulas as $cedula) {
            $cedulaStr = (string)$cedula;

            // Use pre-fetched data
            $existsInColpensiones = in_array($cedulaStr, $colpensionesDocs);
            $existsInFiducidiaria = in_array($cedulaStr, $fiducidiariaDocs);

            $record = $latestRecords->get($cedulaStr);
            $situacionLaboral = $record ? $record->situacion_laboral : 'No disponible';

            $couponsForCedula = $couponsGenRecords->get($cedulaStr, collect());

            // Get cargo from coupons
            $cargoRecord = $couponsForCedula->first();
            $cargo = $cargoRecord ? $cargoRecord->cargo : 'No disponible';

            Log::info("Procesando cédula: {$cedulaStr}", [
                'colpensiones' => $existsInColpensiones,
                'fiducidiaria' => $existsInFiducidiaria,
                'cargo' => $cargo,
                'situacion_laboral' => $situacionLaboral
            ]);

            // Get unique pagadurias from coupons
            $pagadurias = $couponsForCedula->pluck('pagaduria')->unique();

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
                    } else {
                        $edad = $record->edad;
                    }
                } catch (\Exception $e) {
                    Log::error("Error processing fecha_nacimiento for $cedulaStr: " . $e->getMessage());
                }
            }

            foreach ($pagadurias as $pagaduria) {
                Log::info("Procesando pagaduría: {$pagaduria} para cédula: {$cedulaStr}");

                // Get coupons for cedula and pagaduria
                $cupones = $couponsForCedula->where('pagaduria', $pagaduria);

                // Calculate totalWithoutHealthPension
                $couponsIngresos = $cupones->whereNotIn('code', ['APFPM', 'APEPEN', 'APESDN']);
                $totalWithoutHealthPension = $couponsIngresos->sum('vaplicado');

                // Get embargos and descuentos for cedula and pagaduria
                $embargosForCedula = $embargosGenRecords->get($cedulaStr, collect());
                $embargos = $embargosForCedula->where('pagaduria', $pagaduria);

                $descuentosForCedula = $descuentosGenRecords->get($cedulaStr, collect());
                $descuentos = $descuentosForCedula->where('pagaduria', $pagaduria)
                    ->filter(function ($descuento) {
                        return $descuento->mliquid != 'ALERTA';
                    });

                // Obtain valorIngreso based on pagaduria
                $valorIngreso = 0;
                if (in_array($pagaduria, ['FOPEP', 'FIDUPREVISORA'])) {
                    Log::info("Pagaduría es {$pagaduria}, obteniendo valorIngreso de DatamesFopep o DatamesFidu");

                    // Get valorIngreso from DatamesFopep or DatamesFidu
                    if ($pagaduria == 'FOPEP') {
                        $datames = $datamesFopepRecords->get($cedulaStr);
                    } elseif ($pagaduria == 'FIDUPREVISORA') {
                        $datames = $datamesFiduRecords->get($cedulaStr);
                    }
                    if ($datames && $datames->vpension) {
                        $valorIngresoStr = preg_replace('/[^0-9]/', '', $datames->vpension);
                        $valorIngreso = substr($valorIngresoStr, 0, -2); // Remove last two digits
                        $valorIngreso = floatval($valorIngreso);
                    } else {
                        Log::warning("No se encontró valorIngreso en Datames para {$cedulaStr}");
                    }
                } else {
                    Log::info("Pagaduría no es FOPEP ni FIDUPREVISORA, obteniendo valorIngreso de CouponsGen");

                    // Get valorIngreso from CouponsGen where code is 'INGCUP'
                    $ingresoRecord = $cupones->where('code', 'INGCUP')->first();
                    $valorIngreso = $ingresoRecord ? floatval($ingresoRecord->ingresos) : 0;
                }

                // Apply increases based on cargo using "like" comparison
                $increase = 0;
                $cargoLower = strtolower($cargo);

                if (strpos($cargoLower, 'rector') !== false) {
                    $increase = $valorIngreso * 0.3;
                    Log::info("Cargo contiene 'rector', aumento de 30% aplicado", ['increase' => $increase]);
                } elseif (strpos($cargoLower, 'coordinador') !== false) {
                    $increase = $valorIngreso * 0.2;
                    Log::info("Cargo contiene 'coordinador', aumento de 20% aplicado", ['increase' => $increase]);
                } elseif (strpos($cargoLower, 'director') !== false) {
                    $increase = $valorIngreso * 0.35;
                    Log::info("Cargo contiene 'director', aumento de 35% aplicado", ['increase' => $increase]);
                } else {
                    Log::info("Cargo no aplica para incrementos", ['cargo' => $cargo]);
                }
                $valorIngreso += $increase;

                Log::info("Valor ingreso después de incrementos para {$cedulaStr}: ", ['valorIngreso' => $valorIngreso]);

                // Calculate discount
                $descuento = 0.08;
                if (in_array($pagaduria, ['FOPEP', 'FIDUPREVISORA'])) {
                    if ($valorIngreso == $salarioMinimo) {
                        $descuento = 0.04;
                        Log::info("Valor ingreso igual al salario mínimo, descuento de 4% aplicado");
                    } elseif ($valorIngreso > $salarioMinimo && $valorIngreso < $salarioMinimo * 2) {
                        $descuento = 0.08;
                        Log::info("Valor ingreso entre 1 y 2 salarios mínimos, descuento de 8% aplicado");
                    } elseif ($valorIngreso >= $salarioMinimo * 2) {
                        $descuento = 0.12;
                        Log::info("Valor ingreso mayor o igual a 2 salarios mínimos, descuento de 12% aplicado");
                    }
                }
                Log::info("Descuento total aplicado para {$cedulaStr}: ", ['descuento' => $descuento]);

                $valorIngresoConDescuento = $valorIngreso - ($valorIngreso * $descuento);

                Log::info("Valor ingreso con descuento para {$cedulaStr}: ", ['valorIngresoConDescuento' => $valorIngresoConDescuento]);

                // Calculate compraCartera
                if ($valorIngresoConDescuento < $salarioMinimo * 2) {
                    $compraCartera = $valorIngresoConDescuento - $salarioMinimo - $totalWithoutHealthPension;
                    Log::info("Valor ingreso con descuento es menor a 2 salarios mínimos, cálculo de compraCartera ajustado", [
                        'compraCartera' => $compraCartera
                    ]);
                } else {
                    $compraCartera = ($valorIngresoConDescuento / 2) - $totalWithoutHealthPension;
                    Log::info("Valor ingreso con descuento es mayor o igual a 2 salarios mínimos, cálculo de compraCartera estándar", [
                        'compraCartera' => $compraCartera
                    ]);
                }

                $compraCartera = max($compraCartera, 0); // Ensure it's not negative

                // Calculate total egresos excluding certain codes
                $egresosExcluidos = ['APFPM', 'APEPEN', 'APESDN']; // Codes to exclude
                $egresos = $cupones->whereNotIn('code', $egresosExcluidos)
                    ->where(function ($coupon) {
                        return preg_match('/^\d+(\.\d+)?$/', $coupon->egresos);
                    })
                    ->sum(function ($coupon) {
                        return (float)$coupon->egresos;
                    });

                Log::info("Total de egresos (excluyendo pensión y salud) para {$cedulaStr}: ", ['egresos' => $egresos]);

                // Calculate libreInversion
                $libreInversion = $compraCartera - $egresos;

                Log::info("Cupo libre de inversión final para {$cedulaStr}: ", ['libreInversion' => $libreInversion]);

                // Push the result
                $results->push([
                    'doc' => $cedulaStr,
                    'nombre_usuario' => $record ? $record->nombre_usuario : null,
                    'tipo_contrato' => $record ? $record->tipo_contrato : null,
                    'edad' => $edad,
                    'fecha_nacimiento' => $record ? $record->fecha_nacimiento : null,
                    'pagaduria' => $pagaduria,
                    'cargo' => $cargo,
                    'situacion_laboral' => $situacionLaboral,
                    'compra_cartera' => $compraCartera,
                    'cupo_libre' => $libreInversion,
                    'cupones' => $cupones->values(), // Reset keys
                    'embargos' => $embargos->values(),
                    'descuentos' => $descuentos->values(),
                    'colpensiones' => $existsInColpensiones,
                    'fiducidiaria' => $existsInFiducidiaria,
                ]);
            }
        }

        return $results;
    } catch (\Exception $e) {
        Log::error("Error en processCedulas: " . $e->getMessage());
        throw $e;
    }
}


public function processCedulas_vista($cedulas, $mes, $año)
{
    try {
        Log::info("Inicio de processCedulas_vista", ['cedulas' => $cedulas]);

        $mes = (int)$mes;
        $año = (int)$año;

        $resultsData =   DB::connection('pgsql')
        ->table('fast_aggregate_data')
        ->whereIn('doc', $cedulas)
        ->whereRaw("extract(month from CAST(inicioperiodo AS date)) = ?", [$mes])
        ->whereRaw("extract(year from CAST(inicioperiodo AS date)) = ?", [$año])
        ->get();

        $colpensionesDocs = Colpensiones::on('pgsql')
            ->whereIn('documento', $cedulas)
            ->pluck('documento')
            ->toArray();

        $fiducidiariaDocs = Fiducidiaria::on('pgsql')
            ->whereIn('documento', $cedulas)
            ->pluck('documento')
            ->toArray();

        $fopepDocs = DatamesFopep::on('pgsql')
            ->whereIn('doc', $cedulas)
            ->pluck('doc')
            ->toArray();

        $results = collect();
        $salarioMinimo = 1300000;

        foreach ($cedulas as $cedula) {
            $cedulaStr = (string)$cedula;
            $dataForCedula = $resultsData->where('doc', $cedulaStr);

            if ($dataForCedula->isEmpty()) {
                continue;
            }

            foreach ($dataForCedula as $data) {
                $pagaduria = $data->pagaduria;
                $nombre_usuario = $data->nombre_usuario;
                $tipo_contrato = $data->tipo_contrato;
                $cargo = $data->cargos;
                $edad = $data->edad;

                // Convert fecha_nacimiento to a readable format
                $fecha_nacimiento = $data->fecha_nacimiento ?? null;
                if ($fecha_nacimiento) {
                    if (is_numeric($fecha_nacimiento)) {
                        $fecha_nacimiento = Carbon::createFromTimestamp($fecha_nacimiento)->format('Y-m-d');
                    } elseif (preg_match('/\d{2}\/\d{2}\/\d{4}/', $fecha_nacimiento)) {
                        $fecha_nacimiento = Carbon::createFromFormat('d/m/Y', $fecha_nacimiento)->format('Y-m-d');
                    } else {
                        $fecha_nacimiento = Carbon::parse($fecha_nacimiento)->format('Y-m-d');
                    }
                }

                $situacionLaboral=$data->situacion_laboral;
                Log::info("situacion alboral: ", ['situacion_laboral' => $situacionLaboral]);

                $total_egresos = $data->total_egresos;
                $ingresosExtras = $data->ingresos_ajustados;

                // Procesamiento de embargos
                $embargos = collect();
                preg_match_all('/(\d+): ([^,]+), ([\d\/-]+), ([\d,]+)/', $data->embargos_concatenados, $matches, PREG_SET_ORDER);
                foreach ($matches as $match) {
                    $embargoData = [
                        'docdeman' => trim($match[1]),
                        'entidaddeman' => trim($match[2]),
                        'fembini' => trim($match[3]),
                        'valor' => (float)str_replace(',', '', $match[4])
                    ];
                    $embargos->push($embargoData);
                }
                $embargos = $embargos->isEmpty() ? null : $embargos;

                // Detalle de cupones y descuentos
                $cupones = collect(explode(', ', $data->cupones_concatenados))
    ->map(function ($cupon) {
        $parts = explode(': ', $cupon);
        if (count($parts) === 2) {
            [$concept, $egresos] = $parts;
            $egresos = (float)str_replace(',', '', trim($egresos)); // Eliminar comas y convertir a float
            return ['concept' => trim($concept), 'egresos' => $egresos];
        }
        return null;
    })
    ->filter(function ($cupon) {
        return $cupon && $cupon['egresos'] > 0; // Filtra solo cupones con egresos > 0
    })
    ->values() // Re-indexa la colección para que sea secuencial
    ->toArray(); // Convierte a array para la compatibilidad con la vista

$cupones = empty($cupones) ? null : $cupones;

// Log para verificar cupones después del filtro de egresos > 0
Log::info("Cupones con egresos mayores a 0:", ['cupones' => $cupones]);



                $descuentos = collect(explode(', ', $data->descuentos_concatenados))
                    ->filter(function ($descuento) {
                        return !str_contains($descuento, 'ALERTA');
                    })
                    ->map(function ($descuento) {
                        $parts = explode(': ', $descuento);
                        if (count($parts) === 2) {
                            [$mliquid, $valor] = $parts;
                            return ['mliquid' => trim($mliquid), 'valor' => (float)trim($valor)];
                        }
                        return ['mliquid' => null, 'valor' => null];
                    })
                    ->filter(function ($descuento) {
                        return $descuento['valor'] > 0;
                    });
                $descuentos = $descuentos->isEmpty() ? null : $descuentos;

                // Cálculo de compraCartera y libreInversion
                $valorIngreso = $ingresosExtras;
                $descuento = 0.08;
                if (in_array($pagaduria, ['FOPEP', 'FIDUPREVISORA'])) {
                    if ($valorIngreso == $salarioMinimo) {
                        $descuento = 0.04;
                    } elseif ($valorIngreso > $salarioMinimo && $valorIngreso < $salarioMinimo * 2) {
                        $descuento = 0.08;
                    } elseif ($valorIngreso >= $salarioMinimo * 2) {
                        $descuento = 0.12;
                    }
                }
                if ($valorIngreso > 5200000) {
                    $descuento += 0.01;
                }

                $valorIngresoConDescuento = $valorIngreso - ($valorIngreso * $descuento);

                $compraCartera = ($valorIngresoConDescuento < $salarioMinimo * 2)
                    ? $valorIngresoConDescuento - $salarioMinimo 
                    : ($valorIngresoConDescuento / 2);

                $libreInversion = $compraCartera - $total_egresos;

                $existsInColpensiones = in_array($cedulaStr, $colpensionesDocs);
                $existsInFiducidiaria = in_array($cedulaStr, $fiducidiariaDocs);
                $existsInFopep = in_array($cedulaStr, $fopepDocs);

                $results->push([
                    'doc' => $cedulaStr,
                    'nombre_usuario' => $nombre_usuario,
                    'tipo_contrato' => $tipo_contrato,
                    'edad' => $edad,
                    'fecha_nacimiento' => $fecha_nacimiento,
                    'pagaduria' => $pagaduria,
                    'cargo' => $cargo,
                    'situacion_laboral' => $situacionLaboral,
                    'compra_cartera' => $compraCartera,
                    'cupo_libre' => $libreInversion,
                    'cupones' => $cupones,
                    'embargos' => $embargos,
                    'descuentos' => $descuentos,
                    'colpensiones' => $existsInColpensiones,
                    'fiducidiaria' => $existsInFiducidiaria,
                    'fopep' => $existsInFopep,

                ]);
            }
        }

        Log::info("Fin de processCedulas_vista");
        return $results;
    } catch (\Exception $e) {
        Log::error("Error en processCedulas_vista: " . $e->getMessage());
        throw $e;
    }
}





/**
 * Helper function to parse concatenated strings back into arrays.
 */
private function parseConcatenatedString($concatenatedString)
{
    if (empty($concatenatedString)) {
        return [];
    }

    $items = explode(', ', $concatenatedString);
    $result = [];

    foreach ($items as $item) {
        $result[] = $item;
    }

    return $result;
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


    /*public function calculoLibreInversionCompraCartera(Request $request){
        //Variable $minimo es temporal, pendiente saber de donde se puede obtener de forma dinamica
        $minimo = 1300000;
        $solidaridad = 0.01;
        $aportes = 250000;
        $renta = 250000;

        $cupones = CouponsGen::where('doc', $cedulaStr)->where('egresos', '>', 0)->get();
        $egresos = $cupones->sum('egresos');

        $sueldo = CouponsGen::where('doc', $request->cedula)->where('code', 'SUEBA')->first();
        $compraCartera = null;
        $cupoLibreInversion = null;
        
        if ($sueldo < $minimo*2) {
            $compraCartera = $sueldo - $minimo;
        }else if($sueldo > $minimo*2){
            $$compraCartera = $sueldo / 2;
        }else if($sueldo > 5200000){
            $sueldo = $sueldo - $solidaridad;
            $compraCartera = $sueldo/2;
        }else if($sueldo > 6200000){
            $sueldo = $sueldo - $solidaridad - $aportes;
            $compraCartera = $sueldo / 2;
        }

        $cupoLibreInversion = ($compraCartera - $cupoLibreInversion);

        $valorIngreso = 0;
        $increase = 0;
        if($request->pagaduriaType === 'FOPEP' ){
            $valorIngreso = (int)$request->datamesFopep['vpension'];
        }else if($request->pagaduriaType === 'FIDUPREVISORA' ){
            $valorIngreso = (int)$request->datamesFidu['vpension'];
        }else {
            $valorIngreso = 0;
            foreach ($request->couponsPerPeriod['items'] as $item) {
                if ($item['code'] === 'INGCUP') {
                    $valorIngreso = isset($item['ingresos']) ? $item['ingresos'] : 0;
                    break; 
                }
            }
        }

        if ($sueldo <= $minimo) {
            $valorIngreso = $valorIngreso -0.04;
        }else if($sueldo > $minimo && $sueldo < 3900000){
            $valorIngreso = $valorIngreso -0.10;
        }else if($sueldo > 3900000){
            $valorIngreso = $valorIngreso -0.12;
        }

        array_filter($request->cargo, function($cargo) {
            if(stripos($cargo, 'coordinador')){ 
                $increase = $valorIngreso * 0.2;
                $valorIngreso = float($valorIngreso) + float($increase);
            }else if( stripos($cargo, 'Rector') ){
                $increase = $valorIngreso * 0.3;
                $valorIngreso = float($valorIngreso) + float($increase);
            }else if( stripos($cargo, 'Director') ){
                $increase = $valorIngreso * 0.35;
                $valorIngreso = float($valorIngreso) + float($increase);
            }
        });


    }*/
    

    //PARA VISADO


  

    public function calcularCupoPorCedula($cedula, $mes, $año)
    {
        Log::info("Inicio del cálculo para una sola cédula", ['cedula' => $cedula, 'mes' => $mes, 'año' => $año]);
    
        try {
            $mes = (int)$mes;
            $año = (int)$año;
    
            // Obtener datos relevantes para la cédula
            $resultData = DB::connection('pgsql')
                ->table('fast_aggregate_data')
                ->where('doc', $cedula)
                ->whereRaw("extract(month from CAST(inicioperiodo AS date)) = ?", [$mes])
                ->whereRaw("extract(year from CAST(inicioperiodo AS date)) = ?", [$año])
                ->first();
    
            if (!$resultData) {
                Log::info("No se encontró información para la cédula", ['cedula' => $cedula]);
                return null;
            }
    
            // Verificar presencia en Colpensiones, Fiducidiaria y Fopep
            $existsInColpensiones = Colpensiones::on('pgsql')->where('documento', $cedula)->exists();
            $existsInFiducidiaria = Fiducidiaria::on('pgsql')->where('documento', $cedula)->exists();
            $existsInFopep = DatamesFopep::on('pgsql')->where('doc', $cedula)->exists();
    
            // Formatear fecha de nacimiento
            $fechaNacimiento = $resultData->fecha_nacimiento ?? null;
            if ($fechaNacimiento) {
                if (is_numeric($fechaNacimiento)) {
                    $fechaNacimiento = Carbon::createFromTimestamp($fechaNacimiento)->format('Y-m-d');
                } elseif (preg_match('/\d{2}\/\d{2}\/\d{4}/', $fechaNacimiento)) {
                    $fechaNacimiento = Carbon::createFromFormat('d/m/Y', $fechaNacimiento)->format('Y-m-d');
                } else {
                    $fechaNacimiento = Carbon::parse($fechaNacimiento)->format('Y-m-d');
                }
            }
    
            // Procesar embargos
            $embargos = collect();
            if ($resultData->embargos_concatenados) {
                preg_match_all('/(\d+): ([^,]+), ([\d\/-]+), ([\d,]+)/', $resultData->embargos_concatenados, $matches, PREG_SET_ORDER);
                foreach ($matches as $match) {
                    $embargos->push([
                        'docdeman' => trim($match[1]),
                        'entidaddeman' => trim($match[2]),
                        'fembini' => trim($match[3]),
                        'valor' => (float)str_replace(',', '', $match[4]),
                    ]);
                }
            }
    
            // Procesar cupones
            $cupones = collect(explode(', ', $resultData->cupones_concatenados))
                ->map(function ($cupon) {
                    $parts = explode(': ', $cupon);
                    if (count($parts) === 2) {
                        [$concept, $egresos] = $parts;
                        return ['concept' => trim($concept), 'egresos' => (float)str_replace(',', '', $egresos)];
                    }
                    return null;
                })
                ->filter(function ($cupon) {
                    return $cupon && $cupon['egresos'] > 0;
                })
                                ->values()
                ->toArray();
    
            // Procesar descuentos
            $descuentos = collect(explode(', ', $resultData->descuentos_concatenados))
                ->filter(fn($descuento) => !str_contains($descuento, 'ALERTA'))
                ->map(function ($descuento) {
                    $parts = explode(': ', $descuento);
                    if (count($parts) === 2) {
                        [$mliquid, $valor] = $parts;
                        return ['mliquid' => trim($mliquid), 'valor' => (float)$valor];
                    }
                    return null;
                })
                ->filter(fn($descuento) => $descuento && $descuento['valor'] > 0);
    
            // Cálculos financieros
            $salarioMinimo = 1300000;
            $valorIngreso = $resultData->ingresos_ajustados;
            $totalEgresos = $resultData->total_egresos;
            $descuento = 0.08;
    
            if (in_array($resultData->pagaduria, ['FOPEP', 'FIDUPREVISORA'])) {
                if ($valorIngreso == $salarioMinimo) {
                    $descuento = 0.04;
                } elseif ($valorIngreso > $salarioMinimo && $valorIngreso < $salarioMinimo * 2) {
                    $descuento = 0.08;
                } elseif ($valorIngreso >= $salarioMinimo * 2) {
                    $descuento = 0.12;
                }
            }
            if ($valorIngreso > 5200000) {
                $descuento += 0.01;
            }
    
            $valorIngresoConDescuento = $valorIngreso - ($valorIngreso * $descuento);
    
            $compraCartera = ($valorIngresoConDescuento < $salarioMinimo * 2)
                ? $valorIngresoConDescuento - $salarioMinimo
                : ($valorIngresoConDescuento / 2);
    
            $libreInversion = $compraCartera - $totalEgresos;
    
            // Resultado final
            $resultado = [
                'doc' => $cedula,
                'nombre_usuario' => $resultData->nombre_usuario,
                'tipo_contrato' => $resultData->tipo_contrato,
                'edad' => $resultData->edad,
                'fecha_nacimiento' => $fechaNacimiento,
                'pagaduria' => $resultData->pagaduria,
                'cargo' => $resultData->cargos,
                'situacion_laboral' => $resultData->situacion_laboral,
                'compra_cartera' => $compraCartera,
                'cupo_libre' => $libreInversion,
                'cupones' => $cupones,
                'embargos' => $embargos->values()->toArray(),
                'descuentos' => $descuentos->values()->toArray(),
                'colpensiones' => $existsInColpensiones,
                'fiducidiaria' => $existsInFiducidiaria,
                'fopep' => $existsInFopep,
            ];
    
            Log::info("Resultado del cálculo para cédula", ['resultado' => $resultado]);
    
            return $resultado;
        } catch (\Exception $e) {
            Log::error("Error en calcularCupoPorCedula: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return null;
        }
    }
    




}