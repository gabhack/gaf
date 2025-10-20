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
use App\PendingDemographicUpload;
use App\DemographicConsultLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;           
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;

class DemograficoController extends Controller
{


    public function upload(Request $request)
    {
        Log::info('[upload] Iniciando método upload...');
        try {
            // Limpiar caché anterior del usuario
            $cacheKey = 'cedulas_data_' . Auth::id();
            Cache::forget($cacheKey);
            Log::info('[upload] Caché anterior limpiado para el usuario: ' . Auth::id());

            $file = $request->file('file');
            if (!$file || !$file->isValid()) {
                Log::warning('[upload] Archivo no válido o no encontrado.');
                return response()->json(['error' => 'Archivo inválido'], 400);
            }

            $path = $file->getRealPath();
            Log::info('[upload] Path del archivo: '.$path);

            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($path);
            Log::info('[upload] Spreadsheet cargado correctamente.');

            $worksheet = $spreadsheet->getActiveSheet();
            $highestColumn = $worksheet->getHighestColumn();
            $headerRange = 'A1:' . $highestColumn . '1';
            $header = $worksheet->rangeToArray($headerRange)[0];
            Log::info('[upload] Header extraído: ', $header);

            // Mapear nombres de columnas a índices (case-insensitive)
            $headerMap = [];
            foreach ($header as $index => $columnName) {
                $headerMap[strtolower(trim($columnName))] = $index;
            }

            // Validar que exista la columna cedulas
            if (!isset($headerMap['cedulas'])) {
                Log::warning('[upload] No se encontró la columna "cedulas" en el archivo Excel.');
                return response()->json(['error' => 'No se encontró la columna "cedulas"'], 400);
            }

            // Definir las columnas adicionales que queremos extraer
            $additionalColumns = [
                'operación',
                'valor desembolso',
                'saldo capital original',
                'intereses corrientes',
                'intereses de mora',
                'seguros',
                'otros conceptos',
                'tasa pactada',
                'respetar tasa pactada',
                'plazo pactado',
                'cuota pactada',
                'respetar cuota pactada'
            ];

            $dataRows = [];
            foreach ($worksheet->getRowIterator(2) as $row) {
                $rowIndex = $row->getRowIndex();
                $cedula = trim($worksheet->getCellByColumnAndRow($headerMap['cedulas'] + 1, $rowIndex)->getValue());

                if (empty($cedula)) {
                    continue;
                }

                $rowData = ['cedula' => $cedula];

                // Extraer campos adicionales si existen
                foreach ($additionalColumns as $columnName) {
                    $columnKey = strtolower($columnName);
                    if (isset($headerMap[$columnKey])) {
                        $value = $worksheet->getCellByColumnAndRow($headerMap[$columnKey] + 1, $rowIndex)->getValue();
                        $rowData[$columnKey] = $value;
                    }
                }

                $dataRows[] = $rowData;
            }

            Log::info('[upload] Total de filas extraídas: '.count($dataRows));

            Cache::put($cacheKey, $dataRows, 3600);
            Log::info('[upload] Datos almacenados en caché con key: '.$cacheKey);

            return response()->json(['uploaded' => true], 200);
        } catch (\Exception $e) {
            Log::error('[upload] Error al procesar el archivo: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Error al procesar el archivo'], 500);
        }
    }
    
    public function fetchPaginatedResultsDemografico(Request $request)
    {
        Log::info('[fetchPaginatedResultsDemografico] Iniciando método...');
        try {
            $page = (int) $request->query('page', 1);
            $perPage = (int) $request->query('perPage', 30);
            $mes = $request->query('mes');
            $año = $request->query('año');

            Log::info("[fetchPaginatedResultsDemografico] Parámetros recibidos - Page: {$page}, PerPage: {$perPage}, Mes: {$mes}, Año: {$año}");

            $cacheKey = 'cedulas_data_' . Auth::id();
            $cedulasData = Cache::get($cacheKey, []);
            $total = count($cedulasData);

            Log::info("[fetchPaginatedResultsDemografico] Total cédulas en caché: {$total}");

            if ($total < 1) {
                Log::info('[fetchPaginatedResultsDemografico] No hay cédulas en caché.');
                return response()->json([
                    'data' => [],
                    'total' => 0,
                    'page' => $page,
                    'perPage' => $perPage,
                    'hasMore' => false
                ]);
            }

            $offset = ($page - 1) * $perPage;
            if ($offset >= $total) {
                Log::info("[fetchPaginatedResultsDemografico] Offset {$offset} supera el total ({$total}).");
                return response()->json([
                    'data' => [],
                    'total' => $total,
                    'page' => $page,
                    'perPage' => $perPage,
                    'hasMore' => false
                ]);
            }

            $cedulasDataChunk = array_slice($cedulasData, $offset, $perPage);
            Log::info('[fetchPaginatedResultsDemografico] Cédulas chunk size: '.count($cedulasDataChunk));

            // Extraer solo las cédulas para la consulta
            $cedulas = array_column($cedulasDataChunk, 'cedula');

            // Usar processCedulas_vista que consulta fast_aggregate_data con mes y año
            $results = $this->processCedulas_vista($cedulas, $mes, $año);

            // Merge con los datos adicionales del Excel
            foreach ($results as &$result) {
                foreach ($cedulasDataChunk as $excelRow) {
                    if ($excelRow['cedula'] == $result['doc']) {
                        // Agregar campos adicionales del Excel
                        $result['operacion'] = $excelRow['operación'] ?? null;
                        $result['valor_desembolso'] = $excelRow['valor desembolso'] ?? null;
                        $result['saldo_capital_original'] = $excelRow['saldo capital original'] ?? null;
                        $result['intereses_corrientes'] = $excelRow['intereses corrientes'] ?? null;
                        $result['intereses_de_mora'] = $excelRow['intereses de mora'] ?? null;
                        $result['seguros'] = $excelRow['seguros'] ?? null;
                        $result['otros_conceptos'] = $excelRow['otros conceptos'] ?? null;
                        $result['tasa_pactada'] = $excelRow['tasa pactada'] ?? null;
                        $result['respetar_tasa_pactada'] = $excelRow['respetar tasa pactada'] ?? null;
                        $result['plazo_pactado'] = $excelRow['plazo pactado'] ?? null;
                        $result['cuota_pactada'] = $excelRow['cuota pactada'] ?? null;
                        $result['respetar_cuota_pactada'] = $excelRow['respetar cuota pactada'] ?? null;
                        break;
                    }
                }
            }

            $hasMore = ($offset + $perPage) < $total;
            Log::info("[fetchPaginatedResultsDemografico] hasMore: ".($hasMore ? 'true' : 'false'));

            return response()->json([
                'data' => $results,
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'hasMore' => $hasMore
            ]);
        } catch (\Exception $e) {
            Log::error('[fetchPaginatedResultsDemografico] Error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Error en la obtención de resultados paginados'], 500);
        }
    }
    
    private function processCedulasDemografico($cedulas)
    {
        Log::info('[processCedulasDemografico] Iniciando método. Cantidad de cédulas a procesar: '.count($cedulas));
        try {
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
    
            Log::info('[processCedulasDemografico] DatamesGen records recuperados: '.count($latestRecords).', CouponsGen: '.count($couponsgenRecords));
    
            $results = collect();
            foreach ($cedulas as $cedula) {
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
                    Log::info("[processCedulasDemografico] No se encontró registro en DatamesGen para cédula: {$cedula}");
                }
            }
    
            Log::info('[processCedulasDemografico] Registros finales a retornar: '.count($results));
            return $results;
        } catch (\Exception $e) {
            Log::error('[processCedulasDemografico] Error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
    

public function fetchPaginatedResults(Request $request)
{
    Log::info('Inicio del proceso de fetchPaginatedResults');

    try {
        $page = (int) $request->query('page', 1);
        $perPage = (int) $request->query('perPage', 10000); // Ajusta según necesidad
        
        // Obtener las cédulas desde caché
        $cacheKey = 'cedulas_' . Auth::id();
        $cedulas = Cache::get($cacheKey, []);

        if (empty($cedulas)) {
            Log::info('No hay cédulas en caché');
            return response()->json([
                'data' => [],
                'total' => 0,
                'page' => $page,
                'perPage' => $perPage,
                'hasMore' => false
            ]);
        }

        // Obtener la cantidad total
        $total = count($cedulas);
        $offset = ($page - 1) * $perPage;

        // Validar si el offset es mayor al total
        if ($offset >= $total) {
            Log::info("Offset {$offset} mayor que total de registros {$total}");
            return response()->json([
                'data' => [],
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'hasMore' => false
            ]);
        }

        // Extraer el subconjunto de cédulas
        $cedulasChunk = array_slice($cedulas, $offset, $perPage);

        if (empty($cedulasChunk)) {
            Log::info('No se encontraron cédulas en la página solicitada');
            return response()->json([
                'data' => [],
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'hasMore' => false
            ]);
        }

        // Procesar las cédulas
        $results = $this->processCedulas_vista($cedulasChunk, $request->query('mes'), $request->query('año'));

        Log::info('Fin del proceso de fetchPaginatedResults');
        return response()->json([
            'data' => $results,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'hasMore' => ($offset + $perPage) < $total // Determinar si hay más registros
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
        $salarioMinimo = 1423000;

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
        Log::info("Inicio de processCedulas_vista", [
            'cedulas' => $cedulas,
            'mes' => $mes,
            'año' => $año
        ]);

        $mes = (int)$mes;
        $año = (int)$año;

        Log::info("Obteniendo registros de fast_aggregate_data con mes/año indicados");
        $resultsData = DB::connection('pgsql')
            ->table('fast_aggregate_data')
            ->whereIn('doc', $cedulas)
            ->whereRaw("extract(month from CAST(inicioperiodo AS date)) = ?", [$mes])
            ->whereRaw("extract(year from CAST(inicioperiodo AS date)) = ?", [$año])
            ->get();

        Log::info("Cargando registros de Colpensiones, Fiducidiaria, Fopep");
        $colpensionesAll = Colpensiones::on('pgsql')
            ->whereIn('documento', $cedulas)
            ->get()
            ->keyBy('documento'); 
        $fiducidiariaAll = Fiducidiaria::on('pgsql')
            ->whereIn('documento', $cedulas)
            ->get()
            ->keyBy('documento'); 
        $fopepAll = DatamesFopep::on('pgsql')
            ->whereIn('doc', $cedulas)
            ->get()
            ->keyBy('doc');      

        // Colecciones de solo los docs para banderas en el resultado
        $colpensionesDocs = $colpensionesAll->keys()->all();
        $fiducidiariaDocs = $fiducidiariaAll->keys()->all();
        $fopepDocs        = $fopepAll->keys()->all();

        $results = collect();
        $salarioMinimo = 1423500;

        Log::info("Comenzando procesamiento de las cédulas...");

        foreach ($cedulas as $cedula) {
            $cedulaStr = (string)$cedula;
            Log::info("Procesando cédula: {$cedulaStr}");

            // ¿Está la cédula en Colpensiones, Fiducidiaria o Fopep?
            $existsInColpensiones = in_array($cedulaStr, $colpensionesDocs);
            $existsInFiducidiaria = in_array($cedulaStr, $fiducidiariaDocs);
            $existsInFopep        = in_array($cedulaStr, $fopepDocs);

            Log::info("Estado en fuentes externas (Colpensiones, Fiducidiaria, Fopep)", [
                'colpensiones' => $existsInColpensiones,
                'fiducidiaria' => $existsInFiducidiaria,
                'fopep' => $existsInFopep
            ]);

            // Traemos todos los registros en fast_aggregate_data que coincidan
            $dataForCedula = $resultsData->where('doc', $cedulaStr);

            // Construir el nombre desde Colpensiones / Fidu / Fopep, si existe
            $nombrePensionado = null;
            if ($existsInColpensiones) {
                $c = $colpensionesAll->get($cedulaStr);
                $nombrePensionado = trim(
                    ($c->primer_nombre ?? '') . ' ' .
                    ($c->segundo_nombre ?? '') . ' ' .
                    ($c->primer_apellido ?? '') . ' ' .
                    ($c->segundo_apellido ?? '')
                );
            } elseif ($existsInFiducidiaria) {
                $f = $fiducidiariaAll->get($cedulaStr);
                $nombrePensionado = trim(
                    ($f->NOMBRES ?? '') . ' ' .
                    ($f->APELLIDOS ?? '')
                );
            } elseif ($existsInFopep) {
                $p = $fopepAll->get($cedulaStr);
                $nombrePensionado = trim($p->nomp ?? '');
            }

            // Si NO hay información en fast_aggregate_data
            if ($dataForCedula->isEmpty()) {
                Log::info("No se encontró información en fast_aggregate_data para cédula: {$cedulaStr}");
                // pero sí está en Colpensiones / Fiducidiaria / Fopep
                if ($existsInColpensiones || $existsInFiducidiaria || $existsInFopep) {
                    Log::info("Cédula existe en (Colpensiones o Fiduciaria o Fopep). Se devolverá info mínima.");
                    $results->push([
                        'doc'               => $cedulaStr,
                        'nombre_usuario'    => $nombrePensionado ?: null,
                        'tipo_contrato'     => null,
                        'edad'              => null,
                        'fecha_nacimiento'  => null,
                        'pagaduria'         => null,
                        'cargo'             => null,
                        'situacion_laboral' => null,
                        'compra_cartera'    => 0,
                        'cupo_libre'        => 0,
                        'cupones'           => null,
                        'embargos'          => null,
                        'descuentos'        => null,
                        'colpensiones'      => $existsInColpensiones,
                        'fiducidiaria'      => $existsInFiducidiaria,
                        'fopep'             => $existsInFopep,
                    ]);
                }
                continue;
            }

            // Si SÍ hay data en fast_aggregate_data
            foreach ($dataForCedula as $data) {
                Log::info("Registro fast_aggregate_data encontrado", ['data' => $data]);

                $pagaduria        = $data->pagaduria;
                $nombre_usuario   = $data->nombre_usuario;
                $tipo_contrato    = $data->tipo_contrato;
                $cargo            = $data->cargos;
                $edad             = $data->edad;
                $situacionLaboral = $data->situacion_laboral;

                // Si fast_aggregate_data no trae nombre, pero sí lo tenemos en Colpensiones/Fidu/Fopep
                if (empty($nombre_usuario) && $nombrePensionado) {
                    Log::info("No había nombre en fast_aggregate_data; usando el de la fuente pensional", [
                        'nombre_pensionado' => $nombrePensionado
                    ]);
                    $nombre_usuario = $nombrePensionado;
                }

                // Convertir fecha_nacimiento a formato legible
                $fecha_nacimiento = $data->fecha_nacimiento ?? null;
                if ($fecha_nacimiento) {
                    Log::info("Procesando fecha_nacimiento: {$fecha_nacimiento}");
                    try {
                        if (is_numeric($fecha_nacimiento)) {
                            // A veces vienen timestamps
                            $fecha_nacimiento = Carbon::createFromTimestamp($fecha_nacimiento)->format('Y-m-d');
                        } elseif (preg_match('/\\d{2}\\/\\d{2}\\/\\d{4}/', $fecha_nacimiento)) {
                            $fecha_nacimiento = Carbon::createFromFormat('d/m/Y', $fecha_nacimiento)->format('Y-m-d');
                        } else {
                            // Manejo genérico
                            $fecha_nacimiento = Carbon::parse($fecha_nacimiento)->format('Y-m-d');
                        }
                    } catch (\Exception $e) {
                        Log::error("Error parseando fecha_nacimiento para {$cedulaStr}: ".$e->getMessage());
                        $fecha_nacimiento = null;
                    }
                }

                // Total de egresos
                $total_egresos = $data->total_egresos;
                Log::info("Total de egresos: {$total_egresos} para cédula: {$cedulaStr}");

                // Ingresos ajustados (ya incluye incrementos según cargo y desc. RTFSA)
                $valorIngreso = $data->ingresos_ajustados;
                Log::info("Ingresos ajustados iniciales: {$valorIngreso} para cédula: {$cedulaStr}");

                // Parse embargos
                $embargos = collect();
                if (!empty($data->embargos_concatenados)) {
                    Log::info("Procesando embargos_concatenados para cédula: {$cedulaStr}", [
                        'embargos_concatenados' => $data->embargos_concatenados
                    ]);
                    preg_match_all(
                        '/(\\d+): ([^,]+), ([\\d\\/\\-]+), ([\\d,]+)/',
                        $data->embargos_concatenados,
                        $matches,
                        PREG_SET_ORDER
                    );
                    foreach ($matches as $match) {
                        $embargoData = [
                            'docdeman'     => trim($match[1]),
                            'entidaddeman' => trim($match[2]),
                            'fembini'      => trim($match[3]),
                            'valor'        => (float)str_replace(',', '', $match[4]),
                        ];
                        $embargos->push($embargoData);
                    }
                }
                $embargos = $embargos->isEmpty() ? null : $embargos;

                // Parse cupones
                Log::info("Procesando cupones_concatenados para cédula: {$cedulaStr}");
                $cupones = collect(explode(', ', $data->cupones_concatenados))
                    ->map(function ($cupon) {
                        $parts = explode(': ', $cupon);
                        if (count($parts) === 2) {
                            [$concept, $egresos] = $parts;
                            $egresos = (float)str_replace(',', '', trim($egresos));
                            return [
                                'concept' => trim($concept),
                                'egresos' => $egresos
                            ];
                        }
                        return null;
                    })
                    ->filter(function ($cupon) {
                        return $cupon && $cupon['egresos'] > 0;
                    })
                    ->values()
                    ->toArray();

                // Parse descuentos
                Log::info("Procesando descuentos_concatenados para cédula: {$cedulaStr}");
                $descuentos = collect(explode(', ', $data->descuentos_concatenados))
                    ->filter(function ($descuento) {
                        // Excluir 'ALERTA'
                        return !str_contains($descuento, 'ALERTA');
                    })
                    ->map(function ($descuento) {
                        $parts = explode(': ', $descuento);
                        if (count($parts) === 2) {
                            [$mliquid, $valor] = $parts;
                            return [
                                'mliquid' => trim($mliquid),
                                'valor'   => (float)$valor
                            ];
                        }
                        return null;
                    })
                    ->filter(function ($item) {
                        // Eliminar nulos y valores <= 0
                        return $item && $item['valor'] > 0;
                    })
                    ->unique('mliquid')
                    ;
                $descuentos = $descuentos->isEmpty() ? null : $descuentos;

                // Aplicar descuento base según pagaduría y salario
                $descuento = 0.08;
                Log::info("Descuento inicial base para {$cedulaStr} = 8%");

                if (in_array($pagaduria, ['FOPEP','FIDUPREVISORA'])) {
                    Log::info("La pagaduría es FOPEP o FIDUPREVISORA, ajustando descuento");
                    if ($valorIngreso == $salarioMinimo) {
                        $descuento = 0.04;
                        Log::info("Ingresos == salario mínimo, descuento = 4%");
                    } elseif ($valorIngreso > $salarioMinimo && $valorIngreso < $salarioMinimo * 2) {
                        $descuento = 0.08;
                        Log::info("Ingresos entre 1 y 2 SMMLV, descuento = 8%");
                    } elseif ($valorIngreso >= $salarioMinimo * 2) {
                        $descuento = 0.12;
                        Log::info("Ingresos >= 2 SMMLV, descuento = 12%");
                    }
                }

                if ($valorIngreso > 5694000) {
                    $descuento += 0.01;
                    Log::info("Ingresos > 5.2 millones, se aumenta +1% adicional. Descuento total = " . ($descuento * 100) . "%");
                }

                Log::info("Descuento final para cédula {$cedulaStr} => " . ($descuento * 100) . "%");

                // Valor ingreso con descuento
                $valorIngresoConDescuento = $valorIngreso - ($valorIngreso * $descuento);
                Log::info("Valor ingreso con descuento = {$valorIngresoConDescuento} (Ingresos - (Ingresos*descuento))", [
                    'ingresos_ajustados' => $valorIngreso,
                    'descuento' => $descuento,
                    'resultado' => $valorIngresoConDescuento
                ]);

                // Cálculo de compraCartera
                if ($valorIngresoConDescuento < $salarioMinimo * 2) {
                    $compraCartera = $valorIngresoConDescuento - $salarioMinimo;
                    Log::info("Valor ingreso con descuento < 2 SMMLV, compraCartera = ingresoDescontado - SMMLV => {$compraCartera}");
                } else {
                    $compraCartera = $valorIngresoConDescuento / 2;
                    Log::info("Valor ingreso con descuento >= 2 SMMLV, compraCartera = ingresoDescontado / 2 => {$compraCartera}");
                }

                // Cupo de libre inversión
                $libreInversion = $compraCartera - $total_egresos;
                Log::info("Cupo libre = compraCartera - total_egresos => {$libreInversion}", [
                    'compraCartera' => $compraCartera,
                    'total_egresos' => $total_egresos
                ]);

                // Agregando al array final
                $results->push([
                    'doc'               => $cedulaStr,
                    'nombre_usuario'    => $nombre_usuario,
                    'tipo_contrato'     => $tipo_contrato,
                    'edad'              => $edad,
                    'fecha_nacimiento'  => $fecha_nacimiento,
                    'pagaduria'         => $pagaduria,
                    'cargo'             => $cargo,
                    'situacion_laboral' => $situacionLaboral,
                    'compra_cartera'    => $compraCartera,
                    'cupo_libre'        => $libreInversion,
                    'cupones'           => empty($cupones) ? null : $cupones,
                    'embargos'          => $embargos,
                    'descuentos'        => $descuentos,
                    'colpensiones'      => $existsInColpensiones,
                    'fiducidiaria'      => $existsInFiducidiaria,
                    'fopep'             => $existsInFopep,
                ]);
            }
        }

        Log::info("Fin de processCedulas_vista", [
            'total_results' => count($results)
        ]);
        return $results;

    } catch (\Exception $e) {
        Log::error("Error en processCedulas_vista: " . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);
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

    public function show()  // ANALISIS DE CARTERA
    {
        Log::info('Inicio del proceso de show');
        Log::info('Fin del proceso de show');
        return view('Demographic.DemographicData');
    }

    public function showAvanzado()  // ANALISIS DE CARTERA AVANZADO
    {
        Log::info('Inicio del proceso de showAvanzado');
        Log::info('Fin del proceso de showAvanzado');
        return view('Demographic.DemographicDataAvanzado');
    }


    public function getDemograficoPorDoc($doc)
    {
        Log::info('Inicio del proceso de getDemograficoPorDoc', ['doc' => $doc]);

        try {
            $record = DatamesGen::where('doc', $doc)
    ->select('doc', 'nombre_usuario', 'cencosto as centro_costo', 'tipo_contrato', 'edad', 'fecha_nacimiento', 'created_at')
    ->orderBy('created_at', 'desc')
    ->first();

if ($record) {
    $record->nombre_usuario = $record->nombre_usuario ?: 'No se encontró nombre en datames';
}


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
        Log::info('Inicio del cálculo para una sola cédula', compact('cedula', 'mes', 'año'));
        try {
            $mes  = (int) $mes;
            $año  = (int) $año;
    
            $resultData = DB::connection('pgsql')
                ->table('fast_aggregate_data')
                ->where('doc', $cedula)
                ->whereRaw('extract(month  from CAST(inicioperiodo AS date)) = ?', [$mes])
                ->whereRaw('extract(year   from CAST(inicioperiodo AS date)) = ?', [$año])
                ->first();
    
            if (!$resultData) {
                Log::info('No se encontró información para la cédula', compact('cedula'));
                return null;
            }
    
            $existsInColpensiones = Colpensiones::on('pgsql')->where('documento', $cedula)->exists();
            $existsInFiducidiaria = Fiducidiaria::on('pgsql')->where('documento', $cedula)->exists();
            $existsInFopep        = DatamesFopep::on('pgsql')->where('doc', $cedula)->exists();
    
            $fechaNacimiento = $resultData->fecha_nacimiento ?? null;
            if ($fechaNacimiento) {
                try {
                    if (is_numeric($fechaNacimiento)) {
                        $fechaNacimiento = Carbon::createFromTimestamp($fechaNacimiento)->format('Y-m-d');
                    } elseif (preg_match('/\d{2}\/\d{2}\/\d{4}/', $fechaNacimiento)) {
                        $fechaNacimiento = Carbon::createFromFormat('d/m/Y', $fechaNacimiento)->format('Y-m-d');
                    } else {
                        $fechaNacimiento = Carbon::parse($fechaNacimiento)->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    Log::error('Error procesando fecha de nacimiento', ['error' => $e->getMessage()]);
                    $fechaNacimiento = null;
                }
            }
    
            $embargos = collect();
            if ($resultData->embargos_concatenados) {
                preg_match_all('/(\d+): ([^,]+), ([\d\/-]+), ([\d,]+)/', $resultData->embargos_concatenados, $matches, PREG_SET_ORDER);
                foreach ($matches as $match) {
                    $embargos->push([
                        'docdeman'     => trim($match[1]),
                        'entidaddeman' => trim($match[2]),
                        'fembini'      => trim($match[3]),
                        'valor'        => (float) str_replace(',', '', $match[4]),
                    ]);
                }
            }
    
            $cupones = collect(explode(', ', $resultData->cupones_concatenados))
                ->map(function ($cupon) {
                    $parts = explode(': ', $cupon);
                    if (count($parts) === 2) {
                        [$concept, $egresos] = $parts;
                        return ['concept' => strtoupper(trim($concept)), 'egresos' => (float) str_replace(',', '', $egresos)];
                    }
                    return null;
                })
                ->filter(function ($cupon) {
                    return $cupon && $cupon['egresos'] > 0;
                })
                ->values()
                ->toArray();
    
            $descuentos = collect(explode(', ', $resultData->descuentos_concatenados))
                ->filter(function ($descuento) {
                    return !str_contains($descuento, 'ALERTA');
                })
                ->map(function ($descuento) {
                    $parts = explode(': ', $descuento);
                    if (count($parts) === 2) {
                        [$mliquid, $valor] = $parts;
                        return ['mliquid' => trim($mliquid), 'valor' => (float) $valor];
                    }
                    return null;
                })
                ->filter(function ($descuento) {
                    return $descuento && $descuento['valor'] > 0;
                });
    
            $salarioMinimo = 1423500;
            $valorIngreso  = $resultData->ingresos_ajustados;
            $totalEgresos  = $resultData->total_egresos;
    
            $descuento = 0.08;
            if (in_array($resultData->pagaduria, ['FOPEP', 'FIDUPREVISORA'])) {
                if ($valorIngreso == $salarioMinimo) {
                    $descuento = 0.04;
                } elseif ($valorIngreso < $salarioMinimo * 2) {
                    $descuento = 0.08;
                } else {
                    $descuento = 0.12;
                }
            }
            if (in_array(strtoupper($resultData->pagaduria), ['CASUR'])) {
                $descuento = 0.04;
            }
            if ($valorIngreso > 5694000) {
                $descuento += 0.01;
            }
    
            $valorIngresoConDescuento = $valorIngreso - ($valorIngreso * $descuento);
    
            $rtsfaValue = collect($cupones)->firstWhere('concept', 'RTFSA')['egresos'] ?? 0;
            if ($valorIngreso > $salarioMinimo * 5) {
                $valorIngresoConDescuento -= $rtsfaValue;
            }
    
            if ($valorIngresoConDescuento < $salarioMinimo * 2) {
                $compraCartera = $valorIngresoConDescuento - $salarioMinimo;
            } else {
                $compraCartera = $valorIngresoConDescuento / 2;
            }
    
            $libreInversion = $compraCartera - $totalEgresos;
    
            return [
                'doc'               => $cedula,
                'nombre_usuario'    => $resultData->nombre_usuario,
                'tipo_contrato'     => $resultData->tipo_contrato,
                'edad'              => $resultData->edad,
                'fecha_nacimiento'  => $fechaNacimiento,
                'pagaduria'         => $resultData->pagaduria,
                'cargo'             => $resultData->cargos,
                'situacion_laboral' => $resultData->situacion_laboral,
                'compra_cartera'    => $compraCartera,
                'cupo_libre'        => $libreInversion,
                'cupones'           => $cupones,
                'embargos'          => $embargos->values()->toArray(),
                'descuentos'        => $descuentos->values()->toArray(),
                'colpensiones'      => $existsInColpensiones,
                'fiducidiaria'      => $existsInFiducidiaria,
                'fopep'             => $existsInFopep,
            ];
        } catch (\Exception $e) {
            Log::error('Error en calcularCupoPorCedula', ['error' => $e->getMessage()]);
            return null;
        }
    }
    
    
    public function uploadPending(Request $r)
    {
        $r->validate([
            'file' => 'required|file|mimes:xlsx,xls',
            'mes'  => 'required|integer|min:1|max:12',
            'anio' => 'required|integer|min:1900|max:'.(date('Y')+1),
        ]);
    
        /* ---------- leer el Excel y obtener $cedulas ---------- */
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $sheet  = $reader->load($r->file('file')->getRealPath())->getActiveSheet();
    
        $col    = array_search('cedulas',
                   array_map('strtolower',$sheet->rangeToArray('A1:'.$sheet->getHighestColumn().'1')[0]));
        if ($col === false) return response()->json(['error'=>'Columna "cedulas" no encontrada'],400);
    
        $cedulas = [];
        foreach ($sheet->getRowIterator(2) as $row) {
            $v = trim($sheet->getCellByColumnAndRow($col+1,$row->getRowIndex())->getValue());
            if ($v!=='') $cedulas[] = $v;
        }
    
        /* ---------- guardar archivo ---------- */
        $storedPath = $r->file('file')
                        ->storeAs('demografico_pending',Str::uuid().'.'.$r->file('file')->getClientOriginalExtension(),'public');
    
        /* ---------- crear registro ---------- */
        $upload = PendingDemographicUpload::create([
            'user_id'       => Auth::id(),
            'original_name' => $r->file('file')->getClientOriginalName(),
            'stored_path'   => $storedPath,
            'mes'           => $r->mes,
            'anio'          => $r->anio,
            'cedulas'       => $cedulas,          // ← se graban
            'status'        => 'pending',
        ]);
    
        return response()->json(['upload_id'=>$upload->id],200);
    }
    

public function listPending()
{
    return PendingDemographicUpload::where('status', 'pending')   
        ->orderBy('created_at', 'desc')
        ->get([
            'id',
            'user_id',
            'original_name',
            'stored_path',
            'mes',
            'anio',
            'status',
            'created_at'
        ])
        ->map(function ($u) {
            return [
                'id'            => $u->id,
                'user_id'       => $u->user_id,
                'original_name' => $u->original_name,
                'mes'           => $u->mes,
                'anio'          => $u->anio,
                'status'        => $u->status,
                'created_at'    => (string) $u->created_at,
            ];
        });
}


public function approveUpload($id)
{
    $u               = PendingDemographicUpload::findOrFail($id);
    $u->timestamps   = false;
    $u->status       = 'analyzed';
    $u->analyzed_by  = Auth::id();
    $u->analyzed_at  = now()->format('Y-m-d H:i:s.u');
    $u->save();

    /* ---- cachear las cédulas para el usuario que analiza ---- */
    Cache::put('cedulas_'.Auth::id(), $u->cedulas, 3600);

    return response()->json([
        'analyzed'      => true,
        'mes'           => $u->mes,
        'año'           => $u->anio,
        'analizado_por' => $u->analyzed_by,
    ]);
}





}