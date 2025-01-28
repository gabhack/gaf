<?php

namespace App\Http\Controllers\Fintra;

use App\Http\Controllers\Controller;
use App\CreditRequest;
use App\CreditCartera;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreditRequestController extends Controller
{
    public function store(Request $request)
    {
        // Validaciones mínimas
        $request->validate([
            'doc'         => 'required|string|max:20',
            'name'        => 'required|string|max:255',
            'client_type' => 'required|string|max:50',
            'pagaduria_id' => 'required|integer',
            'cuota'       => 'required|numeric|min:0',
            'monto'       => 'required|numeric|min:0',
            'tasa'        => 'required|numeric|min:0',
            'plazo'       => 'required|integer|min:1',
            // 'carteras' => array con {valor_cuota, saldo}
        ]);

        try {
            DB::beginTransaction();

            // Crear registro de solicitud
            $credit = new CreditRequest();
            $credit->doc         = $request->input('doc');
            $credit->name        = $request->input('name');
            $credit->client_type = $request->input('client_type');
            $credit->pagaduria_id= $request->input('pagaduria_id');  // Guardamos entero
            $credit->cuota       = $request->input('cuota');
            $credit->monto       = $request->input('monto');
            $credit->tasa        = $request->input('tasa');
            $credit->plazo       = $request->input('plazo');
            $credit->save();

            // Guardar carteras asociadas (si llegan)
            if ($request->has('carteras') && is_array($request->carteras)) {
                foreach ($request->carteras as $carteraItem) {
                    $cartera = new CreditCartera();
                    $cartera->credit_request_id = $credit->id;
                    $cartera->valor_cuota = $carteraItem['valor_cuota'] ?? 0;
                    $cartera->saldo       = $carteraItem['saldo'] ?? 0;
                    $cartera->save();
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Crédito guardado exitosamente.',
                'data'    => $credit->load('carteras'),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            // Registrar el error en el log
            Log::error('Error al guardar el crédito', [
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'message' => 'Error al guardar el crédito',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function index()
    {
        // Simplemente retorna la vista Blade que contendrá
        // el componente Vue. El componente se encargará
        // de obtener la información vía AJAX o la misma vista
        // puede inyectar datos.
        return view('CreditRequest.index');
    }

    /**
     * Retorna la lista de créditos en formato JSON (para Vue)
     */
    public function getAll(Request $request)
    {
        $credits = CreditRequest::orderBy('id', 'desc')->get();
        return response()->json($credits);
    }

    /**
     * Actualiza el estado de un crédito a 'aprobado'
     */
    public function updateStatus($id, Request $request)
    {
        $credit = CreditRequest::findOrFail($id);
        $credit->status = 'aprobado';
        $credit->save();

        return response()->json([
            'message' => 'Estado actualizado a aprobado.',
            'data'    => $credit
        ]);
    }
}
