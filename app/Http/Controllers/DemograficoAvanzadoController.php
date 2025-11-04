<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Colpensiones;
use App\Fiducidiaria;
use App\DatamesFopep;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DemograficoAvanzadoController extends Controller
{
    public function show()
    {
        Log::info('Inicio del proceso de showAvanzado');
        return view('Demographic.DemographicDataAvanzado');
    }

    public function upload(Request $request)
    {
        Log::info('[upload] Iniciando método upload...');
        try {
            // Limpiar caché anterior del usuario
            $cacheKey = 'cedulas_data_avanzado_' . Auth::id();
            Cache::forget($cacheKey);
            Log::info('[upload] Caché anterior limpiado para el usuario: ' . Auth::id());

            // Obtener política de portafolio seleccionada
            $politicaPortafolioId = $request->input('politica_portafolio_id');
            $politicaPortafolio = DB::table('politicas_portafolio')
                ->where('id', $politicaPortafolioId)
                ->whereNull('deleted_at')
                ->first();

            if (!$politicaPortafolio) {
                Log::warning('[upload] Política de portafolio no encontrada.');
                return response()->json(['error' => 'Política de portafolio no encontrada'], 400);
            }

            Log::info('[upload] Política de portafolio cargada: ' . $politicaPortafolio->nombre);

            // Obtener política de fondo seleccionada
            $politicaFondoId = $request->input('politica_fondo_id');
            $politicaFondo = DB::table('politicas_fondos')
                ->where('id', $politicaFondoId)
                ->whereNull('deleted_at')
                ->first();

            if (!$politicaFondo) {
                Log::warning('[upload] Política de fondo no encontrada.');
                return response()->json(['error' => 'Política de fondo no encontrada'], 400);
            }

            Log::info('[upload] Política de fondo cargada: ' . $politicaFondo->nombre_fondo);

            // OPTIMIZACIÓN: Guardar las políticas completas en caché (no solo IDs) para evitar consultas SQL en cada página
            $politicasCacheKey = 'politicas_completas_' . Auth::id();
            $politicasData = [
                'portafolio' => $politicaPortafolio,
                'fondo' => $politicaFondo
            ];
            Cache::put($politicasCacheKey, $politicasData, 3600); // 1 hora
            Log::info('[upload] Políticas completas guardadas en caché para evitar consultas repetidas');

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

            // Validar que exista la columna cedula
            if (!isset($headerMap['cedula'])) {
                Log::warning('[upload] No se encontró la columna "cedula" en el archivo Excel.');
                return response()->json(['error' => 'No se encontró la columna "cedula"'], 400);
            }

            // Definir las columnas adicionales que queremos extraer
            $additionalColumns = [
                'operacion',
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
                'respetar cuota pactada',
                'cupo colpensiones',
                'cupo fopep',
                'cupo fiduprevisora'
            ];

            // Columnas que contienen valores monetarios
            $moneyColumns = [
                'valor desembolso',
                'saldo capital original',
                'intereses corrientes',
                'intereses de mora',
                'seguros',
                'otros conceptos',
                'cuota pactada',
                'cupo colpensiones',
                'cupo fopep',
                'cupo fiduprevisora'
            ];

            $dataRows = [];
            foreach ($worksheet->getRowIterator(2) as $row) {
                $rowIndex = $row->getRowIndex();
                $cedulaRaw = $worksheet->getCellByColumnAndRow($headerMap['cedula'] + 1, $rowIndex)->getValue();
                $cedula = trim($cedulaRaw);

                if (empty($cedula)) {
                    continue;
                }

                // Asegurar que la cédula sea string sin espacios
                $cedula = (string)$cedula;

                $rowData = ['cedula' => $cedula];

                // Extraer campos adicionales si existen
                foreach ($additionalColumns as $columnName) {
                    $columnKey = strtolower($columnName);
                    if (isset($headerMap[$columnKey])) {
                        $value = $worksheet->getCellByColumnAndRow($headerMap[$columnKey] + 1, $rowIndex)->getValue();

                        // Si es una columna monetaria, limpiar el formato
                        if (in_array($columnName, $moneyColumns)) {
                            $value = $this->cleanMoneyValue($value);
                        }

                        $rowData[$columnKey] = $value;
                    }
                }

                $dataRows[] = $rowData;
            }

            Log::info('[upload] Total de filas extraídas: '.count($dataRows));
            Log::info('[upload] Ejemplo de primera fila extraída:', ['first_row' => $dataRows[0] ?? 'No hay filas']);

            Cache::put($cacheKey, $dataRows, 3600);
            Log::info('[upload] Datos almacenados en caché con key: '.$cacheKey);

            // Verificar que se guardó correctamente
            $cached = Cache::get($cacheKey);
            Log::info('[upload] Verificación de caché - Total items: ' . count($cached));
            Log::info('[upload] Verificación de caché - Primera fila:', ['first_cached' => $cached[0] ?? 'No hay datos']);

            return response()->json(['uploaded' => true], 200);
        } catch (\Exception $e) {
            Log::error('[upload] Error al procesar el archivo: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Error al procesar el archivo'], 500);
        }
    }

    public function fetchPaginatedResults(Request $request)
    {
        Log::info('[fetchPaginatedResults] Iniciando método...');
        try {
            $page = (int) $request->query('page', 1);
            $perPage = (int) $request->query('perPage', 30);
            $mes = $request->query('mes');
            $año = $request->query('año');

            Log::info("[fetchPaginatedResults] Parámetros recibidos - Page: {$page}, PerPage: {$perPage}, Mes: {$mes}, Año: {$año}");

            $cacheKey = 'cedulas_data_avanzado_' . Auth::id();
            $cedulasData = Cache::get($cacheKey, []);
            $total = count($cedulasData);

            Log::info("[fetchPaginatedResults] Total cédulas en caché: {$total}");

            if ($total < 1) {
                Log::info('[fetchPaginatedResults] No hay cédulas en caché.');
                return response()->json([
                    'data' => [],
                    'total' => 0,
                    'page' => $page,
                    'perPage' => $perPage,
                    'hasMore' => false
                ]);
            }

            // OPTIMIZACIÓN: Recuperar políticas completas desde caché (sin consultas SQL)
            $politicasCacheKey = 'politicas_completas_' . Auth::id();
            $politicasData = Cache::get($politicasCacheKey);

            if (!$politicasData) {
                Log::warning('[fetchPaginatedResults] No se encontraron políticas en caché.');
                return response()->json(['error' => 'No se encontraron las políticas. Por favor, recargue el archivo.'], 400);
            }

            $politicaPortafolio = $politicasData['portafolio'];
            $politicaFondo = $politicasData['fondo'];

            Log::info('[fetchPaginatedResults] Políticas recuperadas desde caché (0 queries SQL): ' .
                $politicaPortafolio->nombre . ' + ' . $politicaFondo->nombre_fondo);

            $offset = ($page - 1) * $perPage;
            if ($offset >= $total) {
                Log::info("[fetchPaginatedResults] Offset {$offset} supera el total ({$total}).");
                return response()->json([
                    'data' => [],
                    'total' => $total,
                    'page' => $page,
                    'perPage' => $perPage,
                    'hasMore' => false
                ]);
            }

            $cedulasDataChunk = array_slice($cedulasData, $offset, $perPage);
            Log::info('[fetchPaginatedResults] Cédulas chunk size: '.count($cedulasDataChunk));

            // Extraer solo las cédulas para la consulta
            $cedulas = array_column($cedulasDataChunk, 'cedula');

            // Usar processCedulas_vista que consulta fast_aggregate_data con mes y año
            $resultsCollection = $this->processCedulas($cedulas, $mes, $año);
            $results = $resultsCollection->toArray(); // Convertir Collection a Array para poder usar referencias

            // Merge con los datos adicionales del Excel
            Log::info('[fetchPaginatedResults] Iniciando merge de datos del Excel');
            Log::info('[fetchPaginatedResults] Total resultados de BD: ' . count($results));
            Log::info('[fetchPaginatedResults] Total datos Excel en chunk: ' . count($cedulasDataChunk));

            if (count($cedulasDataChunk) > 0) {
                Log::info('[fetchPaginatedResults] Ejemplo de dato Excel:', ['excel_row' => $cedulasDataChunk[0]]);
            }

            // OPTIMIZACIÓN: Crear hash map de datos Excel para lookup O(1) en lugar de O(n²)
            Log::info('[fetchPaginatedResults] Creando hash map de datos Excel');
            $excelDataMap = [];
            foreach ($cedulasDataChunk as $excelRow) {
                $cedulaStr = trim((string)$excelRow['cedula']);
                $excelDataMap[$cedulaStr] = $excelRow;
            }
            Log::info('[fetchPaginatedResults] Hash map creado con ' . count($excelDataMap) . ' entradas');

            $matchCount = 0;

            // Iterar solo una vez sobre los results, hacer lookup directo en el map
            for ($i = 0; $i < count($results); $i++) {
                // Convertir a string para comparación
                $docStr = trim((string)$results[$i]['doc']);

                // Lookup O(1) en lugar de loop O(n)
                if (isset($excelDataMap[$docStr])) {
                    $excelRow = $excelDataMap[$docStr];
                    $matchCount++;

                    Log::info('[fetchPaginatedResults] MATCH ENCONTRADO para cédula: ' . $docStr, [
                        'datos_excel_keys' => array_keys($excelRow)
                    ]);

                    // Agregar campos adicionales del Excel
                    $results[$i]['operacion'] = $excelRow['operacion'] ?? null;
                    $results[$i]['valor_desembolso'] = $excelRow['valor desembolso'] ?? null;
                    $results[$i]['saldo_capital_original'] = $excelRow['saldo capital original'] ?? null;
                    $results[$i]['intereses_corrientes'] = $excelRow['intereses corrientes'] ?? null;
                    $results[$i]['intereses_de_mora'] = $excelRow['intereses de mora'] ?? null;
                    $results[$i]['seguros'] = $excelRow['seguros'] ?? null;
                    $results[$i]['otros_conceptos'] = $excelRow['otros conceptos'] ?? null;
                    $results[$i]['tasa_pactada'] = $excelRow['tasa pactada'] ?? null;
                    $results[$i]['respetar_tasa_pactada'] = $excelRow['respetar tasa pactada'] ?? null;
                    $results[$i]['plazo_pactado'] = $excelRow['plazo pactado'] ?? null;
                    $results[$i]['cuota_pactada'] = $excelRow['cuota pactada'] ?? null;
                    $results[$i]['respetar_cuota_pactada'] = $excelRow['respetar cuota pactada'] ?? null;

                    // Log de diagnóstico para campos problemáticos
                    Log::info('[fetchPaginatedResults] Valores leídos del Excel para doc ' . $docStr, [
                        'tasa_pactada' => $excelRow['tasa pactada'] ?? 'NO_EXISTE',
                        'respetar_tasa_pactada' => $excelRow['respetar tasa pactada'] ?? 'NO_EXISTE',
                        'plazo_pactado' => $excelRow['plazo pactado'] ?? 'NO_EXISTE',
                        'respetar_plazo_pactado' => $excelRow['respetar plazo pactado'] ?? 'NO_EXISTE',
                        'cuota_pactada' => $excelRow['cuota pactada'] ?? 'NO_EXISTE',
                        'respetar_cuota_pactada' => $excelRow['respetar cuota pactada'] ?? 'NO_EXISTE',
                        'excel_keys' => array_keys($excelRow)
                    ]);

                    // Calcular Total Obligación (suma de valores monetarios del crédito, excluyendo valor desembolso)
                    $total_obligacion =
                        ($results[$i]['saldo_capital_original'] ?? 0) +
                        ($results[$i]['intereses_corrientes'] ?? 0) +
                        ($results[$i]['intereses_de_mora'] ?? 0) +
                        ($results[$i]['seguros'] ?? 0) +
                        ($results[$i]['otros_conceptos'] ?? 0);

                    $results[$i]['total_obligacion'] = $total_obligacion;

                    // Calcular datos de Portafolio usando valores de la política seleccionada
                    $saldo_capital = $results[$i]['saldo_capital_original'] ?? 0;

                    // Usar porcentajes de la política de portafolio
                    $costo_compra_portafolio = $saldo_capital * ($politicaPortafolio->porcentaje_compra_portafolio / 100);
                    $costo_comision_comercial = $costo_compra_portafolio * ($politicaPortafolio->porcentaje_comision_comercial / 100);
                    $costo_reincorporacion_gaf = $total_obligacion * ($politicaPortafolio->porcentaje_reincorporacion_gaf / 100);
                    $costo_seguro_vd = ($total_obligacion * ($politicaPortafolio->porcentaje_costo_seguro_vd / 100)) * 3; // 3 Meses
                    $costos_fiduciarios = (1423500 * 5) / 51; // Costos Fiduciarios (Fiducoomeva) - mantener fijo por ahora

                    // Usar valores directos de la política
                    $reporte_centrales = $politicaPortafolio->costo_reporte_centrales;
                    $tecnologia = $politicaPortafolio->tecnologia;

                    // Guardar valores iniciales de portafolio (sin coadministración aún, se calculará después)
                    $results[$i]['costo_compra_portafolio'] = $costo_compra_portafolio;
                    $results[$i]['costo_comision_comercial'] = $costo_comision_comercial;
                    $results[$i]['costo_reincorporacion_gaf'] = $costo_reincorporacion_gaf;
                    $results[$i]['costo_coadministracion'] = 0; // Se calculará después con cuota a incorporar
                    $results[$i]['costo_seguro_vd'] = $costo_seguro_vd;
                    $results[$i]['costos_fiduciarios'] = $costos_fiduciarios;
                    $results[$i]['reporte_centrales'] = $reporte_centrales;
                    $results[$i]['tecnologia'] = $tecnologia;
                    $results[$i]['sub_total_costo_compra_adm'] = 0; // Se calculará después con coadministración

                    // Calcular datos de AMI (Cupo)
                    // Cuota incorporada previamente = compra_cartera
                    $cuota_incorporada_previamente = $results[$i]['compra_cartera'] ?? 0;

                    // Cupo Sem = cupo_libre (libre inversión)
                    $cupo_sem = $results[$i]['cupo_libre'] ?? 0;

                    // Otros cupos vienen del Excel
                    $cupo_colpensiones = $excelRow['cupo colpensiones'] ?? 0;
                    $cupo_fopep = $excelRow['cupo fopep'] ?? 0;
                    $cupo_fiduprevisora = $excelRow['cupo fiduprevisora'] ?? 0;

                    // Total Cupo Disponible = suma de todos
                    $total_cupo_disponible = $cuota_incorporada_previamente + $cupo_sem + $cupo_colpensiones + $cupo_fopep + $cupo_fiduprevisora;

                    $results[$i]['cuota_incorporada_previamente'] = $cuota_incorporada_previamente;
                    $results[$i]['cupo_sem'] = $cupo_sem;
                    $results[$i]['cupo_colpensiones'] = $cupo_colpensiones;
                    $results[$i]['cupo_fopep'] = $cupo_fopep;
                    $results[$i]['cupo_fiduprevisora'] = $cupo_fiduprevisora;
                    $results[$i]['total_cupo_disponible'] = $total_cupo_disponible;

                    // Datos para Cuota a Incorporar
                    // Ya tenemos: tasa_pactada, respetar_tasa_pactada, plazo_pactado, respetar_plazo_pactado, cuota_pactada, respetar_cuota_pactada

                    // Calcular Tasa Nueva Libranza Ck
                    // Fórmula: SI(RESPETAR TASA PACTADA="SI", SI(Tasa Pactada>TASA USURA, TASA USURA, Tasa Pactada), TASA USURA)
                    $tasa_nueva_libranza_ck = 0;
                    if ($politicaFondo) {
                        $tasa_usura = $politicaFondo->tasa_usura ?? 0;
                        $tasa_pactada = $excelRow['tasa pactada'] ?? 0;
                        $respetar_tasa_pactada = strtoupper(trim($excelRow['respetar tasa pactada'] ?? ''));

                        if ($respetar_tasa_pactada == 'SI') {
                            if ($tasa_pactada > $tasa_usura) {
                                $tasa_nueva_libranza_ck = $tasa_usura;
                            } else {
                                $tasa_nueva_libranza_ck = $tasa_pactada;
                            }
                        } else {
                            $tasa_nueva_libranza_ck = $tasa_usura;
                        }
                    }

                    // Calcular Plazo Nueva Libranza Ck
                    // Fórmula: SI(RESPETAR PLAZO PACTADO="SI", SI(PLAZO PACTADO>PLAZO MAX, PLAZO MAX, PLAZO PACTADO), PLAZO MAX)
                    $plazo_nueva_libranza_ck = 0;
                    if ($politicaFondo) {
                        $plazo_max = $politicaFondo->plazo_max ?? 0;
                        $plazo_pactado = $excelRow['plazo pactado'] ?? 0;
                        // Leer y normalizar el valor del Excel
                        $respetar_plazo_pactado_raw = $excelRow['respetar plazo pactado'] ?? null;
                        $respetar_plazo_pactado = $respetar_plazo_pactado_raw ? strtoupper(trim($respetar_plazo_pactado_raw)) : 'NO';

                        if ($respetar_plazo_pactado == 'SI') {
                            if ($plazo_pactado > $plazo_max) {
                                $plazo_nueva_libranza_ck = $plazo_max;
                            } else {
                                $plazo_nueva_libranza_ck = $plazo_pactado;
                            }
                        } else {
                            $plazo_nueva_libranza_ck = $plazo_max;
                        }

                        // Solo actualizar si no existía previamente en el resultado
                        if (!isset($results[$i]['respetar_plazo_pactado']) || $results[$i]['respetar_plazo_pactado'] === null) {
                            $results[$i]['respetar_plazo_pactado'] = $respetar_plazo_pactado;
                        }
                    }

                    // Calcular CUOTA A INCORPORAR
                    // Fórmula: SI(RESPETAR CUOTA PACTADA="SI", SI(CUOTA PACTADA>TOTAL CUPO DISPONIBLE, TOTAL CUPO DISPONIBLE, CUOTA PACTADA), TOTAL CUPO DISPONIBLE)
                    $cuota_a_incorporar = 0;
                    $cuota_pactada = $excelRow['cuota pactada'] ?? 0;
                    // Leer y normalizar el valor del Excel
                    $respetar_cuota_pactada_raw = $excelRow['respetar cuota pactada'] ?? null;
                    $respetar_cuota_pactada = $respetar_cuota_pactada_raw ? strtoupper(trim($respetar_cuota_pactada_raw)) : 'NO';

                    if ($respetar_cuota_pactada == 'SI') {
                        if ($cuota_pactada > $total_cupo_disponible) {
                            $cuota_a_incorporar = $total_cupo_disponible;
                        } else {
                            $cuota_a_incorporar = $cuota_pactada;
                        }
                    } else {
                        $cuota_a_incorporar = $total_cupo_disponible;
                    }

                    // Solo actualizar si no existía previamente en el resultado
                    if (!isset($results[$i]['respetar_cuota_pactada']) || $results[$i]['respetar_cuota_pactada'] === null) {
                        $results[$i]['respetar_cuota_pactada'] = $respetar_cuota_pactada;
                    }

                    // Calcular Costo Coadministración
                    // Fórmula: CUOTA_A_INCORPORAR * (costo_administracion %)
                    $costo_coadministracion = $cuota_a_incorporar * ($politicaPortafolio->costo_administracion / 100);

                    // Actualizar el valor en results (debe recalcular el sub_total también)
                    $results[$i]['costo_coadministracion'] = $costo_coadministracion;

                    // Recalcular Sub Total Costo Compra + Adm (NPL´S) con el nuevo valor de coadministración
                    $sub_total_costo_compra_adm =
                        $results[$i]['costo_compra_portafolio'] +
                        $results[$i]['costo_comision_comercial'] +
                        $results[$i]['costo_reincorporacion_gaf'] +
                        $costo_coadministracion +
                        $results[$i]['costo_seguro_vd'] +
                        $results[$i]['costos_fiduciarios'] +
                        $results[$i]['reporte_centrales'] +
                        $results[$i]['tecnologia'];

                    $results[$i]['sub_total_costo_compra_adm'] = $sub_total_costo_compra_adm;

                    // OPTIMIZACIÓN: Usar método helper optimizado para cálculos financieros complejos
                    // Agrupa TASA y NPER con validaciones tempranas
                    $valoresFinancieros = $this->calcularValoresFinancierosOptimizado([
                        'plazo_nueva_libranza_ck' => $plazo_nueva_libranza_ck,
                        'cuota_a_incorporar' => $cuota_a_incorporar,
                        'total_obligacion' => $results[$i]['total_obligacion'],
                        'total_cupo_disponible' => $total_cupo_disponible
                    ], $politicaFondo);

                    $results[$i]['tasa_nueva_libranza_ck'] = $tasa_nueva_libranza_ck;
                    $results[$i]['plazo_nueva_libranza_ck'] = $plazo_nueva_libranza_ck;
                    $results[$i]['cuota_a_incorporar'] = $cuota_a_incorporar;
                    $results[$i]['tasa_modificada_conservando_plazo_180'] = $valoresFinancieros['tasa_modificada_conservando_plazo_180'];
                    $results[$i]['plazo_modificado_conservando_tasa_188'] = $valoresFinancieros['plazo_modificado_conservando_tasa_188'];

                    Log::info('[fetchPaginatedResults] Datos mergeados para doc ' . $docStr . ':', [
                        'operacion' => $results[$i]['operacion'],
                        'valor_desembolso' => $results[$i]['valor_desembolso'],
                        'total_obligacion' => $total_obligacion,
                        'costo_compra_portafolio' => $costo_compra_portafolio,
                        'costo_comision_comercial' => $costo_comision_comercial
                    ]);
                }
            }

            Log::info('[fetchPaginatedResults] Total de matches encontrados: ' . $matchCount);

            $hasMore = ($offset + $perPage) < $total;
            Log::info("[fetchPaginatedResults] hasMore: ".($hasMore ? 'true' : 'false'));

            return response()->json([
                'data' => $results,
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'hasMore' => $hasMore
            ]);
        } catch (\Exception $e) {
            Log::error('[fetchPaginatedResults] Error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Error en la obtención de resultados paginados'], 500);
        }
    }

    private function processCedulas($cedulas, $mes, $año)
    {
        try {
            Log::info("Inicio de processCedulas", [
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
                            } elseif (preg_match('/\d{2}\/\d{2}\/\d{4}/', $fecha_nacimiento)) {
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
                            '/(\d+): ([^,]+), ([\d\/\-]+), ([\d,]+)/',
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
                        ->unique('mliquid');
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

            Log::info("Fin de processCedulas", [
                'total_results' => count($results)
            ]);
            return $results;

        } catch (\Exception $e) {
            Log::error("Error en processCedulas: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Limpia valores monetarios removiendo símbolos de moneda, espacios y comas
     * Convierte el valor a número
     *
     * @param mixed $value
     * @return float|null
     */
    private function cleanMoneyValue($value)
    {
        if (empty($value)) {
            return null;
        }

        // Convertir a string si no lo es
        $value = (string) $value;

        // Remover símbolos de moneda ($, €, etc.), espacios, y comas
        $cleaned = preg_replace('/[$€£¥,\s]/', '', $value);

        // Si después de limpiar queda vacío, retornar null
        if (empty($cleaned)) {
            return null;
        }

        // Convertir a float
        return (float) $cleaned;
    }

    /**
     * Calcula la tasa de interés de un préstamo (equivalente a la función TASA de Excel)
     *
     * @param int $nper Número de períodos
     * @param float $pmt Pago por período
     * @param float $pv Valor presente (negativo para préstamos)
     * @param float $fv Valor futuro (opcional, por defecto 0)
     * @param int $type Tipo de pago: 0 = fin del período, 1 = inicio (opcional)
     * @param float $guess Estimación inicial (opcional, por defecto 0.1)
     * @return float Tasa de interés por período
     */
    private function calculateRate($nper, $pmt, $pv, $fv = 0, $type = 0, $guess = 0.1)
    {
        // Si no hay períodos o pago, retornar 0
        if ($nper <= 0 || $pmt == 0) {
            return 0;
        }

        // Método de Newton-Raphson para encontrar la tasa
        $rate = $guess;
        $maxIterations = 100;
        $precision = 1e-10;

        for ($i = 0; $i < $maxIterations; $i++) {
            // Calcular f(rate) y f'(rate)
            $f = 0;
            $df = 0;

            if (abs($rate) < $precision) {
                $f = $pv + $pmt * $nper + $fv;
                $df = $pmt * $nper * ($nper - 1) / 2;
            } else {
                $temp = pow(1 + $rate, $nper);
                $f = $pv * $temp + $pmt * (1 + $rate * $type) * ($temp - 1) / $rate + $fv;
                $df = $nper * $pv * pow(1 + $rate, $nper - 1)
                    + $pmt * (1 + $rate * $type) * ($nper * pow(1 + $rate, $nper - 1) * $rate - $temp + 1) / ($rate * $rate)
                    + $pmt * $type * ($temp - 1) / $rate;
            }

            // Si la derivada es muy pequeña, no podemos continuar
            if (abs($df) < $precision) {
                break;
            }

            // Calcular nueva tasa
            $newRate = $rate - $f / $df;

            // Si convergió, retornar
            if (abs($newRate - $rate) < $precision) {
                return $newRate;
            }

            $rate = $newRate;
        }

        // Si no convergió, retornar la última aproximación
        return $rate;
    }

    /**
     * Calcula el número de períodos de un préstamo (equivalente a la función NPER de Excel)
     *
     * @param float $rate Tasa de interés por período
     * @param float $pmt Pago por período
     * @param float $pv Valor presente (negativo para préstamos)
     * @param float $fv Valor futuro (opcional, por defecto 0)
     * @param int $type Tipo de pago: 0 = fin del período, 1 = inicio (opcional)
     * @return float Número de períodos
     */
    private function calculateNper($rate, $pmt, $pv, $fv = 0, $type = 0)
    {
        // Si el pago es 0, no se puede calcular
        if ($pmt == 0) {
            return 0;
        }

        // Si la tasa es 0, usar fórmula simplificada
        if ($rate == 0) {
            return -($pv + $fv) / $pmt;
        }

        // Fórmula NPER: log((pmt - fv*rate) / (pmt + pv*rate)) / log(1 + rate)
        $numerator = $pmt - ($fv * $rate);
        $denominator = $pmt + ($pv * $rate);

        // Validar que el denominador no sea 0 y que la división sea positiva
        if ($denominator == 0 || ($numerator / $denominator) <= 0) {
            return 0;
        }

        $nper = log($numerator / $denominator) / log(1 + $rate);

        return $nper;
    }

    /**
     * Guardar análisis de cartera en la base de datos
     */
    public function guardarAnalisis(Request $request)
    {
        Log::info('[guardarAnalisis] Iniciando guardado de análisis...');

        try {
            $userId = Auth::id();

            // Log de todos los datos recibidos para debug
            Log::info('[guardarAnalisis] Datos recibidos en el request', [
                'all_inputs' => $request->all()
            ]);

            // Validar datos del request (hacer opcional las políticas por ahora)
            $request->validate([
                'descripcion' => 'nullable|string|max:500'
            ]);

            // Obtener datos del caché
            $cacheKey = 'cedulas_data_avanzado_' . $userId;
            $cedulasData = Cache::get($cacheKey, []);

            if (empty($cedulasData)) {
                Log::warning('[guardarAnalisis] No hay datos en caché para guardar');
                return response()->json([
                    'error' => 'No hay datos para guardar. Por favor, cargue un archivo primero.'
                ], 400);
            }

            // Obtener políticas: primero del request, si no existen, del caché
            $politicaPortafolioId = $request->input('politica_portafolio_id');
            $politicaFondoId = $request->input('politica_fondo_id');

            // Si no vienen en el request, intentar obtenerlas del caché de políticas completas
            if (!$politicaPortafolioId || !$politicaFondoId) {
                Log::info('[guardarAnalisis] Políticas no vienen en request, buscando en caché...');
                $politicasCacheKey = 'politicas_completas_' . $userId;
                $politicasData = Cache::get($politicasCacheKey);

                if ($politicasData) {
                    $politicaPortafolioId = $politicasData['portafolio']->id ?? null;
                    $politicaFondoId = $politicasData['fondo']->id ?? null;
                    Log::info('[guardarAnalisis] Políticas recuperadas desde caché completas', [
                        'politica_portafolio_id' => $politicaPortafolioId,
                        'politica_fondo_id' => $politicaFondoId
                    ]);
                }
            }

            // Validar que tengamos las políticas
            if (!$politicaPortafolioId || !$politicaFondoId) {
                Log::warning('[guardarAnalisis] No se encontraron políticas');
                return response()->json([
                    'error' => 'No se encontraron las políticas utilizadas. Por favor, recargue el análisis.'
                ], 400);
            }

            Log::info('[guardarAnalisis] IDs de políticas a utilizar', [
                'politica_portafolio_id' => $politicaPortafolioId,
                'politica_fondo_id' => $politicaFondoId
            ]);

            // Obtener datos de las políticas
            $politicaPortafolio = DB::table('politicas_portafolio')
                ->where('id', $politicaPortafolioId)
                ->first();

            $politicaFondo = DB::table('politicas_fondos')
                ->where('id', $politicaFondoId)
                ->first();

            if (!$politicaPortafolio || !$politicaFondo) {
                Log::warning('[guardarAnalisis] Políticas no encontradas en BD');
                return response()->json([
                    'error' => 'Las políticas utilizadas ya no existen en el sistema.'
                ], 400);
            }

            // Obtener mes, año y nombre de archivo del request o caché
            $mes = $request->input('mes');
            $anio = $request->input('anio');
            $nombreArchivo = $request->input('nombre_archivo', 'archivo_' . date('YmdHis') . '.xlsx');

            if (!$mes || !$anio) {
                Log::warning('[guardarAnalisis] Mes o año no proporcionados');
                return response()->json([
                    'error' => 'Debe proporcionar el mes y año del análisis.'
                ], 400);
            }

            // Obtener resultados procesados del request
            $datosProcessados = $request->input('datos_procesados', []);

            if (empty($datosProcessados)) {
                Log::warning('[guardarAnalisis] No hay datos procesados para guardar');
                return response()->json([
                    'error' => 'No hay resultados procesados para guardar.'
                ], 400);
            }

            // Calcular estadísticas
            $totalRegistros = count($datosProcessados);
            $registrosExitosos = 0;
            $registrosConErrores = 0;

            foreach ($datosProcessados as $registro) {
                if (isset($registro['total_cupo_disponible']) && $registro['total_cupo_disponible'] > 0) {
                    $registrosExitosos++;
                } else {
                    $registrosConErrores++;
                }
            }

            // Preparar metadatos
            $metadatos = [
                'nombre_politica_portafolio' => $politicaPortafolio->nombre ?? 'N/D',
                'nombre_politica_fondo' => $politicaFondo->nombre_fondo ?? 'N/D',
                'politica_portafolio' => [
                    'id' => $politicaPortafolio->id,
                    'nombre' => $politicaPortafolio->nombre,
                    'porcentaje_compra_portafolio' => $politicaPortafolio->porcentaje_compra_portafolio,
                    'porcentaje_comision_comercial' => $politicaPortafolio->porcentaje_comision_comercial,
                    'costo_administracion' => $politicaPortafolio->costo_administracion,
                ],
                'politica_fondo' => [
                    'id' => $politicaFondo->id,
                    'nombre_fondo' => $politicaFondo->nombre_fondo,
                    'tasa_usura' => $politicaFondo->tasa_usura ?? null,
                    'plazo_max' => $politicaFondo->plazo_max ?? null,
                ],
                'fecha_guardado' => now()->toDateTimeString(),
                'usuario_id' => $userId
            ];

            // Crear registro en la base de datos
            $estudio = \App\EstudioCartera::create([
                'user_id' => $userId,
                'politica_portafolio_id' => $politicaPortafolioId,
                'politica_fondo_id' => $politicaFondoId,
                'nombre_archivo' => $nombreArchivo,
                'mes' => $mes,
                'anio' => $anio,
                'descripcion' => $request->input('descripcion'),
                'total_registros' => $totalRegistros,
                'registros_exitosos' => $registrosExitosos,
                'registros_con_errores' => $registrosConErrores,
                'datos_procesados' => json_encode($datosProcessados),
                'metadatos' => json_encode($metadatos)
            ]);

            Log::info('[guardarAnalisis] Análisis guardado exitosamente con ID: ' . $estudio->id);

            return response()->json([
                'success' => true,
                'message' => 'Análisis guardado exitosamente',
                'estudio_id' => $estudio->id,
                'datos' => [
                    'id' => $estudio->id,
                    'periodo' => $mes . '/' . $anio,
                    'total_registros' => $totalRegistros,
                    'registros_exitosos' => $registrosExitosos,
                    'fecha_creacion' => $estudio->created_at->format('d/m/Y H:i')
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('[guardarAnalisis] Error al guardar análisis: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Error al guardar el análisis: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Limpiar caché de análisis del usuario actual
     */
    public function limpiarCache(Request $request)
    {
        Log::info('[limpiarCache] Iniciando limpieza de caché...');

        try {
            $userId = Auth::id();

            // Limpiar caché de cédulas
            $cacheKey = 'cedulas_data_avanzado_' . $userId;
            Cache::forget($cacheKey);
            Log::info('[limpiarCache] Caché de cédulas limpiado: ' . $cacheKey);

            // Limpiar caché de políticas
            $politicaCacheKey = 'politica_portafolio_id_' . $userId;
            Cache::forget($politicaCacheKey);
            Log::info('[limpiarCache] Caché de política portafolio limpiado: ' . $politicaCacheKey);

            $politicaFondoCacheKey = 'politica_fondo_id_' . $userId;
            Cache::forget($politicaFondoCacheKey);
            Log::info('[limpiarCache] Caché de política fondo limpiado: ' . $politicaFondoCacheKey);

            Log::info('[limpiarCache] Limpieza de caché completada exitosamente');

            return response()->json([
                'success' => true,
                'message' => 'Caché limpiado exitosamente'
            ], 200);

        } catch (\Exception $e) {
            Log::error('[limpiarCache] Error al limpiar caché: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Error al limpiar el caché: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * OPTIMIZACIÓN: Método helper para calcular valores financieros complejos de forma eficiente
     * Agrupa TASA y NPER con validaciones tempranas para evitar cálculos innecesarios
     *
     * @param array $data Datos necesarios para los cálculos
     * @param object $politicaFondo Política de fondo con parámetros
     * @return array Resultados calculados
     */
    private function calcularValoresFinancierosOptimizado($data, $politicaFondo)
    {
        $resultado = [
            'tasa_modificada_conservando_plazo_180' => 0,
            'plazo_modificado_conservando_tasa_188' => 0
        ];

        // Validación temprana: si no hay política o valores críticos son 0, retornar ceros
        if (!$politicaFondo) {
            return $resultado;
        }

        $plazo_nueva_libranza_ck = $data['plazo_nueva_libranza_ck'] ?? 0;
        $cuota_a_incorporar = $data['cuota_a_incorporar'] ?? 0;
        $total_obligacion = $data['total_obligacion'] ?? 0;
        $total_cupo_disponible = $data['total_cupo_disponible'] ?? 0;
        $costo_asegurabilidad_mes = $politicaFondo->costo_asegurabilidad_mes ?? 0;

        // Cálculo 1: Tasa Modificada Conservando Plazo 180
        // Solo calcular si los valores son válidos
        if ($plazo_nueva_libranza_ck > 0 && $cuota_a_incorporar > 0 && $total_obligacion > 0) {
            $tasa_calculada = $this->calculateRate(
                $plazo_nueva_libranza_ck,
                $cuota_a_incorporar,
                -$total_obligacion,
                0,
                0
            );

            $resultado['tasa_modificada_conservando_plazo_180'] =
                ($tasa_calculada - ($costo_asegurabilidad_mes / 100)) * 100;
        }

        // Cálculo 2: Plazo Modificado Conservando Tasa 1.88%
        // Solo calcular si los valores son válidos
        if ($cuota_a_incorporar > 0 && $total_cupo_disponible > 0) {
            $tasa_fija_188 = 0.0188; // 1.88%
            $tasa_total = $tasa_fija_188 + ($costo_asegurabilidad_mes / 100);

            $plazo_calculado = $this->calculateNper(
                $tasa_total,
                $cuota_a_incorporar,
                -$total_cupo_disponible,
                0,
                0
            );

            $plazo_redondeado = round($plazo_calculado, 0);

            // Validar rango razonable
            if ($plazo_redondeado > 0 && $plazo_redondeado <= 500) {
                $resultado['plazo_modificado_conservando_tasa_188'] = $plazo_redondeado;
            }
        }

        return $resultado;
    }

    /**
     * Exportar datos actuales a Excel con formato desde el frontend
     */
    public function exportarExcelConFormato(Request $request)
    {
        Log::info('[exportarExcelConFormato] Iniciando exportación con formato...');

        try {
            // Obtener datos del request
            $datos = $request->input('datos', []);

            if (empty($datos)) {
                Log::warning('[exportarExcelConFormato] No hay datos para exportar');
                return response()->json([
                    'error' => 'No hay datos para exportar'
                ], 400);
            }

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Análisis Cartera Avanzado');

            // Definir headers (41 columnas totales)
            $headers = [
                'Operación',
                'Cédula',
                'Nombre',
                'Secretaria',
                'Colpensiones',
                'Fiduprevisora',
                'Fopep',
                'Edad',
                'Desembolso',
                'Saldo Capital Original',
                'Intereses Corrientes',
                'Intereses De Mora',
                'Seguros',
                'Otros Concepto',
                'Total Obligación',
                'Costo Compra Portafolio',
                'Costo Comision Comercial',
                'Costo Re-Incorporación Gaf',
                'Costo Coadministración',
                'Costo Seguro V.D',
                'Costos Fiduciarios',
                'Reporte Centrales',
                'Tecnología',
                'Subtotal Costo Compra + Adm (Npl´S)',
                'Cuota Incorporada Previamente',
                'Cupo Sem',
                'Cupo Colpensiones',
                'Cupo Fopep',
                'Cupo Fiduprevisora',
                'Total Cupo Disponible',
                'Tasa Pactada',
                'Respetar Tasa Pactada',
                'Tasa Nueva Libranza Ck',
                'Plazo Pactado',
                'Respetar Plazo Pactado',
                'Plazo Nueva Libranza Ck',
                'Cuota Pactada',
                'Respetar Cuota Pactada',
                'Cuota A Incorporar',
                'Tasa Modificada Conservando Plazo 180)',
                'Plazo Modificado Conservando Tasa 1,88%)'
            ];

            // Escribir headers
            $sheet->fromArray([$headers], null, 'A1');

            // Aplicar estilo a headers (color verde GAF)
            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '2C8C73']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ]
            ];
            $sheet->getStyle('A1:AO1')->applyFromArray($headerStyle);

            // Escribir datos
            $row = 2;
            foreach ($datos as $item) {
                $rowData = [
                    $item['operacion'] ?? '',
                    $item['doc'] ?? '',
                    $item['nombre_usuario'] ?? '',
                    $item['pagaduria'] ?? '',
                    ($item['colpensiones'] ?? false) ? 'Sí' : 'No',
                    ($item['fiducidiaria'] ?? false) ? 'Sí' : 'No',
                    ($item['fopep'] ?? false) ? 'Sí' : 'No',
                    $item['edad'] ?? '',
                    $item['valor_desembolso'] ?? 0,
                    $item['saldo_capital_original'] ?? 0,
                    $item['intereses_corrientes'] ?? 0,
                    $item['intereses_de_mora'] ?? 0,
                    $item['seguros'] ?? 0,
                    $item['otros_conceptos'] ?? 0,
                    $item['total_obligacion'] ?? 0,
                    $item['costo_compra_portafolio'] ?? 0,
                    $item['costo_comision_comercial'] ?? 0,
                    $item['costo_reincorporacion_gaf'] ?? 0,
                    $item['costo_coadministracion'] ?? 0,
                    $item['costo_seguro_vd'] ?? 0,
                    $item['costos_fiduciarios'] ?? 0,
                    $item['reporte_centrales'] ?? 0,
                    $item['tecnologia'] ?? 0,
                    $item['sub_total_costo_compra_adm'] ?? 0,
                    $item['cuota_incorporada_previamente'] ?? 0,
                    $item['cupo_sem'] ?? 0,
                    $item['cupo_colpensiones'] ?? 0,
                    $item['cupo_fopep'] ?? 0,
                    $item['cupo_fiduprevisora'] ?? 0,
                    $item['total_cupo_disponible'] ?? 0,
                    $item['tasa_pactada'] ?? '',
                    $item['respetar_tasa_pactada'] ?? '',
                    $item['tasa_nueva_libranza_ck'] ?? '',
                    $item['plazo_pactado'] ?? '',
                    $item['respetar_plazo_pactado'] ?? '',
                    $item['plazo_nueva_libranza_ck'] ?? '',
                    $item['cuota_pactada'] ?? 0,
                    $item['respetar_cuota_pactada'] ?? '',
                    $item['cuota_a_incorporar'] ?? 0,
                    $item['tasa_modificada_conservando_plazo_180'] ?? '',
                    $item['plazo_modificado_conservando_tasa_188'] ?? '',
                ];

                $sheet->fromArray([$rowData], null, 'A' . $row);
                $row++;
            }

            // Configurar anchos de columnas
            $columnWidths = [
                'A' => 12,  'B' => 12,  'C' => 30,  'D' => 25,  'E' => 12,
                'F' => 12,  'G' => 10,  'H' => 8,   'I' => 15,  'J' => 20,
                'K' => 18,  'L' => 18,  'M' => 12,  'N' => 15,  'O' => 18,
                'P' => 22,  'Q' => 25,  'R' => 28,  'S' => 22,  'T' => 20,
                'U' => 18,  'V' => 18,  'W' => 12,  'X' => 30,  'Y' => 28,
                'Z' => 15,  'AA' => 18, 'AB' => 15, 'AC' => 18, 'AD' => 22,
                'AE' => 15, 'AF' => 22, 'AG' => 22, 'AH' => 15, 'AI' => 22,
                'AJ' => 22, 'AK' => 15, 'AL' => 22, 'AM' => 20, 'AN' => 38,
                'AO' => 38
            ];

            foreach ($columnWidths as $col => $width) {
                $sheet->getColumnDimension($col)->setWidth($width);
            }

            // Nombre del archivo
            $fecha = date('Y-m-d');
            $filename = 'analisis_cartera_avanzado_' . $fecha . '.xlsx';

            Log::info('[exportarExcelConFormato] Exportación completada exitosamente');

            // Preparar descarga
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

            return response()->streamDownload(function() use ($writer) {
                $writer->save('php://output');
            }, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Cache-Control' => 'max-age=0',
            ]);

        } catch (\Exception $e) {
            Log::error('[exportarExcelConFormato] Error al exportar: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Error al exportar: ' . $e->getMessage()
            ], 500);
        }
    }
}
