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
                return response()->json(['error' => 'No se encontraron ingresos para las pagadurias del documento'], 404);
            }

            $factorMillon = 18500;
            $result = [];

            foreach ($ingresosPorPagaduria as $registro) {
                $compraCartera = $this->calcularCompraCartera($registro->ingresos);

                if ($compraCartera === false) {
                    return response()->json([
                        'error' => "No se pudo calcular la compra de cartera para la pagaduría {$registro->pagaduria}"
                    ], 404);
                }

                $factorCompra = $compraCartera * $factorMillon;
                $result[] = [
                    'pagaduria' => $registro->pagaduria,
                    'ingreso' => $registro->ingresos,
                    'compraCartera' => $compraCartera,
                    'factor' => $factorCompra
                ];
            }

            return response()->json([
                'message' => 'Cálculo exitoso',
                'documento' => $doc,
                'resultados' => $result
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al procesar la cédula: ' . $e->getMessage());
            return response()->json(['error' => 'Error procesando la solicitud'], 500);
        }
    }

    private function obtenerUltimosIngresosPorPagaduria($doc)
{
    try {
        return CouponsGen::select('pagaduria', 'ingresos')
            ->where('doc', $doc)
            ->whereNotNull('ingresos')
            ->whereIn('id', function($query) use ($doc) {
                $query->select(DB::raw('MAX(id)'))
                      ->from('couponsgen')
                      ->where('doc', $doc)
                      ->groupBy('pagaduria');
            })
            ->get();
    } catch (\Exception $e) {
        Log::error("Error al obtener los últimos ingresos por pagaduría para el documento {$doc}: " . $e->getMessage());
        return collect(); // Devolver una colección vacía en caso de error
    }
}

    

    private function calcularCompraCartera($ingreso)
    {
        try {
            $salarioMinimo = 1300000;

            if ($ingreso > 2 * $salarioMinimo * 0.92) {
                return ($ingreso * 0.92) / 2;
            } else {
                return ($ingreso * 0.92 - $salarioMinimo) / 2;
            }
        } catch (\Exception $e) {
            Log::error('Error al calcular compra de cartera: ' . $e->getMessage());
            return false;
        }
    }
}
