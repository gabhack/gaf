<?php

namespace App\Http\Controllers;

use App\Empresa;
use App\Sede;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SedeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        // Set query
        $query = Sede::orderBy('id', 'DESC');

        // Filter by company
        if (IsCompany()) {
            $query->where('empresa_id', $user->empresa->id);
        }

        // Pagination
        $perPage = (int) request()->query('per_page', 5);
        $sedes = $query->paginate($perPage)->appends(request()->query());

        // Map data
        $data = $sedes->map(function ($sede) {
            return [
                'id' => $sede->id,
                'empresa' => $sede->empresa->nombre,
                'departamento_id' => $sede->departamento_id,
                'ciudad_id' => $sede->ciudad_id,
                'nombre' => $sede->nombre,
            ];
        });

        $sedes->setCollection($data);

        return view('sedes.index', [
            'sedes' => json_encode($sedes),
            'user' => $user,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $user = Auth::user();
            $empresas = Empresa::orderBy('nombre', 'asc')->get();

            return view('sedes.create', [
                'empresas' => $empresas,
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Hubo un error al obtener las empresas',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'departamento_id' => 'required',
            'ciudad_id' => 'required',
            'nombre' => 'required',
        ]);

        try {
            $sede = Sede::create($validatedData);

            return response()->json([
                'message' => 'Sede creada correctamente',
                'sede' => $sede,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Hubo un error al crear la sede',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Sede  $sede
     * @return \Illuminate\Http\Response
     */
    public function show(Sede $sede)
    {
        try {
            $empresas = Empresa::orderBy('nombre', 'asc')->get();
            return view('sedes.show', compact('sede', 'empresas'));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Hubo un error al obtener la sede',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sede  $sede
     * @return \Illuminate\Http\Response
     */
    public function edit(Sede $sede)
    {
        try {
            $user = Auth::user();
            $empresas = Empresa::orderBy('nombre', 'asc')->get();

            return view('sedes.edit', [
                'sede' => $sede,
                'empresas' => $empresas,
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Hubo un error al obtener la sede',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Sede  $sede
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sede $sede)
    {
        $validatedData = $request->validate([
            'empresa_id' => 'required|exists:empresas,id',
            'departamento_id' => 'required',
            'ciudad_id' => 'required',
            'nombre' => 'required',
        ]);

        try {
            $sede->update($validatedData);

            return response()->json([
                'message' => 'Sede actualizada correctamente',
                'sede' => $sede,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Hubo un error al actualizar la sede',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sede  $sede
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sede $sede)
    {
        try {
            $sede->delete();

            return response()->json([
                'message' => 'Sede eliminada correctamente',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Hubo un error al eliminar la sede',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
