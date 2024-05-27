<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DatamesGen;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DemograficoController extends Controller
{
    public function upload(Request $request)
    {
        ini_set('memory_limit', '2048M');  // Aumentar el límite de memoria
        ini_set('max_execution_time', '600');  // Aumentar el límite de tiempo de ejecución

        Log::info('Inicio del proceso de carga de archivo');

        try {
            $file = $request->file('file');
            if (!$file || !$file->isValid()) {
                throw new \Exception('Error uploading file');
            }

            Log::info('Archivo cargado correctamente');

            $filePath = $file->getRealPath();

            // Usar el lector de Excel en modo de solo lectura
            Log::info('Inicio de la carga del archivo Excel');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($filePath);
            Log::info('Archivo Excel cargado correctamente');

            $worksheet = $spreadsheet->getActiveSheet();

            // Logging para depurar el encabezado
            Log::info('Inicio de la lectura del encabezado');
            $highestColumn = $worksheet->getHighestColumn();
            $headerRange = 'A1:' . $highestColumn . '1';
            $header = $worksheet->rangeToArray($headerRange)[0];
            Log::info('Excel header:', ['header' => $header]);
            Log::info('Excel header range:', ['headerRange' => $headerRange]);

            // Imprimir cada celda del encabezado
            foreach ($header as $index => $cellValue) {
                Log::info('Header cell:', ['index' => $index, 'value' => $cellValue]);
            }

            // Buscar la columna con el encabezado 'cedulas'
            $cedulasColumn = array_search('cedulas', array_map('strtolower', $header));
            if ($cedulasColumn === false) {
                throw new \Exception('No se encontró la columna "cedulas"');
            }

            Log::info('Cedulas column index:', ['cedulasColumn' => $cedulasColumn]);

            // Extraer las cedulas
            Log::info('Inicio de la extracción de cédulas');
            $cedulas = [];
            foreach ($worksheet->getRowIterator(2) as $row) {
                $cell = $worksheet->getCellByColumnAndRow($cedulasColumn + 1, $row->getRowIndex());
                $cedulas[] = $cell->getValue();
                Log::info('Cedula extraída:', ['cedula' => $cell->getValue()]);
            }

            Log::info('Cedulas extraídas:', ['cedulas' => $cedulas]);

            // Consulta en la base de datos para obtener el último registro de cada cédula
            Log::info('Inicio de la consulta en la base de datos');
            $results = collect();
            foreach ($cedulas as $cedula) {
                $record = DatamesGen::where('doc', $cedula)->orderBy('id', 'desc')->first(['doc', 'cel', 'direccion_residencial', 'telefono', 'correo_electronico']);
                if ($record) {
                    $results->push($record);
                }
            }
            Log::info('Consulta en la base de datos completada', ['results' => $results]);

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

    public function show()
    {
        return view('Demographic.DemographicData');
    }
}
