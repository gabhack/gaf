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
use Illuminate\Support\Facades\Storage; // <- añadido
use Illuminate\Http\JsonResponse;

class DemograficoController extends Controller
{
    public function upload(Request $request)
    {
        Log::info('[upload] Iniciando método upload...');
        try {
            $file = $request->file('file');
            if (!$file || !$file->isValid()) {
                Log::warning('[upload] Archivo no válido o no encontrado.');
                return response()->json(['error' => 'Archivo inválido'], 400);
            }

            // 1) Guardar el archivo en storage (disco public)
            $storedFilename = Str::uuid().'.'.$file->getClientOriginalExtension();
            $storedPath = $file->storeAs(
                'demografico_uploads/'.Auth::id(),
                $storedFilename,
                'public'
            );
            $absolutePath = Storage::disk('public')->path($storedPath);
            Log::info('[upload] Archivo guardado en storage.', [
                'stored_path' => $storedPath,
                'absolute_path' => $absolutePath
            ]);

            // 2) Leer el Excel desde el archivo ya guardado
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($absolutePath);
            Log::info('[upload] Spreadsheet cargado correctamente.');

            $worksheet = $spreadsheet->getActiveSheet();
            $highestColumn = $worksheet->getHighestColumn();
            $headerRange = 'A1:' . $highestColumn . '1';
            $header = $worksheet->rangeToArray($headerRange)[0];
            Log::info('[upload] Header extraído: ', $header);

            $cedulasColumn = array_search('cedulas', array_map('strtolower', $header));
            if ($cedulasColumn === false) {
                Log::warning('[upload] No se encontró la columna "cedulas" en el archivo Excel.');
                return response()->json(['error' => 'No se encontró la columna "cedulas"'], 400);
            }

            $cedulas = [];
            foreach ($worksheet->getRowIterator(2) as $row) {
                $cell = $worksheet->getCellByColumnAndRow($cedulasColumn + 1, $row->getRowIndex());
                $value = trim($cell->getValue());
                if (!empty($value)) {
                    $cedulas[] = $value;
                }
            }

            Log::info('[upload] Total de cédulas extraídas: '.count($cedulas));

            $cacheKey = 'cedulas_' . Auth::id();
            Cache::put($cacheKey, $cedulas, 3600);
            Log::info('[upload] Cédulas almacenadas en caché con key: '.$cacheKey);

            return response()->json([
                'uploaded'    => true,
                'stored_path' => $storedPath, // devolver dónde quedó guardado
                'total'       => count($cedulas),
            ], 200);
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

            $cacheKey = 'cedulas_' . Auth::id();
            $cedulas = Cache::get($cacheKey, []);
            $total = count($cedulas);

            Log::info("[fetchPaginatedResultsDemografico] Page: {$page}, PerPage: {$perPage}, Total: {$total}");

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

            $cedulasChunk = array_slice($cedulas, $offset, $perPage);
            Log::info('[fetchPaginatedResultsDemografico] Cédulas chunk size: '.count($cedulasChunk));

            $results = $this->processCedulasDemografico($cedulasChunk);

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
            $perPage = (int) $request->query('perPage', 10000);
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
            $total = count($cedulas);
            $offset = ($page - 1) * $perPage;
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
            $results = $this->processCedulas_vista($cedulasChunk, $request->query('mes'), $request->query('año'));
            Log::info('Fin del proceso de fetchPaginatedResults');
            return response()->json([
                'data' => $results,
                'total' => $total,
                'page' => $page,
                'perPage' => $perPage,
                'hasMore' => ($offset + $perPage) < $total
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

            $latestRecords = DatamesGen::whereIn('doc', $cedulas)
                ->orderBy('created_at', 'desc')
                ->get()
                ->unique('doc')
                ->keyBy('doc');

            $colpensionesDocs = Colpensiones::whereIn('documento', $cedulas)->pluck('documento')->toArray();
            $fiducidiariaDocs = Fiducidiaria::whereIn('documento', $cedulas)->pluck('documento')->toArray();

            $couponsGenRecords = CouponsGen::whereIn('doc', $cedulas)
                ->whereBetween('inicioperiodo', [$startDate, $endDate])
                ->get()
                ->groupBy('doc');

            $embargosGenRecords = EmbargosGen::whereIn('doc', $cedulas)
                ->whereBetween('nomina', [$startDate, $endDate])
                ->select('doc', 'docdeman', 'entidaddeman', 'fembini', 'temb as valor', 'pagaduria')
                ->get()
                ->groupBy('doc');

            $descuentosGenRecords = DescuentosGen::whereIn('doc', $cedulas)
                ->whereBetween('nomina', [$startDate, $endDate])
                ->get()
                ->groupBy('doc');

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
            $salarioMinimo = 1423500;

            foreach ($cedulas as $cedula) {
                $cedulaStr = (string)$cedula;

                $existsInColpensiones = in_array($cedulaStr, $colpensionesDocs);
                $existsInFiducidiaria = in_array($cedulaStr, $fiducidiariaDocs);

                $record = $latestRecords->get($cedulaStr);
                $situacionLaboral = $record ? $record->situacion_laboral : 'No disponible';

                $couponsForCedula = $couponsGenRecords->get($cedulaStr, collect());

                $cargoRecord = $couponsForCedula->first();
                $cargo = $cargoRecord ? $cargoRecord->cargo : 'No disponible';

                Log::info("Procesando cédula: {$cedulaStr}", [
                    'colpensiones' => $existsInColpensiones,
                    'fiducidiaria' => $existsInFiducidiaria,
                    'cargo' => $cargo,
                    'situacion_laboral' => $situacionLaboral
                ]);

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

                    $cupones = $couponsForCedula->where('pagaduria', $pagaduria);

                    $couponsIngresos = $cupones->whereNotIn('code', ['APFPM', 'APEPEN', 'APESDN']);
                    $totalWithoutHealthPension = $couponsIngresos->sum('vaplicado');

                    $embargosForCedula = $embargosGenRecords->get($cedulaStr, collect());
                    $embargos = $embargosForCedula->where('pagaduria', $pagaduria);

                    $descuentosForCedula = $descuentosGenRecords->get($cedulaStr, collect());
                    $descuentos = $descuentosForCedula->where('pagaduria', $pagaduria)
                        ->filter(function ($descuento) {
                            return $descuento->mliquid != 'ALERTA';
                        });

                    $valorIngreso = 0;
                    if (in_array($pagaduria, ['FOPEP', 'FIDUPREVISORA'])) {
                        if ($pagaduria == 'FOPEP') {
                            $datames = $datamesFopepRecords->get($cedulaStr);
                        } elseif ($pagaduria == 'FIDUPREVISORA') {
                            $datames = $datamesFiduRecords->get($cedulaStr);
                        }
                        if ($datames && $datames->vpension) {
                            $valorIngresoStr = preg_replace('/[^0-9]/', '', $datames->vpension);
                            $valorIngreso = substr($valorIngresoStr, 0, -2);
                            $valorIngreso = floatval($valorIngreso);
                        }
                    } else {
                        $ingresoRecord = $cupones->where('code', 'INGCUP')->first();
                        $valorIngreso = $ingresoRecord ? floatval($ingresoRecord->ingresos) : 0;
                    }

                    $increase = 0;
                    $cargoLower = strtolower($cargo);
                    if (strpos($cargoLower, 'rector') !== false) {
                        $increase = $valorIngreso * 0.3;
                    } elseif (strpos($cargoLower, 'coordinador') !== false) {
                        $increase = $valorIngreso * 0.2;
                    } elseif (strpos($cargoLower, 'director') !== false) {
                        $increase = $valorIngreso * 0.35;
                    }
                    $valorIngreso += $increase;

                    $descuento = 0.08;
                    if (in_array($pagaduria, ['FOPEP', 'FIDUPREVISORA'])) {
                        if ($valorIngreso == $salarioMinimo) {
                            $descuento = 0.04;
                        } elseif ($valorIngreso > $salarioMinimo && $valorIngreso < $salarioMinimo * 2) {
                            $descuento = 0.08;
                        } else {
                            $descuento = 0.12;
                        }
                    }
                    if (strtoupper($pagaduria) === 'CASUR') {
                        $descuento = 0.05;
                    }
                    if ($valorIngreso > 5694000 && strtoupper($pagaduria) !== 'CASUR') {
                        $descuento += 0.01;
                    }

                    $valorIngresoConDescuento = $valorIngreso - ($valorIngreso * $descuento);
                    $montoDesc = $valorIngreso * $descuento;

                    $egresosExcluidos = ['APFPM', 'APEPEN', 'APESDN'];
                    $egresos = $cupones->whereNotIn('code', $egresosExcluidos)
                        ->where(function ($coupon) {
                            return preg_match('/^\d+(\.\d+)?$/', $coupon->egresos);
                        })
                        ->sum(function ($coupon) {
                            return (float)$coupon->egresos;
                        });

                    $egresosAjustados = max($egresos - $montoDesc, 0);
                    Log::info('Egresos ajustados por descuentos espejo', [
                        'egresos_original' => $egresos,
                        'ajuste_pct' => $descuento,
                        'egresos_ajustados' => $egresosAjustados
                    ]);

                    if ($valorIngresoConDescuento < $salarioMinimo * 2) {
                        $compraCartera = $valorIngresoConDescuento - $salarioMinimo - $totalWithoutHealthPension;
                    } else {
                        $compraCartera = ($valorIngresoConDescuento / 2) - $totalWithoutHealthPension;
                    }

                    $libreInversion = $compraCartera - $egresosAjustados;

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
                        'cupones' => $cupones->values(),
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

            $resultsData = DB::connection('pgsql')
                ->table('fast_aggregate_data')
                ->whereIn('doc', $cedulas)
                ->whereRaw("extract(month from CAST(inicioperiodo AS date)) = ?", [$mes])
                ->whereRaw("extract(year from CAST(inicioperiodo AS date)) = ?", [$año])
                ->get();

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

            $colpensionesDocs = $colpensionesAll->keys()->all();
            $fiducidiariaDocs = $fiducidiariaAll->keys()->all();
            $fopepDocs        = $fopepAll->keys()->all();

            $results = collect();
            $salarioMinimo = 1423500;

            Log::info("Comenzando procesamiento de las cédulas...");

            foreach ($cedulas as $cedula) {
                $cedulaStr = (string)$cedula;
                Log::info("Procesando cédula: {$cedulaStr}");

                $existsInColpensiones = in_array($cedulaStr, $colpensionesDocs);
                $existsInFiducidiaria = in_array($cedulaStr, $fiducidiariaDocs);
                $existsInFopep        = in_array($cedulaStr, $fopepDocs);

                Log::info("Estado en fuentes externas (Colpensiones, Fiducidiaria, Fopep)", [
                    'colpensiones' => $existsInColpensiones,
                    'fiducidiaria' => $existsInFiducidiaria,
                    'fopep' => $existsInFopep
                ]);

                $dataForCedula = $resultsData->where('doc', $cedulaStr);

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

                if ($dataForCedula->isEmpty()) {
                    if ($existsInColpensiones || $existsInFiducidiaria || $existsInFopep) {
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

                foreach ($dataForCedula as $data) {
                    Log::info("Registro fast_aggregate_data encontrado", ['data' => $data]);

                    $pagaduria        = $data->pagaduria;
                    $nombre_usuario   = $data->nombre_usuario;
                    $tipo_contrato    = $data->tipo_contrato;
                    $cargo            = $data->cargos;
                    $edad             = $data->edad;
                    $situacionLaboral = $data->situacion_laboral;

                    if (empty($nombre_usuario) && $nombrePensionado) {
                        $nombre_usuario = $nombrePensionado;
                    }

                    $fecha_nacimiento = $data->fecha_nacimiento ?? null;
                    if ($fecha_nacimiento) {
                        try {
                            if (is_numeric($fecha_nacimiento)) {
                                $fecha_nacimiento = Carbon::createFromTimestamp($fecha_nacimiento)->format('Y-m-d');
                            } elseif (preg_match('/\d{2}\/\d{2}\/\d{4}/', $fecha_nacimiento)) {
                                $fecha_nacimiento = Carbon::createFromFormat('d/m/Y', $fecha_nacimiento)->format('Y-m-d');
                            } else {
                                $fecha_nacimiento = Carbon::parse($fecha_nacimiento)->format('Y-m-d');
                            }
                        } catch (\Exception $e) {
                            $fecha_nacimiento = null;
                        }
                    }

                    $total_egresos = $data->total_egresos;
                    $valorIngreso = $data->ingresos_ajustados;

                    $embargos = collect();
                    if (!empty($data->embargos_concatenados)) {
                        preg_match_all('/(\d+): ([^,]+), ([\d\/\-]+), ([\d,]+)/', $data->embargos_concatenados, $matches, PREG_SET_ORDER);
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

                    $descuentos = collect(explode(', ', $data->descuentos_concatenados))
                        ->filter(function ($descuento) {
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
                            return $item && $item['valor'] > 0;
                        })
                        ->unique('mliquid');

                    $descuento = 0.08;
                    if (in_array($pagaduria, ['FOPEP','FIDUPREVISORA'])) {
                        if ($valorIngreso == $salarioMinimo) {
                            $descuento = 0.04;
                        } elseif ($valorIngreso > $salarioMinimo && $valorIngreso < $salarioMinimo * 2) {
                            $descuento = 0.08;
                        } elseif ($valorIngreso >= $salarioMinimo * 2) {
                            $descuento = 0.12;
                        }
                    }
                    if (strtoupper($pagaduria) === 'CASUR') {
                        $descuento = 0.05;
                    }
                    if ($valorIngreso > 5694000 && strtoupper($pagaduria) !== 'CASUR') {
                        $descuento += 0.01;
                    }

                    Log::info("Descuento final para cédula {$cedulaStr} => " . ($descuento * 100) . "%");

                    $valorIngresoConDescuento = $valorIngreso - ($valorIngreso * $descuento);
                    Log::info("Valor ingreso con descuento = {$valorIngresoConDescuento}");

                    $montoDesc = $valorIngreso * $descuento;
                    $egresosAjustados = max($total_egresos - $montoDesc, 0);
                    Log::info("Egresos ajustados por descuentos espejo", [
                        'egresos_original' => $total_egresos,
                        'ajuste_pct' => $descuento,
                        'egresos_ajustados' => $egresosAjustados
                    ]);

                    if ($valorIngresoConDescuento < $salarioMinimo * 2) {
                        $compraCartera = $valorIngresoConDescuento - $salarioMinimo;
                        Log::info("Valor ingreso con descuento < 2 SMMLV, compraCartera = {$compraCartera}");
                    } else {
                        $compraCartera = $valorIngresoConDescuento / 2;
                        Log::info("Valor ingreso con descuento >= 2 SMMLV, compraCartera = {$compraCartera}");
                    }

                    $libreInversion = $compraCartera - $egresosAjustados;
                    Log::info("Cupo libre = compraCartera - egresos_ajustados => {$libreInversion}", [
                        'compraCartera' => $compraCartera,
                        'egresos_ajustados' => $egresosAjustados
                    ]);

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
                        'descuentos'        => $descuentos->isEmpty() ? null : $descuentos,
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

    public function uploadPending(Request $r)
    {
        $r->validate([
            'file' => 'required|file|mimes:xlsx,xls',
            'mes'  => 'required|integer|min:1|max:12',
            'anio' => 'required|integer|min:1900|max:'.(date('Y')+1),
        ]);

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

        $storedPath = $r->file('file')
                        ->storeAs('demografico_pending',Str::uuid().'.'.$r->file('file')->getClientOriginalExtension(),'public');

        $upload = PendingDemographicUpload::create([
            'user_id'       => Auth::id(),
            'original_name' => $r->file('file')->getClientOriginalName(),
            'stored_path'   => $storedPath,
            'mes'           => $r->mes,
            'anio'          => $r->anio,
            'cedulas'       => $cedulas,
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

        Cache::put('cedulas_'.Auth::id(), $u->cedulas, 3600);

        return response()->json([
            'analyzed'      => true,
            'mes'           => $u->mes,
            'año'           => $u->anio,
            'analizado_por' => $u->analyzed_by,
        ]);
    }

    private function calcularDescuento(string $pagaduria, float $ingreso, float $smmlv): float
    {
        $p = strtoupper(trim($pagaduria));
        if ($p === 'CASUR') return 0.05;
        $d = 0.08;
        if (in_array($p, ['FOPEP','FIDUPREVISORA'])) {
            if ($ingreso == $smmlv) $d = 0.04;
            elseif ($ingreso > $smmlv && $ingreso < $smmlv * 2) $d = 0.08;
            else $d = 0.12;
        }
        if ($ingreso > 5694000) $d += 0.01;
        return $d;
    }

    private function calcularCupoCore(float $valorIngreso, float $totalEgresos, string $pagaduria, float $salarioMinimo, float $rtsfaValue = 0.0): array
    {
        $pag = strtoupper(trim($pagaduria));
        $descuentoBase = $pag === 'CASUR' ? 0.05 : 0.08;
        if (in_array($pag, ['FOPEP', 'FIDUPREVISORA'])) {
            if ($valorIngreso == $salarioMinimo) $descuentoBase = 0.04;
            elseif ($valorIngreso > $salarioMinimo && $valorIngreso < $salarioMinimo * 2) $descuentoBase = 0.08;
            else $descuentoBase = 0.12;
        }
        $adicional = 0.0;
        if ($valorIngreso > 5694000 && $pag !== 'CASUR') $adicional = 0.01;
        $descuentoTotal = $descuentoBase + $adicional;
        $montoDescuentoSalario = $valorIngreso * $descuentoTotal;
        $valorIngresoConDescuento = $valorIngreso - $montoDescuentoSalario;
        $ajusteRTFSA = 0.0;
        if ($valorIngreso > $salarioMinimo * 5 && $rtsfaValue > 0) {
            $ajusteRTFSA = $rtsfaValue;
            $valorIngresoConDescuento -= $ajusteRTFSA;
        }
        $egresosAjustados = max($totalEgresos - $montoDescuentoSalario, 0);
        if ($valorIngresoConDescuento < $salarioMinimo * 2) $compraCartera = $valorIngresoConDescuento - $salarioMinimo;
        else $compraCartera = $valorIngresoConDescuento / 2;
        $cupoLibre = $compraCartera - $egresosAjustados;

        return [
            'descuento_base'      => $descuentoBase,
            'descuento_adicional' => $adicional,
            'descuento_total'     => $descuentoTotal,
            'monto_desc_salario'  => $montoDescuentoSalario,
            'ingreso_desc'        => $valorIngresoConDescuento,
            'egresos_ajustados'   => $egresosAjustados,
            'ajuste_rtsfa'        => $ajusteRTFSA,
            'compra_cartera'      => $compraCartera,
            'cupo_libre'          => $cupoLibre,
        ];
    }
}
