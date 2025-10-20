<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
     * Get all politicas from session storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request)
    {
        $politicas = session('politicas_portafolio', []);

        return response()->json([
            'success' => true,
            'politicas' => array_values($politicas) // Re-index array
        ]);
    }

    /**
     * Store a new politica in session storage.
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
                'politica.porcentaje_portafolio' => 'required|numeric|min:0',
                'politica.porcentaje_comision_comercial' => 'required|numeric|min:0',
                'politica.porcentaje_reincorporacion_gaf' => 'required|numeric|min:0',
                'politica.porcentaje_coadministracion' => 'required|numeric|min:0',
                'politica.porcentaje_costo_seguro_vd' => 'required|numeric|min:0',
                'politica.activo' => 'nullable|boolean',
            ]);

            $politica = $validated['politica'];

            // Get existing politicas from session
            $politicas = session('politicas_portafolio', []);

            // Generate unique ID
            $politica['id'] = Str::uuid()->toString();
            $politica['created_at'] = now()->toIso8601String();
            $politica['updated_at'] = now()->toIso8601String();

            // Set default value for activo if not provided
            $politica['activo'] = $politica['activo'] ?? true;

            // Add new politica to array
            $politicas[$politica['id']] = $politica;

            // Save back to session
            session(['politicas_portafolio' => $politicas]);

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
     * Update an existing politica in session storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'politica.id' => 'required|string',
                'politica.nombre' => 'required|string|max:255',
                'politica.descripcion' => 'nullable|string',
                'politica.porcentaje_portafolio' => 'required|numeric|min:0',
                'politica.porcentaje_comision_comercial' => 'required|numeric|min:0',
                'politica.porcentaje_reincorporacion_gaf' => 'required|numeric|min:0',
                'politica.porcentaje_coadministracion' => 'required|numeric|min:0',
                'politica.porcentaje_costo_seguro_vd' => 'required|numeric|min:0',
                'politica.activo' => 'nullable|boolean',
            ]);

            $politica = $validated['politica'];
            $id = $politica['id'];

            // Get existing politicas from session
            $politicas = session('politicas_portafolio', []);

            if (!isset($politicas[$id])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Política no encontrada'
                ], 404);
            }

            // Preserve creation date
            $politica['created_at'] = $politicas[$id]['created_at'];
            $politica['updated_at'] = now()->toIso8601String();

            // Update politica
            $politicas[$id] = $politica;

            // Save back to session
            session(['politicas_portafolio' => $politicas]);

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
                'id' => 'required|string'
            ]);

            $id = $request->input('id');

            // Get existing politicas from session
            $politicas = session('politicas_portafolio', []);

            if (!isset($politicas[$id])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Política no encontrada'
                ], 404);
            }

            // Toggle activo status
            $politicas[$id]['activo'] = !$politicas[$id]['activo'];
            $politicas[$id]['updated_at'] = now()->toIso8601String();

            // Save back to session
            session(['politicas_portafolio' => $politicas]);

            $status = $politicas[$id]['activo'] ? 'activada' : 'desactivada';

            return response()->json([
                'success' => true,
                'message' => "Política {$status} exitosamente",
                'politica' => $politicas[$id]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a politica from session storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|string'
            ]);

            $id = $request->input('id');

            // Get existing politicas from session
            $politicas = session('politicas_portafolio', []);

            if (!isset($politicas[$id])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Política no encontrada'
                ], 404);
            }

            // Remove politica
            unset($politicas[$id]);

            // Save back to session
            session(['politicas_portafolio' => $politicas]);

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
     * Get all fondos from session storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFondos(Request $request)
    {
        $fondos = session('politicas_fondo', []);

        return response()->json([
            'success' => true,
            'fondos' => array_values($fondos)
        ]);
    }

    /**
     * Store a new fondo in session storage.
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
                'fondo.saldo_max' => 'required|numeric|min:0',
                'fondo.dias_mora_max' => 'required|integer|min:0',
                'fondo.plazo_max' => 'required|integer|min:0',
                'fondo.ta_min_ea' => 'required|numeric|min:0',
                'fondo.t_usura_ea' => 'required|numeric|min:0',
                'fondo.tasa_usura' => 'required|numeric|min:0',
                'fondo.activo' => 'nullable|boolean',
            ]);

            $fondo = $validated['fondo'];

            // Get existing fondos from session
            $fondos = session('politicas_fondo', []);

            // Generate unique ID
            $fondo['id'] = Str::uuid()->toString();
            $fondo['created_at'] = now()->toIso8601String();
            $fondo['updated_at'] = now()->toIso8601String();
            $fondo['activo'] = $fondo['activo'] ?? true;

            // Calculate derived fields (these should be calculated in frontend, but we ensure consistency)
            $fondo['ta_min_em'] = round($fondo['ta_min_ea'] / 12, 6);
            $fondo['t_usura_menos2_ea'] = round($fondo['t_usura_ea'] - 2, 6);
            $fondo['t_usura_em'] = round($fondo['t_usura_ea'] / 12, 6);
            $fondo['t_usura_menos2_em'] = round($fondo['t_usura_menos2_ea'] / 12, 6);
            $fondo['t_usura_dia'] = round($fondo['t_usura_ea'] / 365, 6);

            // Add new fondo to array
            $fondos[$fondo['id']] = $fondo;

            // Save back to session
            session(['politicas_fondo' => $fondos]);

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
     * Update an existing fondo in session storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateFondo(Request $request)
    {
        try {
            $validated = $request->validate([
                'fondo.id' => 'required|string',
                'fondo.nombre_fondo' => 'required|string|max:255',
                'fondo.descripcion' => 'nullable|string',
                'fondo.saldo_max' => 'required|numeric|min:0',
                'fondo.dias_mora_max' => 'required|integer|min:0',
                'fondo.plazo_max' => 'required|integer|min:0',
                'fondo.ta_min_ea' => 'required|numeric|min:0',
                'fondo.t_usura_ea' => 'required|numeric|min:0',
                'fondo.tasa_usura' => 'required|numeric|min:0',
                'fondo.activo' => 'nullable|boolean',
            ]);

            $fondo = $validated['fondo'];
            $id = $fondo['id'];

            // Get existing fondos from session
            $fondos = session('politicas_fondo', []);

            if (!isset($fondos[$id])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fondo no encontrado'
                ], 404);
            }

            // Preserve creation date
            $fondo['created_at'] = $fondos[$id]['created_at'];
            $fondo['updated_at'] = now()->toIso8601String();

            // Recalculate derived fields
            $fondo['ta_min_em'] = round($fondo['ta_min_ea'] / 12, 6);
            $fondo['t_usura_menos2_ea'] = round($fondo['t_usura_ea'] - 2, 6);
            $fondo['t_usura_em'] = round($fondo['t_usura_ea'] / 12, 6);
            $fondo['t_usura_menos2_em'] = round($fondo['t_usura_menos2_ea'] / 12, 6);
            $fondo['t_usura_dia'] = round($fondo['t_usura_ea'] / 365, 6);

            // Update fondo
            $fondos[$id] = $fondo;

            // Save back to session
            session(['politicas_fondo' => $fondos]);

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
                'id' => 'required|string'
            ]);

            $id = $request->input('id');

            // Get existing fondos from session
            $fondos = session('politicas_fondo', []);

            if (!isset($fondos[$id])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fondo no encontrado'
                ], 404);
            }

            // Toggle activo status
            $fondos[$id]['activo'] = !$fondos[$id]['activo'];
            $fondos[$id]['updated_at'] = now()->toIso8601String();

            // Save back to session
            session(['politicas_fondo' => $fondos]);

            $status = $fondos[$id]['activo'] ? 'activado' : 'desactivado';

            return response()->json([
                'success' => true,
                'message' => "Fondo {$status} exitosamente",
                'fondo' => $fondos[$id]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a fondo from session storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFondo(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|string'
            ]);

            $id = $request->input('id');

            // Get existing fondos from session
            $fondos = session('politicas_fondo', []);

            if (!isset($fondos[$id])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fondo no encontrado'
                ], 404);
            }

            // Remove fondo
            unset($fondos[$id]);

            // Save back to session
            session(['politicas_fondo' => $fondos]);

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
