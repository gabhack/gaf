<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
