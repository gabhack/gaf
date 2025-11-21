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
        // Aplicar estilo a headers (75 columnas = A hasta BW)
        $sheet->getStyle('A1:BW1')->applyFromArray($headerStyle);

        // Escribir datos
        $row = 2;
        foreach ($datos as $item) {
            $rowData = [
                // INFORMACIÓN GENERAL (8)
                $item['operacion'] ?? '',
                $item['doc'] ?? '',
                $item['nombre_usuario'] ?? ($item['nombre'] ?? ''),
                $item['pagaduria'] ?? '',
                ($item['colpensiones'] ?? false) ? 'Sí' : 'No',
                ($item['fiducidiaria'] ?? ($item['fiduprevisora'] ?? false)) ? 'Sí' : 'No',
                ($item['fopep'] ?? false) ? 'Sí' : 'No',
                $item['edad'] ?? '',
                // DETALLE DE CRÉDITO (7)
                $item['valor_desembolso'] ?? 0,
                $item['saldo_capital_original'] ?? 0,
                $item['intereses_corrientes'] ?? 0,
                $item['intereses_de_mora'] ?? ($item['intereses_mora'] ?? 0),
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
                $item['sub_total_costo_compra_adm'] ?? ($item['subtotal_costo_compra_adm'] ?? 0),
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
                // ANÁLISIS AVANZADO - CAMPOS CALCULADOS (24) - pueden no existir en estudios antiguos
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

        // Aplicar ancho predeterminado a columnas no especificadas
        for ($i = 1; $i <= 75; $i++) {
            $col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
            if (!isset($columnWidths[$col])) {
                $sheet->getColumnDimension($col)->setWidth(20);
            }
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
