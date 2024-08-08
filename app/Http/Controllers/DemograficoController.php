<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DatamesGen;
use App\CouponsGen;
use App\DemographicConsultLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 30);

        $cedulas = $request->session()->get('cedulas', []);
        $offset = ($page - 1) * $perPage;
        $cedulasChunk = array_slice($cedulas, $offset, $perPage);

        if (empty($cedulasChunk)) {
            Log::info('No se encontraron cédulas para esta página');
            return response()->json(['data' => [], 'total' => count($cedulas)], 200);
        }

        $results = $this->processCedulas($cedulasChunk);

        Log::info('Fin del proceso de fetchPaginatedResults');
        return response()->json([
            'data' => $results,
            'total' => count($cedulas),
            'page' => $page,
            'perPage' => $perPage
        ]);
    }

    private function processCedulas($cedulas)
    {
        Log::info('Inicio del proceso de processCedulas', ['cedulas' => $cedulas]);

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
        }

        Log::info('Fin del proceso de processCedulas', ['results' => $results]);
        return $results;
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
                ->select('doc', 'nombre_usuario', 'cel', 'telefono', 'correo_electronico', 'ciudad', 'direccion_residencial', 'cencosto as centro_costo', 'tipo_contrato', 'edad', 'fecha_nacimiento', 'created_at')
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
}
