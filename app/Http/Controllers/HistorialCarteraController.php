<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\EstudioCartera;
use App\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class HistorialCarteraController extends Controller
{
    /**
     * Display the historial cartera view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('HistorialCartera.Index');
    }

    /**
     * Get historial data (placeholder for future implementation).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getHistorial()
    {
        // TODO: Implement historial retrieval when ready
        return response()->json([
            'success' => true,
            'data' => []
        ]);
    }

    /**
     * Listar estudios con filtros y paginación.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listar(Request $request)
    {
        try {
            $userId = Auth::id();
            $perPage = $request->input('per_page', 10);
            $page = $request->input('page', 1);

            // Construir query base
            $query = EstudioCartera::with('user:id,name,email')
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc');

            // Aplicar filtros
            if ($request->has('fecha_desde') && $request->fecha_desde) {
                $query->whereDate('created_at', '>=', $request->fecha_desde);
            }

            if ($request->has('fecha_hasta') && $request->fecha_hasta) {
                $query->whereDate('created_at', '<=', $request->fecha_hasta);
            }

            if ($request->has('mes') && $request->mes) {
                $query->where('mes', $request->mes);
            }

            if ($request->has('anio') && $request->anio) {
                $query->where('anio', $request->anio);
            }

            // Paginar resultados
            $estudios = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => $estudios->items(),
                'total' => $estudios->total(),
                'per_page' => $estudios->perPage(),
                'current_page' => $estudios->currentPage(),
                'last_page' => $estudios->lastPage()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar estudios: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar un estudio (soft delete).
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function eliminar($id)
    {
        try {
            $userId = Auth::id();

            $estudio = EstudioCartera::where('id', $id)
                ->where('user_id', $userId)
                ->first();

            if (!$estudio) {
                return response()->json([
                    'success' => false,
                    'message' => 'Estudio no encontrado o no tiene permisos para eliminarlo'
                ], 404);
            }

            $estudio->delete();

            return response()->json([
                'success' => true,
                'message' => 'Estudio eliminado correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar estudio: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar estudio a Excel o PDF.
     *
     * @param int $id
     * @param string $tipo (excel|pdf)
     * @return mixed
     */
    public function exportar($id, $tipo)
    {
        try {
            $userId = Auth::id();

            $estudio = EstudioCartera::where('id', $id)
                ->where('user_id', $userId)
                ->first();

            if (!$estudio) {
                return response()->json([
                    'success' => false,
                    'message' => 'Estudio no encontrado'
                ], 404);
            }

            $datosProcessados = is_array($estudio->datos_procesados)
                ? $estudio->datos_procesados
                : json_decode($estudio->datos_procesados, true);

            if ($tipo === 'excel') {
                return $this->exportarExcel($estudio, $datosProcessados);
            } elseif ($tipo === 'pdf') {
                // Por ahora redirigimos a Excel, el PDF lo manejaremos desde el frontend
                return response()->json([
                    'success' => false,
                    'message' => 'Exportación PDF se maneja desde el frontend'
                ], 400);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tipo de exportación no válido'
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al exportar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar estudio a Excel con formato.
     *
     * @param EstudioCartera $estudio
     * @param array $datos
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    private function exportarExcel($estudio, $datos)
    {
        $spreadsheet = new Spreadsheet();
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
            'Subub Total Costo Compra + Adm (Npl´S)',
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

        // Aplicar estilo a headers
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2C8C73']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
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
                $item['nombre_usuario'] ?? ($item['nombre'] ?? ''),
                $item['pagaduria'] ?? '',
                ($item['colpensiones'] ?? false) ? 'Sí' : 'No',
                ($item['fiducidiaria'] ?? ($item['fiduprevisora'] ?? false)) ? 'Sí' : 'No',
                ($item['fopep'] ?? false) ? 'Sí' : 'No',
                $item['edad'] ?? '',
                $item['valor_desembolso'] ?? 0,
                $item['saldo_capital_original'] ?? 0,
                $item['intereses_corrientes'] ?? 0,
                $item['intereses_de_mora'] ?? ($item['intereses_mora'] ?? 0),
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
                $item['sub_total_costo_compra_adm'] ?? ($item['subtotal_costo_compra_adm'] ?? 0),
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
            'A' => 12,  // Operación
            'B' => 12,  // Cédula
            'C' => 30,  // Nombre
            'D' => 25,  // Secretaria
            'E' => 12,  // Colpensiones
            'F' => 12,  // Fiduprevisora
            'G' => 10,  // Fopep
            'H' => 8,   // Edad
            'I' => 15,  // Desembolso
            'J' => 20,  // Saldo Capital Original
            'K' => 18,  // Intereses Corrientes
            'L' => 18,  // Intereses De Mora
            'M' => 12,  // Seguros
            'N' => 15,  // Otros Concepto
            'O' => 18,  // Total Obligación
            'P' => 22,  // Costo Compra Portafolio
            'Q' => 25,  // Costo Comision Comercial
            'R' => 28,  // Costo Re-Incorporación Gaf
            'S' => 22,  // Costo Coadministración
            'T' => 20,  // Costo Seguro V.D
            'U' => 18,  // Costos Fiduciarios
            'V' => 18,  // Reporte Centrales
            'W' => 12,  // Tecnología
            'X' => 30,  // Subub Total Costo Compra + Adm
            'Y' => 28,  // Cuota Incorporada Previamente
            'Z' => 15,  // Cupo Sem
            'AA' => 18, // Cupo Colpensiones
            'AB' => 15, // Cupo Fopep
            'AC' => 18, // Cupo Fiduprevisora
            'AD' => 22, // Total Cupo Disponible
            'AE' => 15, // Tasa Pactada
            'AF' => 22, // Respetar Tasa Pactada
            'AG' => 22, // Tasa Nueva Libranza Ck
            'AH' => 15, // Plazo Pactado
            'AI' => 22, // Respetar Plazo Pactado
            'AJ' => 22, // Plazo Nueva Libranza Ck
            'AK' => 15, // Cuota Pactada
            'AL' => 22, // Respetar Cuota Pactada
            'AM' => 20, // Cuota A Incorporar
            'AN' => 38, // Tasa Modificada Conservando Plazo 180)
            'AO' => 38  // Plazo Modificado Conservando Tasa 1,88%)
        ];

        foreach ($columnWidths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // Nombre del archivo
        $filename = 'estudio_cartera_' . $estudio->mes . '_' . $estudio->anio . '_' . date('YmdHis') . '.xlsx';

        // Preparar descarga
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    /**
     * Formatear array para Excel (embargos, cupones, descuentos).
     *
     * @param array $data
     * @return string
     */
    private function formatArray($data)
    {
        if (empty($data)) {
            return 'N/D';
        }

        if (is_string($data)) {
            return $data;
        }

        $lines = [];
        foreach ($data as $index => $item) {
            if (is_array($item)) {
                $lines[] = implode(' | ', array_filter($item, function($v) {
                    return $v !== null && $v !== '';
                }));
            } else {
                $lines[] = $item;
            }
        }

        return implode("\n", $lines);
    }
}
