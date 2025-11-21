<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Colpensiones;
use App\Fiducidiaria;
use App\DatamesFopep;
use App\Parametro;
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

            // Validar que la tasa de usura esté actualizada (máximo 30 días)
            if (Parametro::tasaUsuraNecesitaActualizacion()) {
                Log::warning('[upload] Tasa de usura no actualizada en los últimos 30 días. Bloqueando carga de archivo.');
                return response()->json([
                    'error' => 'La Tasa de Usura no se ha actualizado en los últimos 30 días. Por favor, actualícela desde la sección "Políticas de Portafolio y Fondos" (pestaña General) antes de cargar el archivo.'
                ], 403);
            }

            // Obtener el parámetro TASA_USURA para log y uso posterior
            $parametroTasaUsura = Parametro::obtenerTasaUsura();
            Log::info('[upload] Tasa de usura verificada: ' . $parametroTasaUsura->valor . ' (actualizada el ' . $parametroTasaUsura->updated_at . ')');

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

            // OPTIMIZACIÓN: Guardar las políticas completas y parámetros en caché (no solo IDs) para evitar consultas SQL en cada página
            $politicasCacheKey = 'politicas_completas_' . Auth::id();
            $politicasData = [
                'portafolio' => $politicaPortafolio,
                'fondo' => $politicaFondo,
                'tasa_usura' => Parametro::obtenerValorTasaUsura() // Guardar el valor directamente para uso rápido
            ];
            Cache::put($politicasCacheKey, $politicasData, 3600); // 1 hora
            Log::info('[upload] Políticas completas y tasa usura guardados en caché para evitar consultas repetidas');

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
                'respetar plazo pactado',
                'cuota pactada',
                'respetar cuota pactada',
                'cupo colpensiones',
                'cupo fopep',
                'cupo fiduprevisora',
                'cuotas pagas'
            ];

            // Columnas que contienen valores monetarios y numéricos que requieren limpieza
            $moneyColumns = [
                'valor desembolso',
                'saldo capital original',
                'intereses corrientes',
                'intereses de mora',
                'seguros',
                'otros conceptos',
                'tasa pactada',           // Agregar para limpiar formato de porcentaje
                'plazo pactado',          // Agregar para limpiar formato numérico
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
                    $results[$i]['respetar_plazo_pactado'] = $excelRow['respetar plazo pactado'] ?? null;
                    $results[$i]['cuota_pactada'] = $excelRow['cuota pactada'] ?? null;
                    $results[$i]['respetar_cuota_pactada'] = $excelRow['respetar cuota pactada'] ?? null;
                    $results[$i]['cuotas_pagas'] = $excelRow['cuotas pagas'] ?? null;

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
                        // Usar tasa_usura desde parametros tabla (llave=TASA_USURA)
                        $tasa_usura = Parametro::obtenerValorTasaUsura();
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
                        'tasa_nueva_libranza_ck' => $tasa_nueva_libranza_ck,
                        'plazo_nueva_libranza_ck' => $plazo_nueva_libranza_ck,
                        'cuota_a_incorporar' => $cuota_a_incorporar,
                        'total_obligacion' => $results[$i]['total_obligacion'],
                        'total_cupo_disponible' => $total_cupo_disponible,
                        'cuotas_pagas' => $results[$i]['cuotas_pagas'] ?? 0
                    ], $politicaFondo);

                    $results[$i]['tasa_nueva_libranza_ck'] = $tasa_nueva_libranza_ck;
                    $results[$i]['plazo_nueva_libranza_ck'] = $plazo_nueva_libranza_ck;
                    $results[$i]['cuota_a_incorporar'] = $cuota_a_incorporar;
                    $results[$i]['tasa_modificada_conservando_plazo_180'] = $valoresFinancieros['tasa_modificada_conservando_plazo_180'];
                    $results[$i]['plazo_modificado_conservando_tasa_188'] = $valoresFinancieros['plazo_modificado_conservando_tasa_188'];
                    $results[$i]['cuotas_faltantes_condiciones_optimas'] = $valoresFinancieros['cuotas_faltantes_condiciones_optimas'];
                    $results[$i]['tasa_corriente_condiciones_optimas'] = $valoresFinancieros['tasa_corriente_condiciones_optimas'];
                    $results[$i]['saldo_contable_credito_momento_venta'] = $valoresFinancieros['saldo_contable_credito_momento_venta'];
                    $results[$i]['precio_venta_pv_periodo_venta'] = $valoresFinancieros['precio_venta_pv_periodo_venta'];
                    $results[$i]['condiciones_iniciales_libranza_venta_t0'] = $valoresFinancieros['condiciones_iniciales_libranza_venta_t0'];
                    $results[$i]['saldo_contable_t0_menos_precio_venta'] = $valoresFinancieros['saldo_contable_t0_menos_precio_venta'];
                    $results[$i]['aplica_descuento'] = $valoresFinancieros['aplica_descuento'];
                    $results[$i]['porcentaje_descuento_sobre_total_saldo'] = $valoresFinancieros['porcentaje_descuento_sobre_total_saldo'];

                    // Asignar las 6 nuevas columnas de descuento en intereses
                    $results[$i]['saldo_venta_aplicando_cuotas_tv'] = $valoresFinancieros['saldo_venta_aplicando_cuotas_tv'];
                    $results[$i]['descuento_de_interes'] = $valoresFinancieros['descuento_de_interes'];
                    $results[$i]['saldo_inicial_desembolsado_menos_interes_condonados'] = $valoresFinancieros['saldo_inicial_desembolsado_menos_interes_condonados'];
                    $results[$i]['saldo_inicial_desembolsado_menos_interes_condonados_tv'] = $valoresFinancieros['saldo_inicial_desembolsado_menos_interes_condonados_tv'];
                    $results[$i]['plazo_con_descuento_en_interes_t0'] = $valoresFinancieros['plazo_con_descuento_en_interes_t0'];
                    $results[$i]['tasa_con_descuento_en_interes_t0'] = $valoresFinancieros['tasa_con_descuento_en_interes_t0'];
                    $results[$i]['efectividad_condonacion_sobre_intereses'] = $valoresFinancieros['efectividad_condonacion_sobre_intereses'];

                    // Tasa De Compra Nmv = T.A MIN (EM) de la política de fondo (ya viene como porcentaje)
                    $results[$i]['tasa_compra_nmv'] = $politicaFondo->ta_min_em ?? 0;

                    // Costo Asegurabilidad Mes de la política de fondo (ya viene como porcentaje)
                    $results[$i]['costo_asegurabilidad_mes'] = $politicaFondo->costo_asegurabilidad_mes ?? 0;

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
     * Calcula el valor futuro de una inversión (equivalente a la función VF/FV de Excel)
     *
     * @param float $rate Tasa de interés por período
     * @param int $nper Número de períodos
     * @param float $pmt Pago por período
     * @param float $pv Valor presente (negativo para préstamos)
     * @param int $type Tipo de pago: 0 = fin del período, 1 = inicio (opcional)
     * @return float Valor futuro
     */
    private function calculateFV($rate, $nper, $pmt, $pv, $type = 0)
    {
        // Si la tasa es 0, usar fórmula simplificada
        if ($rate == 0) {
            return -($pv + ($pmt * $nper));
        }

        // Fórmula VF: -PV * (1 + rate)^nper - PMT * (((1 + rate)^nper - 1) / rate) * (1 + rate * type)
        $fv = -$pv * pow(1 + $rate, $nper) - $pmt * ((pow(1 + $rate, $nper) - 1) / $rate) * (1 + $rate * $type);

        return $fv;
    }

    /**
     * Calcula el valor presente/actual de una inversión (equivalente a la función VA/PV de Excel)
     *
     * @param float $rate Tasa de interés por período
     * @param int $nper Número de períodos
     * @param float $pmt Pago por período
     * @param float $fv Valor futuro (opcional, por defecto 0)
     * @param int $type Tipo de pago: 0 = fin del período, 1 = inicio (opcional)
     * @return float Valor presente
     */
    private function calculatePV($rate, $nper, $pmt, $fv = 0, $type = 0)
    {
        // Si la tasa es 0, usar fórmula simplificada
        if ($rate == 0) {
            return -($pmt * $nper) - $fv;
        }

        // Fórmula VA: -PMT * [(1 - (1 + rate)^-nper) / rate] * (1 + rate * type) - FV / (1 + rate)^nper
        $pv = -$pmt * ((1 - pow(1 + $rate, -$nper)) / $rate) * (1 + $rate * $type) - ($fv / pow(1 + $rate, $nper));

        return $pv;
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
                    'tasa_usura' => Parametro::obtenerValorTasaUsura(), // Usar tasa_usura desde parametros tabla (TASA_USURA=valor)
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
            'tasa_modificada_conservando_plazo_180' => 100, // 100% por defecto en caso de error (SI.ERROR del Excel)
            'plazo_modificado_conservando_tasa_188' => 2468, // 2468 por defecto en caso de error (SI.ERROR del Excel)
            'cuotas_faltantes_condiciones_optimas' => 'N/A', // N/A por defecto
            'tasa_corriente_condiciones_optimas' => 'N/A', // N/A por defecto
            'saldo_contable_credito_momento_venta' => 0, // 0 por defecto
            'precio_venta_pv_periodo_venta' => 0, // 0 por defecto
            'condiciones_iniciales_libranza_venta_t0' => 0, // 0 por defecto
            'saldo_contable_t0_menos_precio_venta' => 0, // 0 por defecto
            'aplica_descuento' => 'NO', // NO por defecto
            'porcentaje_descuento_sobre_total_saldo' => 0, // 0 por defecto
            'saldo_venta_aplicando_cuotas_tv' => '-', // Guion por defecto (solo si APLICA DESCUENTO="SI")
            'descuento_de_interes' => '-', // Guion por defecto (solo si APLICA DESCUENTO="SI")
            'saldo_inicial_desembolsado_menos_interes_condonados' => '-', // Guion por defecto (solo si APLICA DESCUENTO="SI")
            'saldo_inicial_desembolsado_menos_interes_condonados_tv' => '-', // Guion por defecto (solo si saldo inicial <> 0 y <> "")
            'plazo_con_descuento_en_interes_t0' => '-', // Guion por defecto (solo si APLICA DESCUENTO="SI"), "INFINITO" si hay error
            'tasa_con_descuento_en_interes_t0' => '-', // Guion por defecto (solo si APLICA DESCUENTO="SI"), "IRRECUPERABLE" si hay error
            'efectividad_condonacion_sobre_intereses' => '-' // Guion por defecto
        ];

        // Validación temprana: si no hay política o valores críticos son 0, retornar valores por defecto
        if (!$politicaFondo) {
            return $resultado;
        }

        $tasa_nueva_libranza_ck = $data['tasa_nueva_libranza_ck'] ?? 0;
        $plazo_nueva_libranza_ck = $data['plazo_nueva_libranza_ck'] ?? 0;
        $cuota_a_incorporar = $data['cuota_a_incorporar'] ?? 0;
        $total_obligacion = $data['total_obligacion'] ?? 0;
        $total_cupo_disponible = $data['total_cupo_disponible'] ?? 0;
        $cuotas_pagas = $data['cuotas_pagas'] ?? 0;
        $costo_asegurabilidad_mes = $politicaFondo->costo_asegurabilidad_mes ?? 0;

        // Cálculo 1: Tasa Modificada Conservando Plazo
        // Fórmula: SI.ERROR(TASA(Plazo Nueva Libranza Ck, CUOTA A INCORPORAR, (-Total Obligación)) - COSTO DE ASEGURABILIDAD MES, 100%)
        // Solo calcular si los valores son válidos
        if ($plazo_nueva_libranza_ck > 0 && $cuota_a_incorporar > 0 && $total_obligacion > 0) {
            try {
                $tasa_calculada = $this->calculateRate(
                    $plazo_nueva_libranza_ck,
                    $cuota_a_incorporar,
                    -$total_obligacion,
                    0,
                    0
                );

                $resultado['tasa_modificada_conservando_plazo_180'] =
                    ($tasa_calculada - ($costo_asegurabilidad_mes / 100)) * 100;
            } catch (\Exception $e) {
                // Si hay error en el cálculo, mantener 100% (comportamiento SI.ERROR del Excel)
                Log::warning('[calcularValoresFinancierosOptimizado] Error calculando tasa: ' . $e->getMessage());
                $resultado['tasa_modificada_conservando_plazo_180'] = 100;
            }
        }

        // Cálculo 2: Plazo Modificado Conservando Tasa
        // Fórmula: SI.ERROR(REDONDEAR(NPER(Tasa Nueva Libranza Ck + COSTO DE ASEGURABILIDAD MES, CUOTA A INCORPORAR, (-Total Obligación)), 0), 2468)
        // Solo calcular si los valores son válidos
        if ($cuota_a_incorporar > 0 && $total_obligacion > 0 && $tasa_nueva_libranza_ck > 0) {
            try {
                // Convertir tasa de porcentaje a decimal y sumar costo de asegurabilidad
                $tasa_decimal = ($tasa_nueva_libranza_ck / 100) + ($costo_asegurabilidad_mes / 100);

                $plazo_calculado = $this->calculateNper(
                    $tasa_decimal,
                    $cuota_a_incorporar,
                    -$total_obligacion, // Usar Total Obligación (negativo)
                    0,
                    0
                );

                $plazo_redondeado = round($plazo_calculado, 0);

                // Validar rango razonable
                if ($plazo_redondeado > 0 && $plazo_redondeado <= 500) {
                    $resultado['plazo_modificado_conservando_tasa_188'] = $plazo_redondeado;
                }
            } catch (\Exception $e) {
                // Si hay error en el cálculo, mantener 2468 (comportamiento SI.ERROR del Excel)
                Log::warning('[calcularValoresFinancierosOptimizado] Error calculando plazo: ' . $e->getMessage());
                $resultado['plazo_modificado_conservando_tasa_188'] = 2468;
            }
        }

        // Cálculo 3: Cuotas Faltantes Condiciones Optimas (Tv)
        // Fórmula: SI(Y(Plazo Modificado Conservando Tasa>(PLAZO MAX-1),O(Tasa Modificada Conservando Plazo>Tasa Nueva Libranza Ck,Tasa Modificada Conservando Plazo<T.A MIN (EM))),
        //             "CAPACIDAD INSUFICIENTE",
        //             (SI(Y(Plazo Modificado Conservando Tasa>(PLAZO MAX-1),Tasa Modificada Conservando Plazo<Tasa Nueva Libranza Ck),Plazo Nueva Libranza Ck,
        //                SI(Y(Plazo Modificado Conservando Tasa<PLAZO MAX,Tasa Modificada Conservando Plazo>Tasa Nueva Libranza Ck),Plazo Modificado Conservando Tasa)))-Cuotas Pagas)

        $plazo_max = $politicaFondo->plazo_max ?? 0;
        $ta_min_em = $politicaFondo->ta_min_em ?? 0; // Ya viene como porcentaje desde la BD (no multiplicar por 100)

        $tasa_modificada = $resultado['tasa_modificada_conservando_plazo_180'];
        $plazo_modificado = $resultado['plazo_modificado_conservando_tasa_188'];

        // ========== LOG DE DEBUG PARA CUOTAS FALTANTES ==========
        Log::info('========== CUOTAS FALTANTES CONDICIONES OPTIMAS - DEBUG ==========');
        Log::info('Variables de entrada:');
        Log::info('  - Plazo Modificado Conservando Tasa (plazo_modificado): ' . $plazo_modificado);
        Log::info('  - Tasa Modificada Conservando Plazo (tasa_modificada): ' . $tasa_modificada . '%');
        Log::info('  - Plazo Nueva Libranza Ck (plazo_nueva_libranza_ck): ' . $plazo_nueva_libranza_ck);
        Log::info('  - Tasa Nueva Libranza Ck (tasa_nueva_libranza_ck): ' . $tasa_nueva_libranza_ck . '%');
        Log::info('  - Cuotas Pagas (cuotas_pagas): ' . $cuotas_pagas);
        Log::info('Variables de Política de Fondo:');
        Log::info('  - PLAZO MAX (días) (plazo_max): ' . $plazo_max);
        Log::info('  - T.A MIN (EM) % (ta_min_em): ' . $ta_min_em . '%');
        Log::info('Evaluación de condiciones:');
        Log::info('  - ¿Plazo Modificado > (PLAZO MAX - 1)? ' . ($plazo_modificado > ($plazo_max - 1) ? 'SÍ' : 'NO') . ' (' . $plazo_modificado . ' > ' . ($plazo_max - 1) . ')');
        Log::info('  - ¿Tasa Modificada > Tasa Nueva Libranza? ' . ($tasa_modificada > $tasa_nueva_libranza_ck ? 'SÍ' : 'NO') . ' (' . $tasa_modificada . ' > ' . $tasa_nueva_libranza_ck . ')');
        Log::info('  - ¿Tasa Modificada < T.A MIN (EM)? ' . ($tasa_modificada < $ta_min_em ? 'SÍ' : 'NO') . ' (' . $tasa_modificada . ' < ' . $ta_min_em . ')');
        Log::info('==================================================================');

        // Condición 1: ¿Es CAPACIDAD INSUFICIENTE?
        // Y(Plazo Modificado Conservando Tasa > (PLAZO MAX - 1), O(Tasa Modificada Conservando Plazo > Tasa Nueva Libranza Ck, Tasa Modificada Conservando Plazo < T.A MIN (EM)))
        if ($plazo_modificado > ($plazo_max - 1) &&
            ($tasa_modificada > $tasa_nueva_libranza_ck || $tasa_modificada < $ta_min_em)) {
            $resultado['cuotas_faltantes_condiciones_optimas'] = 'CAPACIDAD INSUFICIENTE';
            Log::info('RESULTADO: CAPACIDAD INSUFICIENTE (Condición 1 cumplida)');
            Log::info('==================================================================');
        } else {
            // Determinar el plazo a usar
            $plazo_seleccionado = null;

            // SI(Y(Plazo Modificado Conservando Tasa > (PLAZO MAX - 1), Tasa Modificada Conservando Plazo < Tasa Nueva Libranza Ck), Plazo Nueva Libranza Ck, ...)
            if ($plazo_modificado > ($plazo_max - 1) && $tasa_modificada < $tasa_nueva_libranza_ck) {
                $plazo_seleccionado = $plazo_nueva_libranza_ck;
                Log::info('Condición 2 cumplida: Plazo seleccionado = Plazo Nueva Libranza Ck = ' . $plazo_seleccionado);
            }
            // SI(Y(Plazo Modificado Conservando Tasa < PLAZO MAX, Tasa Modificada Conservando Plazo > Tasa Nueva Libranza Ck), Plazo Modificado Conservando Tasa)
            elseif ($plazo_modificado < $plazo_max && $tasa_modificada > $tasa_nueva_libranza_ck) {
                $plazo_seleccionado = $plazo_modificado;
                Log::info('Condición 3 cumplida: Plazo seleccionado = Plazo Modificado = ' . $plazo_seleccionado);
            } else {
                Log::info('Ninguna condición cumplida. Plazo seleccionado = null');
            }

            // Calcular cuotas faltantes: Plazo seleccionado - Cuotas Pagas
            if ($plazo_seleccionado !== null) {
                $resultado['cuotas_faltantes_condiciones_optimas'] = $plazo_seleccionado - $cuotas_pagas;
                Log::info('RESULTADO: Cuotas Faltantes = ' . $plazo_seleccionado . ' - ' . $cuotas_pagas . ' = ' . $resultado['cuotas_faltantes_condiciones_optimas']);
            } else {
                $resultado['cuotas_faltantes_condiciones_optimas'] = 'N/A';
                Log::info('RESULTADO: N/A (plazo_seleccionado es null)');
            }
            Log::info('==================================================================');
        }

        // Cálculo 4: Tasa Corriente Condiciones Optimas
        // Fórmula: SI(Y(Plazo Modificado>(PLAZO MAX-1),O(Tasa Modificada>Tasa Nueva Libranza,Tasa Modificada<T.A MIN (EM))),
        //             "CAPACIDAD INSUFICIENTE",
        //             SI(Y(Plazo Modificado>(PLAZO MAX-1),Tasa Modificada<Tasa Nueva Libranza),Tasa Modificada,
        //                SI(Y(Plazo Modificado<PLAZO MAX,Tasa Modificada>Tasa Nueva Libranza),Tasa Nueva Libranza)))

        // Condición 1: ¿Es CAPACIDAD INSUFICIENTE?
        // Y(Plazo Modificado > (PLAZO MAX - 1), O(Tasa Modificada > Tasa Nueva Libranza, Tasa Modificada < T.A MIN (EM)))
        if ($plazo_modificado > ($plazo_max - 1) &&
            ($tasa_modificada > $tasa_nueva_libranza_ck || $tasa_modificada < $ta_min_em)) {
            $resultado['tasa_corriente_condiciones_optimas'] = 'CAPACIDAD INSUFICIENTE';
        }
        // Condición 2: SI(Y(Plazo Modificado > (PLAZO MAX - 1), Tasa Modificada < Tasa Nueva Libranza), Tasa Modificada, ...)
        elseif ($plazo_modificado > ($plazo_max - 1) && $tasa_modificada < $tasa_nueva_libranza_ck) {
            $resultado['tasa_corriente_condiciones_optimas'] = $tasa_modificada;
        }
        // Condición 3: SI(Y(Plazo Modificado < PLAZO MAX, Tasa Modificada > Tasa Nueva Libranza), Tasa Nueva Libranza)
        elseif ($plazo_modificado < $plazo_max && $tasa_modificada > $tasa_nueva_libranza_ck) {
            $resultado['tasa_corriente_condiciones_optimas'] = $tasa_nueva_libranza_ck;
        }
        // Si no se cumple ninguna condición
        else {
            $resultado['tasa_corriente_condiciones_optimas'] = 'N/A';
        }

        // Cálculo 5: Saldo Contable Del Crédito Al Momento De La Venta
        // Fórmula: SI.ERROR(VF(Tasa Corriente CONDICIONES OPTIMAS + COSTO DE ASEGURABILIDAD MES, Cuotas Pagas, CUOTA A INCORPORAR, (-Total Obligación)),
        //                   VF(Tasa Nueva Libranza Ck, Cuotas Pagas, CUOTA A INCORPORAR, (-Total Obligación)))

        $tasa_corriente = $resultado['tasa_corriente_condiciones_optimas'];
        $saldo_calculado = null;

        // Método 1: Intentar calcular con Tasa Corriente Condiciones Optimas (si es numérica)
        if (is_numeric($tasa_corriente) && $cuotas_pagas > 0 && $cuota_a_incorporar > 0 && $total_obligacion > 0) {
            try {
                // Convertir tasa de porcentaje a decimal y sumar costo de asegurabilidad
                $tasa_total = ($tasa_corriente / 100) + ($costo_asegurabilidad_mes / 100);

                $saldo_calculado = $this->calculateFV(
                    $tasa_total,
                    $cuotas_pagas,
                    $cuota_a_incorporar,
                    -$total_obligacion,
                    0
                );

                Log::info('[calcularValoresFinancierosOptimizado] Saldo calculado con Tasa Corriente: ' . $saldo_calculado);
            } catch (\Exception $e) {
                Log::warning('[calcularValoresFinancierosOptimizado] Error calculando saldo contable (método 1): ' . $e->getMessage());
                $saldo_calculado = null; // Forzar uso del fallback
            }
        }

        // Método 2 (Fallback): Si tasa_corriente NO es numérica (ej: "CAPACIDAD INSUFICIENTE") o hubo error
        // IMPORTANTE: El fallback usa "Tasa Nueva Libranza Ck" (tal como está en Excel)
        if ($saldo_calculado === null && $cuotas_pagas > 0 && $cuota_a_incorporar > 0 && $total_obligacion > 0 && $tasa_nueva_libranza_ck > 0) {
            try {
                // Usar Tasa Nueva Libranza Ck directamente (dividir entre 100 para convertir a decimal)
                // Ejemplo: Si Tasa Nueva Libranza Ck = 1.88%, se usa 1.88/100 = 0.0188 como tasa
                // NO se suma el costo de asegurabilidad en el fallback (solo en el método principal)
                $tasa_fallback = $tasa_nueva_libranza_ck / 100;

                $saldo_calculado = $this->calculateFV(
                    $tasa_fallback,
                    $cuotas_pagas,
                    $cuota_a_incorporar,
                    -$total_obligacion,
                    0
                );

                Log::info('[calcularValoresFinancierosOptimizado] Saldo calculado con Tasa Nueva Libranza (fallback): ' . $saldo_calculado);
            } catch (\Exception $e2) {
                Log::warning('[calcularValoresFinancierosOptimizado] Error calculando saldo contable (método 2): ' . $e2->getMessage());
                $saldo_calculado = 0;
            }
        }

        // Asignar resultado final
        $resultado['saldo_contable_credito_momento_venta'] = $saldo_calculado !== null ? $saldo_calculado : 0;

        // Cálculo 6: Precio De Venta (Pv) En Periodo Venta (Tv)
        // Fórmula: SI(Cuotas Faltantes Condiciones Optimas (Tv)<1, 0,
        //             SI.ERROR(VA(Tasa de Compra NMV + COSTO DE ASEGURABILIDAD MES, Cuotas Faltantes CONDICIONES OPTIMAS (Tv), -(CUOTA A INCORPORAR)),
        //                      VA(Tasa de Compra NMV + COSTO DE ASEGURABILIDAD MES, Plazo Nueva Libranza Ck, -(CUOTA A INCORPORAR))))

        $cuotas_faltantes = $resultado['cuotas_faltantes_condiciones_optimas'];
        $tasa_compra_nmv = $politicaFondo->ta_min_em ?? 0; // Ya viene como porcentaje desde la BD
        $precio_venta = 0;

        // Validación inicial: Si Cuotas Faltantes < 1, retornar 0
        if (is_numeric($cuotas_faltantes) && $cuotas_faltantes < 1) {
            $precio_venta = 0;
            Log::info('[calcularValoresFinancierosOptimizado] Cuotas Faltantes < 1, precio_venta = 0');
        }
        // Caso 2: Si cuotas faltantes es válido (numérico y >= 1) y cuota_a_incorporar > 0
        elseif (is_numeric($cuotas_faltantes) && $cuotas_faltantes >= 1 && $cuota_a_incorporar > 0) {
            try {
                // Método 1: Calcular con Cuotas Faltantes Condiciones Optimas
                // Convertir porcentajes a decimales y sumar
                $tasa_total_pv = ($tasa_compra_nmv / 100) + ($costo_asegurabilidad_mes / 100);

                $precio_venta = $this->calculatePV(
                    $tasa_total_pv,
                    $cuotas_faltantes,
                    -$cuota_a_incorporar, // Pago negativo (CUOTA A INCORPORAR, no Total Obligación)
                    0, // FV = 0 por defecto
                    0  // Tipo = 0 (fin de período)
                );

                Log::info('[calcularValoresFinancierosOptimizado] Precio Venta calculado con Cuotas Faltantes: ' . $precio_venta);
            } catch (\Exception $e) {
                Log::warning('[calcularValoresFinancierosOptimizado] Error calculando precio venta (método 1): ' . $e->getMessage());
                $precio_venta = null; // Forzar uso del fallback
            }
        }

        // Método 2 (Fallback): Si cuotas_faltantes NO es numérico o hubo error, usar Plazo Nueva Libranza Ck
        if ($precio_venta === null && $plazo_nueva_libranza_ck > 0 && $cuota_a_incorporar > 0) {
            try {
                // Convertir porcentajes a decimales y sumar
                $tasa_total_pv = ($tasa_compra_nmv / 100) + ($costo_asegurabilidad_mes / 100);

                $precio_venta = $this->calculatePV(
                    $tasa_total_pv,
                    $plazo_nueva_libranza_ck,
                    -$cuota_a_incorporar, // Pago negativo (CUOTA A INCORPORAR, no Total Obligación)
                    0, // FV = 0 por defecto
                    0  // Tipo = 0 (fin de período)
                );

                Log::info('[calcularValoresFinancierosOptimizado] Precio Venta calculado con Plazo Nueva Libranza (fallback): ' . $precio_venta);
            } catch (\Exception $e2) {
                Log::warning('[calcularValoresFinancierosOptimizado] Error calculando precio venta (método 2): ' . $e2->getMessage());
                $precio_venta = 0;
            }
        }

        // Asignar resultado final
        $resultado['precio_venta_pv_periodo_venta'] = $precio_venta !== null ? $precio_venta : 0;

        // Cálculo 7: Condiciones Iniciales Libranza De Venta (T0)
        // Fórmula: VA(Tasa de Compra NMV + COSTO DE ASEGURABILIDAD MES, Cuotas Pagas, -(CUOTA A INCORPORAR), -(Precio de Venta (PV) en periodo VENTA (TV)))

        $condiciones_iniciales = 0;

        if ($cuotas_pagas > 0 && $cuota_a_incorporar > 0) {
            try {
                // Convertir porcentajes a decimales y sumar
                $tasa_total_ci = ($tasa_compra_nmv / 100) + ($costo_asegurabilidad_mes / 100);
                $precio_venta_final = $resultado['precio_venta_pv_periodo_venta'];

                $condiciones_iniciales = $this->calculatePV(
                    $tasa_total_ci,                   // tasa: Tasa de Compra NMV + COSTO DE ASEGURABILIDAD MES
                    $cuotas_pagas,                     // nper: Cuotas Pagas
                    -$cuota_a_incorporar,              // pago: -(CUOTA A INCORPORAR) - no Total Obligación
                    -$precio_venta_final,              // vf: -(Precio de Venta)
                    0                                   // tipo: 0 (fin del período)
                );

                Log::info('[calcularValoresFinancierosOptimizado] Condiciones Iniciales Libranza calculadas: ' . $condiciones_iniciales);
            } catch (\Exception $e) {
                Log::warning('[calcularValoresFinancierosOptimizado] Error calculando condiciones iniciales: ' . $e->getMessage());
                $condiciones_iniciales = 0;
            }
        }

        // Asignar resultado final
        $resultado['condiciones_iniciales_libranza_venta_t0'] = $condiciones_iniciales;

        // Cálculo 8: Saldo Contable (T0) - Precio De Venta
        // Fórmula: =+Precio de Venta (PV) en periodo VENTA (TV) - Saldo Contable Del Crédito Al Momento De La Venta
        // Nota: El nombre del campo no coincide con la operación matemática, pero seguimos la fórmula de Excel
        $resultado['saldo_contable_t0_menos_precio_venta'] =
            $resultado['precio_venta_pv_periodo_venta'] - $resultado['saldo_contable_credito_momento_venta'];

        // Cálculo 9: Aplica Descuento
        // Fórmula: SI(Y((SALDO CONTABLE (T0) - PRECIO DE VENTA)<0, Saldo CONTABLE del Crédito al momento de la venta>0, Plazo Modificado Conservando Tasa >Plazo Nueva Libranza Ck),"SI","NO")
        // Nota: "(SALDO CONTABLE (T0) - PRECIO DE VENTA)" es el nombre del campo, no una operación
        $condicion1 = $resultado['saldo_contable_t0_menos_precio_venta'] < 0;
        $condicion2 = $resultado['saldo_contable_credito_momento_venta'] > 0;
        $condicion3 = $resultado['plazo_modificado_conservando_tasa_188'] > $plazo_nueva_libranza_ck;

        $resultado['aplica_descuento'] = ($condicion1 && $condicion2 && $condicion3) ? 'SI' : 'NO';

        // Cálculo 10: % De Descuento Sobre Total Saldo
        // Fórmula: =(-((SALDO CONTABLE (T0) - PRECIO DE VENTA))/Saldo CONTABLE del Crédito al momento de la venta
        // Nota: "(SALDO CONTABLE (T0) - PRECIO DE VENTA)" es el nombre del campo
        if ($resultado['saldo_contable_credito_momento_venta'] != 0) {
            $resultado['porcentaje_descuento_sobre_total_saldo'] =
                -($resultado['saldo_contable_t0_menos_precio_venta']) / $resultado['saldo_contable_credito_momento_venta'];
        } else {
            $resultado['porcentaje_descuento_sobre_total_saldo'] = 0;
        }

        // Cálculo 11: Saldo Venta Aplicando Cuotas (Tv)
        // Fórmula: =SI(APLICA DESCUENTO="SI",VF(Tasa de Compra NMV+COSTO DE ASEGURABILIDAD MES,Cuotas Pagas,CUOTA A INCORPORAR,-(Precio de Venta (PV) en periodo VENTA (TV))),"-")
        $saldo_venta_aplicando_cuotas_tv = '-';  // Valor por defecto si no aplica descuento

        Log::info('[DEBUG COLUMNAS] Verificando condiciones para columnas nuevas:', [
            'aplica_descuento' => $resultado['aplica_descuento'],
            'cuotas_pagas' => $cuotas_pagas,
            'cuota_a_incorporar' => $cuota_a_incorporar,
            'tasa_nueva_libranza_ck' => $tasa_nueva_libranza_ck,
            'plazo_nueva_libranza_ck' => $plazo_nueva_libranza_ck,
            'precio_venta_pv_periodo_venta' => $resultado['precio_venta_pv_periodo_venta'],
            'total_obligacion' => $total_obligacion
        ]);

        if ($resultado['aplica_descuento'] === 'SI' && $cuotas_pagas > 0 && $cuota_a_incorporar > 0) {
            try {
                // Convertir porcentajes a decimales y sumar
                $tasa_total_saldo_venta = ($tasa_compra_nmv / 100) + ($costo_asegurabilidad_mes / 100);
                $precio_venta_negativo = -$resultado['precio_venta_pv_periodo_venta'];

                $saldo_venta_aplicando_cuotas_tv = $this->calculateFV(
                    $tasa_total_saldo_venta,          // rate: Tasa de Compra NMV + COSTO DE ASEGURABILIDAD MES
                    $cuotas_pagas,                     // nper: Cuotas Pagas
                    $cuota_a_incorporar,               // pmt: CUOTA A INCORPORAR (positivo, no negativo)
                    $precio_venta_negativo,            // pv: -(Precio de Venta (PV) en periodo VENTA (TV))
                    0                                   // type: 0 (fin del período)
                );

                Log::info('[calcularValoresFinancierosOptimizado] Saldo Venta Aplicando Cuotas calculado: ' . $saldo_venta_aplicando_cuotas_tv);
            } catch (\Exception $e) {
                Log::warning('[calcularValoresFinancierosOptimizado] Error calculando Saldo Venta Aplicando Cuotas: ' . $e->getMessage());
                $saldo_venta_aplicando_cuotas_tv = '-';  // Retornar guion en caso de error
            }
        }

        $resultado['saldo_venta_aplicando_cuotas_tv'] = $saldo_venta_aplicando_cuotas_tv;

        // Cálculo 12: Descuento De Interes
        // Fórmula: =SI(APLICA DESCUENTO="SI",SI(Intereses Corrientes<-(Saldo Contable (T0) - Precio De Venta),Intereses Corrientes,-(Saldo Contable (T0) - Precio De Venta)),"-")
        // Nota: "Saldo Contable (T0) - Precio De Venta" es el nombre del campo saldo_contable_t0_menos_precio_venta
        $descuento_de_interes = '-';  // Valor por defecto si no aplica descuento
        $intereses_corrientes = $data['intereses_corrientes'] ?? 0;

        if ($resultado['aplica_descuento'] === 'SI') {
            // Calcular -(Saldo Contable (T0) - Precio De Venta)
            $negativo_saldo_contable_menos_precio = -($resultado['saldo_contable_t0_menos_precio_venta']);

            // SI(Intereses Corrientes < -(Saldo Contable (T0) - Precio De Venta), Intereses Corrientes, -(Saldo Contable (T0) - Precio De Venta))
            // Retorna el mínimo entre Intereses Corrientes y el negativo del saldo contable menos precio
            if ($intereses_corrientes < $negativo_saldo_contable_menos_precio) {
                $descuento_de_interes = $intereses_corrientes;
            } else {
                $descuento_de_interes = $negativo_saldo_contable_menos_precio;
            }

            Log::info('[calcularValoresFinancierosOptimizado] Descuento De Interes calculado: ' . $descuento_de_interes);
        }

        $resultado['descuento_de_interes'] = $descuento_de_interes;

        // Cálculo 13: Saldo Inicial Desembolsado Menos Interes Condonados
        // Fórmula: =SI(APLICA DESCUENTO="SI", Total Obligación - DESCUENTO DE INTERES, "-")
        $saldo_inicial_desembolsado_menos_interes_condonados = '-';  // Valor por defecto si no aplica descuento

        if ($resultado['aplica_descuento'] === 'SI' && is_numeric($descuento_de_interes)) {
            // Total Obligación - DESCUENTO DE INTERES
            $saldo_inicial_desembolsado_menos_interes_condonados = $total_obligacion - $descuento_de_interes;

            Log::info('[calcularValoresFinancierosOptimizado] Saldo Inicial Desembolsado Menos Interes Condonados calculado: ' . $saldo_inicial_desembolsado_menos_interes_condonados);
        }

        $resultado['saldo_inicial_desembolsado_menos_interes_condonados'] = $saldo_inicial_desembolsado_menos_interes_condonados;

        Log::info('[DEBUG COLUMNAS] Resultados columnas 1-3:', [
            'saldo_venta_aplicando_cuotas_tv' => $saldo_venta_aplicando_cuotas_tv,
            'descuento_de_interes' => $descuento_de_interes,
            'saldo_inicial_desembolsado_menos_interes_condonados' => $saldo_inicial_desembolsado_menos_interes_condonados
        ]);

        // Cálculo 14: Saldo Inicial Desembolsado Menos Interes Condonados (Tv)
        // Fórmula: =SI(Y(Saldo Inicial Desembolsado Menos Interes Condonados<>0, Saldo Inicial Desembolsado Menos Interes Condonados<>""),
        //             VF(Tasa De Compra Nmv + COSTO ASEGURABILIDAD MES (%), Cuotas Pagas, CUOTA A INCORPORAR, -(Saldo Inicial Desembolsado Menos Interes Condonados)),
        //             "-")
        $saldo_inicial_desembolsado_menos_interes_condonados_tv = '-';  // Valor por defecto si no cumple condiciones

        // Validar que el saldo inicial desembolsado menos intereses condonados no sea 0 ni vacío (ni guion)
        if (is_numeric($saldo_inicial_desembolsado_menos_interes_condonados) &&
            $saldo_inicial_desembolsado_menos_interes_condonados != 0 &&
            $cuotas_pagas > 0 &&
            $cuota_a_incorporar > 0) {

            try {
                // Tasa De Compra Nmv + COSTO DE ASEGURABILIDAD MES (convertir a decimales)
                $tasa_total_tv = ($tasa_compra_nmv / 100) + ($costo_asegurabilidad_mes / 100);
                $saldo_inicial_negativo = -$saldo_inicial_desembolsado_menos_interes_condonados;

                $saldo_inicial_desembolsado_menos_interes_condonados_tv = $this->calculateFV(
                    $tasa_total_tv,              // rate: Tasa De Compra Nmv + COSTO DE ASEGURABILIDAD MES
                    $cuotas_pagas,               // nper: Cuotas Pagas
                    $cuota_a_incorporar,         // pmt: CUOTA A INCORPORAR
                    $saldo_inicial_negativo,     // pv: -(Saldo Inicial Desembolsado Menos Interes Condonados)
                    0                            // type: 0 (fin del período)
                );

                Log::info('[calcularValoresFinancierosOptimizado] Saldo Inicial Desembolsado Menos Interes Condonados (Tv) calculado: ' . $saldo_inicial_desembolsado_menos_interes_condonados_tv);
            } catch (\Exception $e) {
                Log::warning('[calcularValoresFinancierosOptimizado] Error calculando Saldo Inicial Desembolsado Menos Interes Condonados (Tv): ' . $e->getMessage());
                $saldo_inicial_desembolsado_menos_interes_condonados_tv = '-';  // Retornar guion en caso de error
            }
        }

        $resultado['saldo_inicial_desembolsado_menos_interes_condonados_tv'] = $saldo_inicial_desembolsado_menos_interes_condonados_tv;

        // Cálculo 15: Plazo Con Descuento En Interes (T0)
        // Fórmula: =SI.ERROR(SI(APLICA DESCUENTO="SI", NPER(Tasa Nueva Libranza Ck, CUOTA A INCORPORAR, -(SALDO INICIAL DESEMBOLSADO menos INTERES condonados)), ""), "INFINITO")
        $plazo_con_descuento_en_interes_t0 = '';

        if ($resultado['aplica_descuento'] === 'SI') {
            // Solo calcular si el saldo inicial desembolsado menos intereses condonados existe y no es 0
            if ($saldo_inicial_desembolsado_menos_interes_condonados !== '' &&
                $saldo_inicial_desembolsado_menos_interes_condonados != 0 &&
                $cuota_a_incorporar > 0 &&
                $tasa_nueva_libranza_ck > 0) {

                try {
                    // Convertir tasa de porcentaje a decimal (NO se suma el costo de asegurabilidad aquí)
                    $tasa_decimal = $tasa_nueva_libranza_ck / 100;
                    $saldo_inicial_negativo = -$saldo_inicial_desembolsado_menos_interes_condonados;

                    $plazo_calculado = $this->calculateNper(
                        $tasa_decimal,           // rate: Tasa Nueva Libranza Ck (sin costo de asegurabilidad)
                        $cuota_a_incorporar,     // pmt: CUOTA A INCORPORAR
                        $saldo_inicial_negativo, // pv: -(SALDO INICIAL DESEMBOLSADO menos INTERES condonados)
                        0,                       // fv: 0 por defecto
                        0                        // type: 0 (fin del período)
                    );

                    // Redondear el plazo
                    $plazo_con_descuento_en_interes_t0 = round($plazo_calculado, 0);

                    Log::info('[calcularValoresFinancierosOptimizado] Plazo Con Descuento En Interes (T0) calculado: ' . $plazo_con_descuento_en_interes_t0);
                } catch (\Exception $e) {
                    // Si hay error en el cálculo, retornar "INFINITO" (comportamiento SI.ERROR del Excel)
                    Log::warning('[calcularValoresFinancierosOptimizado] Error calculando Plazo Con Descuento En Interes (T0): ' . $e->getMessage());
                    $plazo_con_descuento_en_interes_t0 = 'INFINITO';
                }
            }
        }

        $resultado['plazo_con_descuento_en_interes_t0'] = $plazo_con_descuento_en_interes_t0;

        // Cálculo 16: Tasa Con Descuento En Interes (T0)
        // Fórmula: =SI.ERROR(SI(APLICA DESCUENTO="SI", TASA(Plazo Nueva Libranza Ck, CUOTA A INCORPORAR, -(SALDO INICIAL DESEMBOLSADO menos INTERES condonados)), ""), "IRRECUPERABLE")
        $tasa_con_descuento_en_interes_t0 = '';

        if ($resultado['aplica_descuento'] === 'SI') {
            // Solo calcular si el saldo inicial desembolsado menos intereses condonados existe y no es 0
            if ($saldo_inicial_desembolsado_menos_interes_condonados !== '' &&
                $saldo_inicial_desembolsado_menos_interes_condonados != 0 &&
                $cuota_a_incorporar > 0 &&
                $plazo_nueva_libranza_ck > 0) {

                try {
                    $saldo_inicial_negativo = -$saldo_inicial_desembolsado_menos_interes_condonados;

                    $tasa_calculada = $this->calculateRate(
                        $plazo_nueva_libranza_ck,  // nper: Plazo Nueva Libranza Ck
                        $cuota_a_incorporar,        // pmt: CUOTA A INCORPORAR
                        $saldo_inicial_negativo,    // pv: -(SALDO INICIAL DESEMBOLSADO menos INTERES condonados)
                        0,                          // fv: 0 por defecto
                        0,                          // type: 0 (fin del período)
                        0.1                         // guess: 0.1 (estimación inicial)
                    );

                    // Convertir tasa de decimal a porcentaje
                    $tasa_con_descuento_en_interes_t0 = $tasa_calculada * 100;

                    Log::info('[calcularValoresFinancierosOptimizado] Tasa Con Descuento En Interes (T0) calculado: ' . $tasa_con_descuento_en_interes_t0 . '%');
                } catch (\Exception $e) {
                    // Si hay error en el cálculo, retornar "IRRECUPERABLE" (comportamiento SI.ERROR del Excel)
                    Log::warning('[calcularValoresFinancierosOptimizado] Error calculando Tasa Con Descuento En Interes (T0): ' . $e->getMessage());
                    $tasa_con_descuento_en_interes_t0 = 'IRRECUPERABLE';
                }
            }
        }

        $resultado['tasa_con_descuento_en_interes_t0'] = $tasa_con_descuento_en_interes_t0;

        // Cálculo 17: Efectividad De Condonacion Sobre Intereses
        // Fórmula: =SI(SALDO INICIAL DESEMBOLSADO menos INTERES condonados (Tv)="","-",
        //             SI(SALDO INICIAL DESEMBOLSADO menos INTERES condonados (Tv)>Saldo Inicial Desembolsado Menos Interes Condonados,
        //                "CUOTA PARCIAL",
        //                "CONDONACION SUFICIENTE"))
        $efectividad_condonacion_sobre_intereses = '-';  // Valor por defecto

        // Verificar si saldo_inicial_desembolsado_menos_interes_condonados_tv es numérico (no es "-")
        if (is_numeric($saldo_inicial_desembolsado_menos_interes_condonados_tv) &&
            is_numeric($saldo_inicial_desembolsado_menos_interes_condonados)) {

            // Comparar: si Tv > Saldo Inicial, entonces "CUOTA PARCIAL", sino "CONDONACION SUFICIENTE"
            if ($saldo_inicial_desembolsado_menos_interes_condonados_tv > $saldo_inicial_desembolsado_menos_interes_condonados) {
                $efectividad_condonacion_sobre_intereses = 'CUOTA PARCIAL';
            } else {
                $efectividad_condonacion_sobre_intereses = 'CONDONACION SUFICIENTE';
            }

            Log::info('[calcularValoresFinancierosOptimizado] Efectividad De Condonacion Sobre Intereses calculado: ' . $efectividad_condonacion_sobre_intereses);
        }

        $resultado['efectividad_condonacion_sobre_intereses'] = $efectividad_condonacion_sobre_intereses;

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

            // Definir headers (75 columnas totales - incluye campos calculados)
            $headers = [
                // INFORMACIÓN GENERAL (8)
                'Operación',
                'Cédula',
                'Nombre',
                'Secretaria',
                'Colpensiones',
                'Fiduprevisora',
                'Fopep',
                'Edad',
                // DETALLE DE CRÉDITO (7)
                'Desembolso',
                'Saldo Capital Original',
                'Intereses Corrientes',
                'Intereses De Mora',
                'Seguros',
                'Otros Concepto',
                'Total Obligación',
                // DETALLE PORTAFOLIO (9)
                'Costo Compra Portafolio',
                'Costo Comision Comercial',
                'Costo Re-Incorporación Gaf',
                'Costo Coadministración',
                'Costo Seguro V.D',
                'Costos Fiduciarios',
                'Reporte Centrales',
                'Tecnología',
                'Subtotal Costo Compra + Adm (Npl´S)',
                // DETALLE CUPO (6)
                'Cuota Incorporada Previamente',
                'Cupo Sem',
                'Cupo Colpensiones',
                'Cupo Fopep',
                'Cupo Fiduprevisora',
                'Total Cupo Disponible',
                // CUOTA A INCORPORAR (9)
                'Tasa Pactada',
                'Respetar Tasa Pactada',
                'Tasa Nueva Libranza Ck',
                'Plazo Pactado',
                'Respetar Plazo Pactado',
                'Plazo Nueva Libranza Ck',
                'Cuota Pactada',
                'Respetar Cuota Pactada',
                'Cuota A Incorporar',
                // OTROS CAMPOS (12)
                'Tasa Modificada Conservando Plazo 180',
                'Plazo Modificado Conservando Tasa 1.88%',
                'Cuotas Pagas',
                'Cuotas Faltantes Condiciones Optimas',
                'Tasa Corriente Condiciones Optimas',
                'Tasa Compra NMV',
                'Saldo Contable Credito Momento Venta',
                'Precio Venta (Pv) Periodo Venta (Tv)',
                'Condiciones Iniciales Libranza Venta (T0)',
                'Saldo Contable T0 Menos Precio Venta',
                'Aplica Descuento',
                '% Descuento Sobre Total Saldo',
                // ANÁLISIS AVANZADO - DATOS BACKEND (6)
                'Saldo Venta Aplicando Cuotas (Tv)',
                'Descuento De Interes',
                'Saldo Inicial Desembolsado Menos Interes Condonados',
                'Saldo Inicial Desembolsado Menos Interes Condonados (Tv)',
                'Plazo Con Descuento En Interes (T0)',
                'Tasa Con Descuento En Interes (T0)',
                // ANÁLISIS AVANZADO - CAMPOS CALCULADOS (24)
                'Plazo Viable Al Aplicar Descuento En Interes',
                'Tasa Viable Al Aplicar Descuento En Interes',
                'Valor Cartera Con Descuento En Interes Y Ajuste En Tasa',
                'Aplica Descuento Sobre Capital',
                'Saldo Maximo A Pagar Con Condiciones Nueva Lbz',
                'Condonacion Sobre Capital',
                '% De Descuento Sobre Capital',
                '% De Descuento Sobre Saldo Total',
                'Saldo Contable Con Condonacion',
                'Tasa Con Plazo Maximo',
                'Plazo Con Tasa Maxima',
                'Saldo Contable Momento Venta Con Condonacion (Tv)',
                'Valor Inicial Lbz Precio Venta Desc Capital (T0)',
                'Precio De Venta Desc Capital (Tv)',
                'Consolidado Precio De Venta (Tv) Desc Cap Inicial',
                'Plazo Restante Al Momento De Venta',
                'Consolidado Libranza Precio De Venta (T0)',
                'Consolidado Plazo Nueva Libranza',
                'Consolidado Libranza T0 Ajustando Cuota',
                'Consolidado Precio Venta (Tv) Condonacion Ajustado',
                'Plazo Necesario Para Liquidar Deuda',
                'Pago Aplicando Descuento Sobre Venta Directa',
                'Modelo Mas Rentable',
                'Consolidado Tasa Nueva Libranza',
                'Consolidado Saldo Nueva Libranza (T0)',
                'Consolidado Saldo Contable (Tv)',
                'Consolidado Saldo T0 Ajustando Amortizacion',
                'Consolidado Saldo Contable (Tv) Ajustando Amortizacion',
                'Excedente Despues Ultima Cuota Contable',
                'Excedente Despues Ultima Cuota En Venta'
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
            // Aplicar estilo a headers (75 columnas = A hasta BW)
            $sheet->getStyle('A1:BW1')->applyFromArray($headerStyle);

            // Escribir datos
            $row = 2;
            foreach ($datos as $item) {
                $rowData = [
                    // INFORMACIÓN GENERAL (8)
                    $item['operacion'] ?? '',
                    $item['doc'] ?? '',
                    $item['nombre_usuario'] ?? '',
                    $item['pagaduria'] ?? '',
                    ($item['colpensiones'] ?? false) ? 'Sí' : 'No',
                    ($item['fiducidiaria'] ?? false) ? 'Sí' : 'No',
                    ($item['fopep'] ?? false) ? 'Sí' : 'No',
                    $item['edad'] ?? '',
                    // DETALLE DE CRÉDITO (7)
                    $item['valor_desembolso'] ?? 0,
                    $item['saldo_capital_original'] ?? 0,
                    $item['intereses_corrientes'] ?? 0,
                    $item['intereses_de_mora'] ?? 0,
                    $item['seguros'] ?? 0,
                    $item['otros_conceptos'] ?? 0,
                    $item['total_obligacion'] ?? 0,
                    // DETALLE PORTAFOLIO (9)
                    $item['costo_compra_portafolio'] ?? 0,
                    $item['costo_comision_comercial'] ?? 0,
                    $item['costo_reincorporacion_gaf'] ?? 0,
                    $item['costo_coadministracion'] ?? 0,
                    $item['costo_seguro_vd'] ?? 0,
                    $item['costos_fiduciarios'] ?? 0,
                    $item['reporte_centrales'] ?? 0,
                    $item['tecnologia'] ?? 0,
                    $item['sub_total_costo_compra_adm'] ?? 0,
                    // DETALLE CUPO (6)
                    $item['cuota_incorporada_previamente'] ?? 0,
                    $item['cupo_sem'] ?? 0,
                    $item['cupo_colpensiones'] ?? 0,
                    $item['cupo_fopep'] ?? 0,
                    $item['cupo_fiduprevisora'] ?? 0,
                    $item['total_cupo_disponible'] ?? 0,
                    // CUOTA A INCORPORAR (9)
                    $item['tasa_pactada'] ?? '',
                    $item['respetar_tasa_pactada'] ?? '',
                    $item['tasa_nueva_libranza_ck'] ?? '',
                    $item['plazo_pactado'] ?? '',
                    $item['respetar_plazo_pactado'] ?? '',
                    $item['plazo_nueva_libranza_ck'] ?? '',
                    $item['cuota_pactada'] ?? 0,
                    $item['respetar_cuota_pactada'] ?? '',
                    $item['cuota_a_incorporar'] ?? 0,
                    // OTROS CAMPOS (12)
                    $item['tasa_modificada_conservando_plazo_180'] ?? '',
                    $item['plazo_modificado_conservando_tasa_188'] ?? '',
                    $item['cuotas_pagas'] ?? '',
                    $item['cuotas_faltantes_condiciones_optimas'] ?? '',
                    $item['tasa_corriente_condiciones_optimas'] ?? '',
                    $item['tasa_compra_nmv'] ?? '',
                    $item['saldo_contable_credito_momento_venta'] ?? '',
                    $item['precio_venta_pv_periodo_venta'] ?? '',
                    $item['condiciones_iniciales_libranza_venta_t0'] ?? '',
                    $item['saldo_contable_t0_menos_precio_venta'] ?? '',
                    $item['aplica_descuento'] ?? '',
                    $item['porcentaje_descuento_sobre_total_saldo'] ?? '',
                    // ANÁLISIS AVANZADO - DATOS BACKEND (6)
                    $item['saldo_venta_aplicando_cuotas_tv'] ?? '',
                    $item['descuento_de_interes'] ?? '',
                    $item['saldo_inicial_desembolsado_menos_interes_condonados'] ?? '',
                    $item['saldo_inicial_desembolsado_menos_interes_condonados_tv'] ?? '',
                    $item['plazo_con_descuento_en_interes_t0'] ?? '',
                    $item['tasa_con_descuento_en_interes_t0'] ?? '',
                    // ANÁLISIS AVANZADO - CAMPOS CALCULADOS (24)
                    $item['calc_plazo_viable_descuento_interes'] ?? '',
                    $item['calc_tasa_viable_descuento_interes'] ?? '',
                    $item['calc_valor_cartera_descuento_interes_ajuste_tasa'] ?? '',
                    $item['calc_aplica_descuento_sobre_capital'] ?? '',
                    $item['calc_saldo_maximo_pagar'] ?? '',
                    $item['calc_condonacion_sobre_capital'] ?? '',
                    $item['calc_porcentaje_descuento_capital'] ?? '',
                    $item['calc_porcentaje_descuento_saldo_total'] ?? '',
                    $item['calc_saldo_contable_con_condonacion'] ?? '',
                    $item['calc_tasa_con_plazo_maximo'] ?? '',
                    $item['calc_plazo_con_tasa_maxima'] ?? '',
                    $item['calc_saldo_contable_momento_venta_condonacion'] ?? '',
                    $item['calc_valor_inicial_lbz_precio_venta_desc_capital'] ?? '',
                    $item['calc_precio_venta_desc_capital_tv'] ?? '',
                    $item['calc_consolidado_precio_venta_tv'] ?? '',
                    $item['calc_plazo_restante_momento_venta'] ?? '',
                    $item['calc_consolidado_libranza_precio_venta_t0'] ?? '',
                    $item['calc_consolidado_plazo_nueva_libranza'] ?? '',
                    $item['calc_consolidado_libranza_t0_ajustando_cuota'] ?? '',
                    $item['calc_consolidado_precio_venta_tv_condonacion_ajustado'] ?? '',
                    $item['calc_plazo_necesario_liquidar_deuda'] ?? '',
                    $item['calc_pago_descuento_venta_directa'] ?? '',
                    $item['calc_modelo_mas_rentable'] ?? '',
                    $item['calc_consolidado_tasa_nueva_libranza'] ?? '',
                    $item['calc_consolidado_saldo_nueva_libranza_t0'] ?? '',
                    $item['calc_consolidado_saldo_contable_tv'] ?? '',
                    $item['calc_consolidado_saldo_t0_ajustando_amortizacion'] ?? '',
                    $item['calc_consolidado_saldo_contable_tv_ajustando_amortizacion'] ?? '',
                    $item['calc_excedente_ultima_cuota_contable'] ?? '',
                    $item['calc_excedente_ultima_cuota_en_venta'] ?? ''
                ];

                $sheet->fromArray([$rowData], null, 'A' . $row);
                $row++;
            }

            // Configurar anchos de columnas (75 columnas)
            $columnWidths = [
                // INFO GENERAL (A-H)
                'A' => 12,  'B' => 12,  'C' => 30,  'D' => 25,  'E' => 12,
                'F' => 12,  'G' => 10,  'H' => 8,
                // DETALLE CRÉDITO (I-O)
                'I' => 15,  'J' => 20, 'K' => 18,  'L' => 18,  'M' => 12,  'N' => 15,  'O' => 18,
                // DETALLE PORTAFOLIO (P-X)
                'P' => 22,  'Q' => 25,  'R' => 28,  'S' => 22,  'T' => 20,
                'U' => 18,  'V' => 18,  'W' => 12,  'X' => 30,
                // DETALLE CUPO (Y-AD)
                'Y' => 28, 'Z' => 15,  'AA' => 18, 'AB' => 15, 'AC' => 18, 'AD' => 22,
                // CUOTA A INCORPORAR (AE-AM)
                'AE' => 15, 'AF' => 22, 'AG' => 22, 'AH' => 15, 'AI' => 22,
                'AJ' => 22, 'AK' => 15, 'AL' => 22, 'AM' => 20,
                // OTROS CAMPOS (AN-AY)
                'AN' => 28, 'AO' => 30, 'AP' => 15, 'AQ' => 28, 'AR' => 28,
                'AS' => 18, 'AT' => 28, 'AU' => 28, 'AV' => 30, 'AW' => 28,
                'AX' => 18, 'AY' => 25,
                // ANÁLISIS AVANZADO BACKEND (AZ-BE)
                'AZ' => 28, 'BA' => 22, 'BB' => 38, 'BC' => 40, 'BD' => 28, 'BE' => 28,
                // CAMPOS CALCULADOS (BF-BW)
                'BF' => 32, 'BG' => 32, 'BH' => 38, 'BI' => 28, 'BJ' => 35,
                'BK' => 25, 'BL' => 25, 'BM' => 28, 'BN' => 28, 'BO' => 22,
                'BP' => 22, 'BQ' => 38, 'BR' => 38, 'BS' => 28, 'BT' => 35,
                'BU' => 28, 'BV' => 32, 'BW' => 28
            ];

            foreach ($columnWidths as $col => $width) {
                $sheet->getColumnDimension($col)->setWidth($width);
            }

            // Aplicar auto-ajuste a columnas restantes no especificadas
            for ($i = 1; $i <= 75; $i++) {
                $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
                if (!isset($columnWidths[$col])) {
                    $sheet->getColumnDimension($col)->setWidth(20);
                }
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
