<?php

namespace App\Http\Controllers;

use App\Comercial;
use App\Empresa;
use App\Role;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class AreaComercialController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Set query
        $query = Comercial::orderBy('id', 'DESC');

        // Filter by company
        if (IsCompany()) {
            $query->where('empresa_id', $user->empresa->id);
        }

        // Pagination
        $comerciales = $query->paginate(request()->query('per_page') ?? 5)->appends(request()->query());

        // Map data
        $data = $comerciales->map(function ($comercial) {
            return [
                'id' => $comercial->id,
                'nombre_completo' => $comercial->nombre_completo,
                'cargo' => $comercial->cargo->cargo,
                'sede' => $comercial->sede->nombre,
                'ciudad' => $comercial->sede->ciudad->nombre,
                'telefono' => $comercial->numero_contacto
            ];
        });

        $comerciales->setCollection($data);

        return view('area-comerciales.index', [
            'comerciales' => json_encode($comerciales)
        ]);
    }

    public function crear()
    {
        $user = Auth::user();

        return view('area-comerciales.crear', [
            'user' => json_encode($user),
        ]);
    }

    public function store(Request $request)
    {
        $empresaRequest = json_decode($request->empresa);
        $personalRequest = json_decode($request->personal);
        $plataformaRequest = json_decode($request->plataforma);

        $permisos = $personalRequest->permisos;
        $permisosIds = collect($permisos)->pluck('id');

        $rolComercial = Role::where('name', 'COMERCIAL')->first();
        if (!$rolComercial) {
            throw new Exception("Role 'COMERCIAL' does not exist");
        }

        try {
            DB::beginTransaction();

            $usuario = User::create([
                'role_id' => $rolComercial->id,
                'name' => $personalRequest->nombre_apellido,
                'email' => $personalRequest->correo_contacto,
                'password' => Hash::make($personalRequest->contrasena),
            ]);

            // Asignar permisos
            foreach ($permisosIds as $permisoId) {
                $usuario->givePermission($permisoId);
            }

            $areaComercial = Comercial::create([
                'user_id' => $usuario->id,
                'empresa_id' => $request->empresa_id ?? Auth::user()->empresa->id,
                'sede_id' => $empresaRequest->sede_id,
                'cargo_id' => $empresaRequest->cargo_id,
                'tipo_documento_id' => $personalRequest->tipo_documento_id,
                'ami_id' => $plataformaRequest->ami_id,
                'hego_id' => $plataformaRequest->hego_id,
                'consultas_diarias' => $request->consultas_diarias,
                'nombre_completo' => $personalRequest->nombre_apellido,
                'numero_documento' => $personalRequest->numero_documento,
                'nacionalidad' => $personalRequest->nacionalidad,
                'correo' => $personalRequest->correo_contacto,
                'numero_contacto' => $personalRequest->numero_contacto,
                'src_documento_identidad' => '',
            ]);

            if ($request->hasFile('src_documento_identidad')) {
                $documentoIdentidadFile = $request->file('src_documento_identidad');
                $extension = $documentoIdentidadFile->getClientOriginalExtension();
                $documentoIdentidadPath = 'area-comerciales/' . $areaComercial->id . '/';
                $fileName = 'documento_identidad.' . $extension;
                $documentoIdentidadUpload = Storage::disk('archivos')->put($documentoIdentidadPath . $fileName, file_get_contents($documentoIdentidadFile));

                if ($documentoIdentidadUpload) {
                    $areaComercial->update(['src_documento_identidad' => $documentoIdentidadPath . $fileName]);
                }
            }

            DB::commit();
            return response()->json(['status' => 200]);
        } catch (Throwable $e) {
            DB::rollBack();
            $message = $e->getMessage() . ' in line ' . $e->getLine() . ' in file ' . $e->getFile();
            logger(['e' => $message]);
            return response()->json(['message' => $message], 500);
        }
    }

    public function getFilePreview($src)
    {
        try {
            $storage = Storage::disk('archivos');

            if (!$storage->exists($src)) {
                return null;
            }

            $file = $storage->get($src);
            return base64_encode($file);
        } catch (\Exception $e) {
            Log::error("Error al obtener el archivo: {$src}", ['exception' => $e->getMessage()]);
            return null;
        }
    }

    public function show($id)
    {
        try {
            $areaComercial = Comercial::with([
                'user.directPermissions',
                'user.role'
            ])->findOrfail($id);

            $areaComercial->ciudad_id = $areaComercial->sede->ciudad_id;
            $areaComercial->src_documento_identidad = $this->getFilePreview($areaComercial->src_documento_identidad);

            return view('area-comerciales.show', [
                'comercial' => json_encode($areaComercial),
                'usuarioComercial' => json_encode($areaComercial->user)
            ]);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        try {
            $areaComercial = Comercial::with([
                'user.directPermissions',
                'user.role'
            ])->findOrfail($id);

            $areaComercial->ciudad_id = $areaComercial->sede->ciudad_id;
            $areaComercial->src_documento_identidad = $this->getFilePreview($areaComercial->src_documento_identidad);

            return view('area-comerciales.edit', [
                'comercial' => json_encode($areaComercial),
                'usuarioComercial' => json_encode($areaComercial->user)
            ]);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $empresaRequest = json_decode($request->empresa);
        $personalRequest = json_decode($request->personal);
        $plataformaRequest = json_decode($request->plataforma);

        $permisos = $personalRequest->permisos ?? [];
        $permisosIds = collect($permisos)->pluck('id');

        try {
            DB::beginTransaction();

            $areaComercial = Comercial::findOrFail($id);
            $empresa = Empresa::findOrFail($areaComercial->empresa_id);
            $usuario = User::findOrFail($areaComercial->user_id);

            if ($usuario) {
                $usuario->update([
                    'name' => $personalRequest->nombre_apellido,
                    'email' => $personalRequest->correo_contacto,
                ]);

                // Asignar permisos
                $usuario->syncPermissions($permisosIds);
            }

            $areaComercial->update([
                'empresa_id' => $request->empresa_id,
                'sede_id' => $empresaRequest->sede_id,
                'cargo_id' => $empresaRequest->cargo_id,
                'tipo_documento_id' => $personalRequest->tipo_documento_id,
                'ami_id' => $plataformaRequest->ami_id,
                'hego_id' => $plataformaRequest->hego_id,
                'consultas_diarias' => $request->consultas_diarias,
                'nombre_completo' => $personalRequest->nombre_apellido,
                'numero_documento' => $personalRequest->numero_documento,
                'nacionalidad' => $personalRequest->nacionalidad,
                'correo' => $personalRequest->correo_contacto,
                'numero_contacto' => $personalRequest->numero_contacto,
            ]);

            $documentos = [
                'src_documento_identidad' => 'documento_identidad',
                'src_camara_comercio' => 'camara_comercio',
                'src_rut' => 'rut',
            ];

            foreach ($documentos as $campo => $nombreArchivo) {
                if ($request->hasFile($campo)) {
                    $file = $request->file($campo);
                    $extension = $file->getClientOriginalExtension();
                    $path = 'area-comerciales/' . $empresa->id . '/';
                    $fileName = $nombreArchivo . '.' . $extension;
                    $uploadSuccess = Storage::disk('archivos')->put($path . $fileName, file_get_contents($file));

                    if ($uploadSuccess) {
                        $areaComercial->update([$campo => $path . $fileName]);
                    }
                }
            }

            DB::commit();
            return response()->json(['status' => 200]);
        } catch (Throwable $e) {
            DB::rollBack();
            $message = $e->getMessage() . ' in line ' . $e->getLine() . ' in file ' . $e->getFile();
            logger(['e' => $message]);
            return response()->json(['message' => $message], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $comercial = Comercial::findOrFail($id);

            $comercial->delete();

            DB::commit();
            return response()->json(['status' => 200]);
        } catch (Throwable $e) {
            DB::rollBack();
            $message = $e->getMessage() . ' in line ' . $e->getLine() . ' in file ' . $e->getFile();
            return response()->json(['message' => $message], 500);
        }
    }
}
