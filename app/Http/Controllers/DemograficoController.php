<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DatamesGen;
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

            // Usar subconsulta para obtener el último registro según created_at para cada documento
            $latestRecords = DatamesGen::whereIn('doc', $cedulas)
                ->select('doc', 'nombre_usuario', 'cel', 'telefono', 'correo_electronico', 'ciudad', 'direccion_residencial', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get()
                ->unique('doc')
                ->keyBy('doc');

            $results = collect();
            foreach ($cedulas as $cedula) {
                if ($record = $latestRecords->get($cedula)) {
                    // Separar y clasificar teléfonos y celulares
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

                    $results->push([
                        'doc' => $record->doc,
                        'nombre_usuario' => $record->nombre_usuario,
                        'cel' => implode(', ', $cellphones),
                        'tel' => implode(', ', $landlines),
                        'correo_electronico' => $record->correo_electronico,
                        'ciudad' => $record->ciudad,
                        'direccion_residencial' => $record->direccion_residencial
                    ]);
                }
            }

            Log::info('Consulta en la base de datos completada', ['results' => $results]);

            // Guardar la consulta en la bitácora
            DemographicConsultLog::create([
                'user_id' => Auth::id(),
                'consulta_data' => $results->toArray()
            ]);

            return response()->json($results);
        } catch (\Exception $e) {
            Log::error('Error processing file upload: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json(['error' => 'Error procesando el archivo: ' . $e->getMessage()], 500);
        }
    }

    public function getRecentConsultations()
    {
        $recentConsultations = DemographicConsultLog::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(5) // Obtener las últimas 5 consultas
            ->get();

        return response()->json($recentConsultations);
    }

    public function show()
    {
        return view('Demographic.DemographicData');
    }

    public function getDemograficoPorDoc($doc)
    {
        try {
            $record = DatamesGen::where('doc', $doc)
                ->select('doc', 'nombre_usuario', 'cel', 'telefono', 'correo_electronico', 'ciudad', 'direccion_residencial', 'created_at')
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$record) {
                return response()->json(['error' => 'Documento no encontrado'], 404);
            }

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
