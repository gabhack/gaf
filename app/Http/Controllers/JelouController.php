<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CouponsGen;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JelouController extends Controller
{
    public function getFactorPerDoc(Request $request, $doc)
    {
        $user = $request->header('php-auth-user');
        $password = $request->header('php-auth-pw');

        $apiUser = 'jelou2024';
        $apiPassword = 'qIZK&U$kla';

        if ($user !== $apiUser || $password !== $apiPassword) {
            return response()->json(['error' => 'Unauthorized: invalid credentials'], 401);
        }

        try {
            $ingresosPorPagaduria = $this->obtenerUltimosIngresosPorPagaduria($doc);

            if ($ingresosPorPagaduria->isEmpty()) {
                Log::info("No se encontraron ingresos para el documento: {$doc}");
                return response()->json(['error' => 'No se encontraron ingresos para las pagadurias del documento'], 404);
            }

            $factorMillon = 18500;
            $result = [];

            foreach ($ingresosPorPagaduria as $registro) {
                $compraCartera = $this->calcularCompraCartera($registro->ingresos);

                if ($compraCartera === false || $compraCartera <= 0) {
                    Log::info("No tiene ingresos suficientes para la pagaduría {$registro->pagaduria}. Documento: {$doc}, Ingreso: {$registro->ingresos}");
                    return response()->json([
                        'error' => "No tiene ingresos suficientes para la pagaduría {$registro->pagaduria}"
                    ], 404);
                }

                // Aquí se redondea el factorCompra y se multiplica por 1 millón
                $factorCompra = round($compraCartera / $factorMillon) * 1000000;

                Log::info("Cálculo exitoso para pagaduría {$registro->pagaduria}. Documento: {$doc}, Ingreso: {$registro->ingresos}, Compra de cartera: {$compraCartera}, Factor: {$factorCompra}");

                $result[] = [
                    'pagaduria' => $registro->pagaduria,
                    'factor' => $factorCompra
                ];
            }

            Log::info("Resultados para el documento {$doc}: ", ['resultados' => $result]);

            return response()->json([
                'documento' => $doc,
                'resultados' => $result
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error al procesar la cédula {$doc}: " . $e->getMessage());
            return response()->json(['error' => 'Error procesando la solicitud'], 500);
        }
    }

    private function obtenerUltimosIngresosPorPagaduria($doc)
    {
        try {
            $query = CouponsGen::select('pagaduria', 'ingresos', 'period', 'concept')
                ->where('doc', $doc)
                ->where('concept', 'IgresosCupon')
                ->whereNotNull('ingresos')
                ->orderBy('period', 'desc')
                ->limit(1);

            $ingresosPorPagaduria = $query->get();

            Log::info("Consulta ejecutada para obtener ingresos por pagaduría del documento {$doc}: " . $query->toSql(), ['bindings' => $query->getBindings()]);

            return $ingresosPorPagaduria;
        } catch (\Exception $e) {
            Log::error("Error al obtener los últimos ingresos por pagaduría para el documento {$doc}: " . $e->getMessage());
            return collect();
        }
    }

    private function calcularCompraCartera($ingreso)
    {
        try {
            $salarioMinimo = 1300000;

            if ($ingreso > 2 * $salarioMinimo * 0.92) {
                $compraCartera = ($ingreso * 0.92) / 2;
            } else {
                $compraCartera = ($ingreso * 0.92 - $salarioMinimo) / 2;
            }

            Log::info("Ingreso: {$ingreso}, Salario Mínimo: {$salarioMinimo}, Compra de cartera calculada: {$compraCartera}");

            return $compraCartera > 0 ? $compraCartera : false;
        } catch (\Exception $e) {
            Log::error('Error al calcular compra de cartera: ' . $e->getMessage());
            return false;
        }
    }
}
