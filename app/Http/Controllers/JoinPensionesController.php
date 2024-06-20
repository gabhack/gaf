<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Colpensiones;

class JoinPensionesController extends Controller
{
    public function __construct()
    {
        // Aumentar el tiempo máximo de ejecución para este controlador
        ini_set('max_execution_time', 300);
    }
    public function index()
    {
        return view('pensiones.joinpensiones');
    }

    public function upload(Request $request)
    {
        Log::info('JoinPensiones upload endpoint hit.');
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        Log::info('Validation passed. Storing file.');

        $file = $request->file('file');
        $filePath = $file->getRealPath();

        Log::info('File stored at: ' . $filePath);

        try {
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
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
                if ($cell->getValue() !== null) {
                    $cedulas[] = $cell->getValue();
                }
            }

            Log::info('Cedulas extraídas:', ['cedulas' => $cedulas]);

            return response()->json(['success' => true, 'message' => 'File uploaded successfully.', 'cedulas' => $cedulas]);
        } catch (\Exception $e) {
            Log::error('Error processing file upload: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json(['error' => 'Error procesando el archivo: ' . $e->getMessage()], 500);
        }
    }

    public function search(Request $request)
    {
        $cedulas = $request->input('cedulas');
        $results = [];

        foreach ($cedulas as $cedula) {
            $colpensiones = Colpensiones::where('documento', $cedula)->first();

            if ($colpensiones) {
                $results[] = [
                    'cedula' => $cedula,
                    'primer_apellido' => $colpensiones->primer_apellido,
                    'segundo_apellido' => $colpensiones->segundo_apellido,
                    'primer_nombre' => $colpensiones->primer_nombre,
                    'segundo_nombre' => $colpensiones->segundo_nombre,
                    'direccion' => $colpensiones->direccion,
                    'telefono' => $colpensiones->telefono,
                    'correo_electronico' => $colpensiones->correo_electronico,
                    'nacimiento' => $colpensiones->nacimiento,
                    'sexo' => $colpensiones->sexo,
                    'departamento' => $colpensiones->departamento,
                    'municipio' => $colpensiones->municipio,
                    'vpensiones' => $colpensiones->vpensiones,
                    'vsalud' => $colpensiones->vsalud,
                    'vembargo' => $colpensiones->vembargo,
                    'vdescuentos' => $colpensiones->vdescuentos,
                    'capacidad' => $colpensiones->capacidad
                ];
            } else {
                $results[] = [
                    'cedula' => $cedula,
                    'found' => false
                ];
            }
        }

        return response()->json(['success' => true, 'results' => $results]);
    }
}
