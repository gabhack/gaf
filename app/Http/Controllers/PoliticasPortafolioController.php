<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PoliticasPortafolioController extends Controller
{
    /**
     * Display the politicas portafolio view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('PoliticasPortafolio.Index');
    }

    /**
     * Get all politicas from database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request)
    {
        $politicas = DB::table('politicas_portafolio')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'politicas' => $politicas
        ]);
    }

    /**
     * Store a new politica in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'politica.nombre' => 'required|string|max:255',
                'politica.descripcion' => 'nullable|string',
                'politica.porcentaje_compra_portafolio' => 'required|numeric|min:0',
                'politica.porcentaje_comision_comercial' => 'required|numeric|min:0',
                'politica.porcentaje_reincorporacion_gaf' => 'required|numeric|min:0',
                'politica.costo_administracion' => 'required|numeric|min:0',
                'politica.porcentaje_costo_seguro_vd' => 'required|numeric|min:0',
                'politica.costo_reporte_centrales' => 'required|numeric|min:0',
                'politica.tecnologia' => 'required|numeric|min:0',
                'politica.activo' => 'nullable|boolean',
            ]);

            $politica = $validated['politica'];
            $politica['activo'] = $politica['activo'] ?? true;
            $politica['created_at'] = now();
            $politica['updated_at'] = now();

            $id = DB::table('politicas_portafolio')->insertGetId($politica);

            $politica['id'] = $id;

            return response()->json([
                'success' => true,
                'message' => 'Política creada exitosamente',
                'politica' => $politica
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la política: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing politica in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'politica.id' => 'required|integer',
                'politica.nombre' => 'required|string|max:255',
                'politica.descripcion' => 'nullable|string',
                'politica.porcentaje_compra_portafolio' => 'required|numeric|min:0',
                'politica.porcentaje_comision_comercial' => 'required|numeric|min:0',
                'politica.porcentaje_reincorporacion_gaf' => 'required|numeric|min:0',
                'politica.costo_administracion' => 'required|numeric|min:0',
                'politica.porcentaje_costo_seguro_vd' => 'required|numeric|min:0',
                'politica.costo_reporte_centrales' => 'required|numeric|min:0',
                'politica.tecnologia' => 'required|numeric|min:0',
                'politica.activo' => 'nullable|boolean',
            ]);

            $politica = $validated['politica'];
            $id = $politica['id'];

            // Check if politica exists
            $exists = DB::table('politicas_portafolio')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->exists();

            if (!$exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Política no encontrada'
                ], 404);
            }

            unset($politica['id']);
            $politica['updated_at'] = now();

            DB::table('politicas_portafolio')
                ->where('id', $id)
                ->update($politica);

            $politica['id'] = $id;

            return response()->json([
                'success' => true,
                'message' => 'Política actualizada exitosamente',
                'politica' => $politica
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la política: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the active status of a politica.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleActivo(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer'
            ]);

            $id = $request->input('id');

            // Get current politica
            $politica = DB::table('politicas_portafolio')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->first();

            if (!$politica) {
                return response()->json([
                    'success' => false,
                    'message' => 'Política no encontrada'
                ], 404);
            }

            // Toggle activo status
            $newStatus = !$politica->activo;
            DB::table('politicas_portafolio')
                ->where('id', $id)
                ->update([
                    'activo' => $newStatus,
                    'updated_at' => now()
                ]);

            $status = $newStatus ? 'activada' : 'desactivada';

            return response()->json([
                'success' => true,
                'message' => "Política {$status} exitosamente"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a politica from database (soft delete).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer'
            ]);

            $id = $request->input('id');

            // Check if politica exists
            $exists = DB::table('politicas_portafolio')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->exists();

            if (!$exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Política no encontrada'
                ], 404);
            }

            // Soft delete
            DB::table('politicas_portafolio')
                ->where('id', $id)
                ->update([
                    'deleted_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Política eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la política: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export all politicas to JSON file (useful for backup/debugging).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function exportJson()
    {
        $politicas = session('politicas_portafolio', []);

        return response()->json([
            'success' => true,
            'data' => array_values($politicas),
            'count' => count($politicas),
            'exported_at' => now()->toIso8601String()
        ]);
    }

    /**
     * Import politicas from JSON (useful for restoring data).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function importJson(Request $request)
    {
        try {
            $validated = $request->validate([
                'politicas' => 'required|array',
                'politicas.*.nombre' => 'required|string',
                'politicas.*.porcentaje_portafolio' => 'required|numeric',
                'politicas.*.porcentaje_comision_comercial' => 'required|numeric',
                'politicas.*.porcentaje_reincorporacion_gaf' => 'required|numeric',
                'politicas.*.porcentaje_coadministracion' => 'required|numeric',
                'politicas.*.porcentaje_costo_seguro_vd' => 'required|numeric',
            ]);

            $politicas = [];
            foreach ($validated['politicas'] as $politica) {
                $id = $politica['id'] ?? Str::uuid()->toString();
                $politica['id'] = $id;
                $politica['created_at'] = $politica['created_at'] ?? now()->toIso8601String();
                $politica['updated_at'] = now()->toIso8601String();
                $politica['activo'] = $politica['activo'] ?? true;

                $politicas[$id] = $politica;
            }

            session(['politicas_portafolio' => $politicas]);

            return response()->json([
                'success' => true,
                'message' => 'Políticas importadas exitosamente',
                'count' => count($politicas)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al importar políticas: ' . $e->getMessage()
            ], 500);
        }
    }

    // ========================================================================
    // POLÍTICAS DEL FONDO - CRUD METHODS
    // ========================================================================

    /**
     * Get all fondos from database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFondos(Request $request)
    {
        $fondos = DB::table('politicas_fondos')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'fondos' => $fondos
        ]);
    }

    /**
     * Store a new fondo in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeFondo(Request $request)
    {
        try {
            $validated = $request->validate([
                'fondo.nombre_fondo' => 'required|string|max:255',
                'fondo.descripcion' => 'nullable|string',
                'fondo.smlv' => 'required|numeric|min:0',
                'fondo.dias_mora_max' => 'required|integer|min:0',
                'fondo.plazo_max' => 'required|integer|min:0',
                'fondo.ta_min_ea' => 'required|numeric|min:0',
                'fondo.t_usura_ea' => 'required|numeric|min:0',
                'fondo.tasa_usura' => 'required|numeric|min:0',
                'fondo.costo_asegurabilidad_mes' => 'nullable|numeric|min:0',
                'fondo.descuento_max_saldo_total' => 'nullable|numeric|min:0',
                'fondo.descuento_max_saldo_capital' => 'nullable|numeric|min:0',
                'fondo.activo' => 'nullable|boolean',
            ]);

            $fondo = $validated['fondo'];
            $fondo['activo'] = $fondo['activo'] ?? true;

            // Calculate derived fields
            // SALDO MAX = SMLV × 90
            $fondo['saldo_max'] = round($fondo['smlv'] * 90, 2);

            // T.A MIN (EM) = ((1+T.A MIN (EA)/100)^(1/12))-1 * 100
            // Convertir de % a decimal, aplicar fórmula, volver a %
            $taMinEADecimal = $fondo['ta_min_ea'] / 100;
            $taMinEMDecimal = pow(1 + $taMinEADecimal, 1/12) - 1;
            $fondo['ta_min_em'] = round($taMinEMDecimal * 100, 6);

            // T. USURA -2 (EA) = T. USURA (EA) - 2
            $fondo['t_usura_menos2_ea'] = round($fondo['t_usura_ea'] - 2, 6);

            // T. USURA (EM) = ((1+T. USURA (EA)/100)^(1/12))-1 * 100
            // Convertir de % a decimal, aplicar fórmula, volver a %
            $tUsuraEADecimal = $fondo['t_usura_ea'] / 100;
            $tUsuraEMDecimal = pow(1 + $tUsuraEADecimal, 1/12) - 1;
            $fondo['t_usura_em'] = round($tUsuraEMDecimal * 100, 6);

            // T. USURA -2 (EM) = ((1+T. USURA -2 (EA)/100)^(1/12))-1 * 100
            // Convertir de % a decimal, aplicar fórmula, volver a %
            $tUsuraMenos2EADecimal = $fondo['t_usura_menos2_ea'] / 100;
            $tUsuraMenos2EMDecimal = pow(1 + $tUsuraMenos2EADecimal, 1/12) - 1;
            $fondo['t_usura_menos2_em'] = round($tUsuraMenos2EMDecimal * 100, 6);

            // T. USURA (DIA) = T. USURA (EA) / 365
            $fondo['t_usura_dia'] = round($fondo['t_usura_ea'] / 365, 6);

            $fondo['created_at'] = now();
            $fondo['updated_at'] = now();

            $id = DB::table('politicas_fondos')->insertGetId($fondo);

            $fondo['id'] = $id;

            return response()->json([
                'success' => true,
                'message' => 'Fondo creado exitosamente',
                'fondo' => $fondo
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el fondo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing fondo in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateFondo(Request $request)
    {
        try {
            $validated = $request->validate([
                'fondo.id' => 'required|integer',
                'fondo.nombre_fondo' => 'required|string|max:255',
                'fondo.descripcion' => 'nullable|string',
                'fondo.smlv' => 'required|numeric|min:0',
                'fondo.dias_mora_max' => 'required|integer|min:0',
                'fondo.plazo_max' => 'required|integer|min:0',
                'fondo.ta_min_ea' => 'required|numeric|min:0',
                'fondo.t_usura_ea' => 'required|numeric|min:0',
                'fondo.tasa_usura' => 'required|numeric|min:0',
                'fondo.costo_asegurabilidad_mes' => 'nullable|numeric|min:0',
                'fondo.descuento_max_saldo_total' => 'nullable|numeric|min:0',
                'fondo.descuento_max_saldo_capital' => 'nullable|numeric|min:0',
                'fondo.activo' => 'nullable|boolean',
            ]);

            $fondo = $validated['fondo'];
            $id = $fondo['id'];

            // Check if fondo exists
            $exists = DB::table('politicas_fondos')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->exists();

            if (!$exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fondo no encontrado'
                ], 404);
            }

            unset($fondo['id']);

            // Recalculate derived fields
            // SALDO MAX = SMLV × 90
            $fondo['saldo_max'] = round($fondo['smlv'] * 90, 2);

            // T.A MIN (EM) = ((1+T.A MIN (EA)/100)^(1/12))-1 * 100
            // Convertir de % a decimal, aplicar fórmula, volver a %
            $taMinEADecimal = $fondo['ta_min_ea'] / 100;
            $taMinEMDecimal = pow(1 + $taMinEADecimal, 1/12) - 1;
            $fondo['ta_min_em'] = round($taMinEMDecimal * 100, 6);

            // T. USURA -2 (EA) = T. USURA (EA) - 2
            $fondo['t_usura_menos2_ea'] = round($fondo['t_usura_ea'] - 2, 6);

            // T. USURA (EM) = ((1+T. USURA (EA)/100)^(1/12))-1 * 100
            // Convertir de % a decimal, aplicar fórmula, volver a %
            $tUsuraEADecimal = $fondo['t_usura_ea'] / 100;
            $tUsuraEMDecimal = pow(1 + $tUsuraEADecimal, 1/12) - 1;
            $fondo['t_usura_em'] = round($tUsuraEMDecimal * 100, 6);

            // T. USURA -2 (EM) = ((1+T. USURA -2 (EA)/100)^(1/12))-1 * 100
            // Convertir de % a decimal, aplicar fórmula, volver a %
            $tUsuraMenos2EADecimal = $fondo['t_usura_menos2_ea'] / 100;
            $tUsuraMenos2EMDecimal = pow(1 + $tUsuraMenos2EADecimal, 1/12) - 1;
            $fondo['t_usura_menos2_em'] = round($tUsuraMenos2EMDecimal * 100, 6);

            // T. USURA (DIA) = T. USURA (EA) / 365
            $fondo['t_usura_dia'] = round($fondo['t_usura_ea'] / 365, 6);

            $fondo['updated_at'] = now();

            DB::table('politicas_fondos')
                ->where('id', $id)
                ->update($fondo);

            $fondo['id'] = $id;

            return response()->json([
                'success' => true,
                'message' => 'Fondo actualizado exitosamente',
                'fondo' => $fondo
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el fondo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle the active status of a fondo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleActivoFondo(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer'
            ]);

            $id = $request->input('id');

            // Get current fondo
            $fondo = DB::table('politicas_fondos')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->first();

            if (!$fondo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fondo no encontrado'
                ], 404);
            }

            // Toggle activo status
            $newStatus = !$fondo->activo;
            DB::table('politicas_fondos')
                ->where('id', $id)
                ->update([
                    'activo' => $newStatus,
                    'updated_at' => now()
                ]);

            $status = $newStatus ? 'activado' : 'desactivado';

            return response()->json([
                'success' => true,
                'message' => "Fondo {$status} exitosamente"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a fondo from database (soft delete).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFondo(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|integer'
            ]);

            $id = $request->input('id');

            // Check if fondo exists
            $exists = DB::table('politicas_fondos')
                ->where('id', $id)
                ->whereNull('deleted_at')
                ->exists();

            if (!$exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fondo no encontrado'
                ], 404);
            }

            // Soft delete
            DB::table('politicas_fondos')
                ->where('id', $id)
                ->update([
                    'deleted_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Fondo eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el fondo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export all fondos to JSON file (useful for backup/debugging).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function exportJsonFondos()
    {
        $fondos = session('politicas_fondo', []);

        return response()->json([
            'success' => true,
            'data' => array_values($fondos),
            'count' => count($fondos),
            'exported_at' => now()->toIso8601String()
        ]);
    }

    /**
     * Import fondos from JSON (useful for restoring data).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function importJsonFondos(Request $request)
    {
        try {
            $validated = $request->validate([
                'fondos' => 'required|array',
                'fondos.*.nombre_fondo' => 'required|string',
                'fondos.*.saldo_max' => 'required|numeric',
                'fondos.*.dias_mora_max' => 'required|integer',
                'fondos.*.plazo_max' => 'required|integer',
                'fondos.*.ta_min_ea' => 'required|numeric',
                'fondos.*.t_usura_ea' => 'required|numeric',
                'fondos.*.tasa_usura' => 'required|numeric',
            ]);

            $fondos = [];
            foreach ($validated['fondos'] as $fondo) {
                $id = $fondo['id'] ?? Str::uuid()->toString();
                $fondo['id'] = $id;
                $fondo['created_at'] = $fondo['created_at'] ?? now()->toIso8601String();
                $fondo['updated_at'] = now()->toIso8601String();
                $fondo['activo'] = $fondo['activo'] ?? true;

                // Recalculate derived fields
                $fondo['ta_min_em'] = round($fondo['ta_min_ea'] / 12, 6);
                $fondo['t_usura_menos2_ea'] = round($fondo['t_usura_ea'] - 2, 6);
                $fondo['t_usura_em'] = round($fondo['t_usura_ea'] / 12, 6);
                $fondo['t_usura_menos2_em'] = round($fondo['t_usura_menos2_ea'] / 12, 6);
                $fondo['t_usura_dia'] = round($fondo['t_usura_ea'] / 365, 6);

                $fondos[$id] = $fondo;
            }

            session(['politicas_fondo' => $fondos]);

            return response()->json([
                'success' => true,
                'message' => 'Fondos importados exitosamente',
                'count' => count($fondos)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al importar fondos: ' . $e->getMessage()
            ], 500);
        }
    }
}
