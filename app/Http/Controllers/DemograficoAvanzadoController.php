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

            // Columnas que contienen valores monetarios
            $moneyColumns = [
                'valor desembolso',
                'saldo capital original',
                'intereses corrientes',
                'intereses de mora',
                'seguros',
                'otros conceptos',
                'cuota pactada'
            ];

            $dataRows = [];
            foreach ($worksheet->getRowIterator(2) as $row) {
                $rowIndex = $row->getRowIndex();
                $cedulaRaw = $worksheet->getCellByColumnAndRow($headerMap['cedulas'] + 1, $rowIndex)->getValue();
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

            $matchCount = 0;
            for ($i = 0; $i < count($results); $i++) {
                // Convertir ambos a string para comparación
                $docStr = trim((string)$results[$i]['doc']);

                foreach ($cedulasDataChunk as $excelRow) {
                    $cedulaStr = trim((string)$excelRow['cedula']);

                    // Log de comparación (solo el primero para no saturar)
                    if ($i === 0) {
                        Log::info('[fetchPaginatedResults] Comparando primera cédula', [
                            'cedula_excel' => $cedulaStr,
                            'doc_bd' => $docStr,
                            'match' => ($cedulaStr === $docStr)
                        ]);
                    }

                    // Comparar como strings
                    if ($cedulaStr === $docStr) {
                        $matchCount++;
                        Log::info('[fetchPaginatedResults] MATCH ENCONTRADO para cédula: ' . $cedulaStr, [
                            'datos_excel_keys' => array_keys($excelRow)
                        ]);

                        // Agregar campos adicionales del Excel
                        $results[$i]['operacion'] = $excelRow['operación'] ?? null;
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

                        // Calcular Total Obligación (suma de valores monetarios del crédito, excluyendo valor desembolso)
                        $total_obligacion =
                            ($results[$i]['saldo_capital_original'] ?? 0) +
                            ($results[$i]['intereses_corrientes'] ?? 0) +
                            ($results[$i]['intereses_de_mora'] ?? 0) +
                            ($results[$i]['seguros'] ?? 0) +
                            ($results[$i]['otros_conceptos'] ?? 0);

                        $results[$i]['total_obligacion'] = $total_obligacion;

                        // Calcular datos de Portafolio
                        $saldo_capital = $results[$i]['saldo_capital_original'] ?? 0;
                        $costo_compra_portafolio = $saldo_capital * 0.08; // 8% del Saldo Capital Original
                        $costo_comision_comercial = $costo_compra_portafolio * 0.03; // 3% del Costo Compra Portafolio
                        $costo_reincorporacion_gaf = $total_obligacion * 0.05; // 5% del Total Obligación
                        $costo_coadministracion = 0; // Por ahora en 0, se calculará después
                        $costo_seguro_vd = ($total_obligacion * 0.0236601214850104 / 100) * 3; // (Total Obligación * 0.0236601214850104%) * 3 Meses
                        $costos_fiduciarios = (1423500 * 5) / 51; // Costos Fiduciarios (Fiducoomeva)
                        $reporte_centrales = 10000; // Reporte Centrales ($10.000)
                        $tecnologia = 5000; // Tecnología ($5.000)

                        // Sub Total Costo Compra + Adm (NPL´S) = suma de todos los costos anteriores
                        $sub_total_costo_compra_adm =
                            $costo_compra_portafolio +
                            $costo_comision_comercial +
                            $costo_reincorporacion_gaf +
                            $costo_coadministracion +
                            $costo_seguro_vd +
                            $costos_fiduciarios +
                            $reporte_centrales +
                            $tecnologia;

                        $results[$i]['costo_compra_portafolio'] = $costo_compra_portafolio;
                        $results[$i]['costo_comision_comercial'] = $costo_comision_comercial;
                        $results[$i]['costo_reincorporacion_gaf'] = $costo_reincorporacion_gaf;
                        $results[$i]['costo_coadministracion'] = $costo_coadministracion;
                        $results[$i]['costo_seguro_vd'] = $costo_seguro_vd;
                        $results[$i]['costos_fiduciarios'] = $costos_fiduciarios;
                        $results[$i]['reporte_centrales'] = $reporte_centrales;
                        $results[$i]['tecnologia'] = $tecnologia;
                        $results[$i]['sub_total_costo_compra_adm'] = $sub_total_costo_compra_adm;

                        // Calcular datos de Cupo (por ahora en 0, se calculará después)
                        $cuota_incorporada_previamente = 0;
                        $cupo_sem = 0;
                        $cupo_colpensiones = 0;
                        $cupo_fopep = 0;
                        $cupo_fiduprevisora = 0;
                        $total_cupo_disponible = $cuota_incorporada_previamente + $cupo_sem + $cupo_colpensiones + $cupo_fopep + $cupo_fiduprevisora;

                        $results[$i]['cuota_incorporada_previamente'] = $cuota_incorporada_previamente;
                        $results[$i]['cupo_sem'] = $cupo_sem;
                        $results[$i]['cupo_colpensiones'] = $cupo_colpensiones;
                        $results[$i]['cupo_fopep'] = $cupo_fopep;
                        $results[$i]['cupo_fiduprevisora'] = $cupo_fiduprevisora;
                        $results[$i]['total_cupo_disponible'] = $total_cupo_disponible;

                        // Datos para Cuota a Incorporar
                        // Ya tenemos: tasa_pactada, respetar_tasa_pactada, plazo_pactado, respetar_plazo_pactado, cuota_pactada, respetar_cuota_pactada
                        // Nuevos campos en 0 por ahora:
                        $tasa_nueva_libranza_ck = 0;
                        $plazo_nueva_libranza_ck = 0;
                        $cuota_a_incorporar = 0;
                        $tasa_modificada_conservando_plazo_180 = 0;
                        $plazo_modificado_conservando_tasa_188 = 0;

                        $results[$i]['tasa_nueva_libranza_ck'] = $tasa_nueva_libranza_ck;
                        $results[$i]['plazo_nueva_libranza_ck'] = $plazo_nueva_libranza_ck;
                        $results[$i]['cuota_a_incorporar'] = $cuota_a_incorporar;
                        $results[$i]['tasa_modificada_conservando_plazo_180'] = $tasa_modificada_conservando_plazo_180;
                        $results[$i]['plazo_modificado_conservando_tasa_188'] = $plazo_modificado_conservando_tasa_188;

                        Log::info('[fetchPaginatedResults] Datos mergeados para doc ' . $docStr . ':', [
                            'operacion' => $results[$i]['operacion'],
                            'valor_desembolso' => $results[$i]['valor_desembolso'],
                            'total_obligacion' => $total_obligacion,
                            'costo_compra_portafolio' => $costo_compra_portafolio,
                            'costo_comision_comercial' => $costo_comision_comercial
                        ]);

                        break;
                    }
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
}
