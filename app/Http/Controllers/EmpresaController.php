<?php

namespace App\Http\Controllers;

use App\DocumentoEmpresa;
use App\Empresa;
use App\RepresentanteLegalEmpresa;
use App\Role;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::orderBy('id', 'DESC')
            ->paginate(request()->query('per_page') ?? 5)
            ->appends(request()->query());

        $data = [];

        foreach ($empresas as $empresa) {
            array_push($data, [
                'id' => $empresa->id,
                'tipo_empresa' => $empresa->tipo_empresa->nombre,
                'nombre' => $empresa->nombre,
                'documento' => $empresa->numero_documento,
                'ciudad_id' => $empresa->ciudad_id
            ]);
        }

        $empresas->setCollection(collect($data));

        return view('empresas.index', ['empresas' => json_encode($empresas)]);
    }

    public function crear()
    {
        return view('empresas.crear');
    }

    public function store(Request $request)
    {
        $empresaRequest = json_decode($request->empresa);
        $representanteLegalRequest = json_decode($request->representante_legal);
        $documentacionRequest = json_decode($request->documentacion);
        $empresaUsuario = json_decode($request->usuario);

        $permisos = $empresaUsuario->permisos;
        $permisosIds = collect($permisos)->pluck('id');

        $rolEmpresa = Role::where('name', 'EMPRESA')->first();
        if (!$rolEmpresa) {
            throw new Exception("Role 'EMPRESA' does not exist.");
        }

        try {
            DB::beginTransaction();

            $usuario = User::create([
                'role_id' => $rolEmpresa->id,
                'name' => $empresaUsuario->nombre,
                'email' => $empresaUsuario->correo,
                'password' => Hash::make($empresaUsuario->contrasena),
            ]);

            // Asignar permisos
            foreach ($permisosIds as $permisoId) {
                $usuario->givePermission($permisoId);
            }

            $empresa = Empresa::create([
                'user_id' => $usuario->id,
                'tipo_sociedad_id' => $empresaRequest->tipo_sociedad_id,
                'tipo_empresa_id' => $request->tipo_empresa_id,
                'tipo_documento_id' => $empresaRequest->tipo_documento_id,
                'consultas_diarias' => $request->consultas_diarias,
                'nombre' => $empresaRequest->nombre,
                'numero_documento' => $empresaRequest->numero_documento,
                'correo' => $empresaRequest->correo,
                'pagina_web' => $empresaRequest->pagina_web,
                'pais_id' => $empresaRequest->pais_id,
                'departamento_id' => $empresaRequest->departamento_id,
                'ciudad_id' => $empresaRequest->ciudad_id,
                'direccion' => $empresaRequest->direccion,
            ]);

            RepresentanteLegalEmpresa::create([
                'empresa_id' => $empresa->id,
                'tipo_documento_id' => $representanteLegalRequest->tipo_documento_id,
                'nombres_completos' => $representanteLegalRequest->nombres_completos,
                'numero_documento' => $representanteLegalRequest->numero_documento,
                'nacionalidad' => $representanteLegalRequest->nacionalidad,
                'correo' => $representanteLegalRequest->correo,
                'numero_contacto' => $representanteLegalRequest->numero_contacto,
            ]);

            $documentoEmpresa = DocumentoEmpresa::create([
                'empresa_id' => $empresa->id,
                'iva' => $documentacionRequest->iva,
                'contribuyente' => $documentacionRequest->contribuyente,
                'autoretenedor' => $documentacionRequest->autoretenedor,
                'src_representante_legal' => '',
                'src_camara_comercio' => '',
                'src_rut' => '',
            ]);

            if ($request->hasFile('src_representante_legal')) {
                $representanteLegalFile = $request->file('src_representante_legal');
                $extension = $representanteLegalFile->getClientOriginalExtension();
                $representanteLegalPath = 'empresas/' . $empresa->id . '/';
                $fileName = 'representante_legal.' . $extension;
                $representanteLegalDocUpload = Storage::disk('archivos')->put($representanteLegalPath . $fileName, file_get_contents($representanteLegalFile));

                if ($representanteLegalDocUpload) {
                    $documentoEmpresa->update(['src_representante_legal' => $representanteLegalPath . $fileName]);
                }
            }

            if ($request->hasFile('src_camara_comercio')) {
                $camaraComercioFile = $request->file('src_camara_comercio');
                $extension = $camaraComercioFile->getClientOriginalExtension();
                $camaraComercioPath = 'empresas/' . $empresa->id . '/';
                $fileName = 'camara_comercio.' . $extension;
                $camaraComercioDocUpload = Storage::disk('archivos')->put($camaraComercioPath . $fileName, file_get_contents($camaraComercioFile));

                if ($camaraComercioDocUpload) {
                    $documentoEmpresa->update(['src_camara_comercio' => $camaraComercioPath . $fileName]);
                }
            }

            if ($request->hasFile('src_rut')) {
                $rutFile = $request->file('src_rut');
                $extension = $rutFile->getClientOriginalExtension();
                $rutDocPath = 'empresas/' . $empresa->id . '/';
                $fileName = 'rut.' . $extension;
                $rutDocPathUpload = Storage::disk('archivos')->put($rutDocPath . $fileName, file_get_contents($rutFile));

                if ($rutDocPathUpload) {
                    $documentoEmpresa->update(['src_rut' => $rutDocPath . $fileName]);
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

    public function show($id) {
        try {
            $empresa = Empresa::with([
                'documento_empresa',
                'representante_legal_empresa',
                'user.directPermissions'
            ])->findOrFail($id);

            if ($empresa->documento_empresa) {
                $documento = $empresa->documento_empresa;

                $documento->src_representante_legal = $this->getFilePreview($documento->src_representante_legal);
                $documento->src_camara_comercio = $this->getFilePreview($documento->src_camara_comercio);
                $documento->src_rut = $this->getFilePreview($documento->src_rut);
            }

            return view('empresas.show', [
                'empresa' => json_encode($empresa),
                'representanteLegal' => json_encode($empresa->representante_legal_empresa),
                'documentoEmpresa' => json_encode($empresa->documento_empresa),
                'usuarioEmpresa' => json_encode($empresa->user)
            ]);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        try {
            $empresa = Empresa::with([
                'documento_empresa',
                'representante_legal_empresa',
                'user.directPermissions'
            ])->findOrFail($id);

            if ($empresa->documento_empresa) {
                $documento = $empresa->documento_empresa;

                $documento->src_representante_legal = $this->getFilePreview($documento->src_representante_legal);
                $documento->src_camara_comercio = $this->getFilePreview($documento->src_camara_comercio);
                $documento->src_rut = $this->getFilePreview($documento->src_rut);
            }

            return view('empresas.edit', [
                'empresa' => json_encode($empresa),
                'representanteLegal' => json_encode($empresa->representante_legal_empresa),
                'documentoEmpresa' => json_encode($empresa->documento_empresa),
                'usuarioEmpresa' => json_encode($empresa->user)
            ]);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $empresaRequest = json_decode($request->empresa);
        $representanteLegalRequest = json_decode($request->representante_legal);
        $documentacionRequest = json_decode($request->documentacion);
        $empresaUsuario = json_decode($request->usuario);

        $permisos = $empresaUsuario->permisos ?? [];
        $permisosIds = collect($permisos)->pluck('id');

        try {
            DB::beginTransaction();

            $empresa = Empresa::findOrFail($id);
            $usuario = User::findOrFail($empresa->user_id);

            if ($usuario) {
                $usuario->update([
                    'name' => $empresaUsuario->nombre,
                    'email' => $empresaUsuario->correo,
                ]);

                // Asignar permisos
                $usuario->syncPermissions($permisosIds);
            }

            $empresa->update([
                'tipo_sociedad_id' => $empresaRequest->tipo_sociedad_id,
                'tipo_empresa_id' => $request->tipo_empresa_id,
                'tipo_documento_id' => $empresaRequest->tipo_documento_id,
                'consultas_diarias' => $request->consultas_diarias ?? 0,
                'nombre' => $empresaRequest->nombre,
                'numero_documento' => $empresaRequest->numero_documento,
                'correo' => $empresaRequest->correo,
                'pagina_web' => $empresaRequest->pagina_web,
                'pais_id' => $empresaRequest->pais_id,
                'departamento_id' => $empresaRequest->departamento_id,
                'ciudad_id' => $empresaRequest->ciudad_id,
                'direccion' => $empresaRequest->direccion,
            ]);

            RepresentanteLegalEmpresa::updateOrCreate(
                ['empresa_id' => $empresa->id],
                [
                    'tipo_documento_id' => $representanteLegalRequest->tipo_documento_id,
                    'nombres_completos' => $representanteLegalRequest->nombres_completos,
                    'numero_documento' => $representanteLegalRequest->numero_documento,
                    'nacionalidad' => $representanteLegalRequest->nacionalidad,
                    'correo' => $representanteLegalRequest->correo,
                    'numero_contacto' => $representanteLegalRequest->numero_contacto,
                ]
            );

            $documentoEmpresa = DocumentoEmpresa::updateOrCreate(
                ['empresa_id' => $empresa->id],
                [
                    'iva' => $documentacionRequest->iva,
                    'contribuyente' => $documentacionRequest->contribuyente,
                    'autoretenedor' => $documentacionRequest->autoretenedor,
                    'src_representante_legal' => '',
                    'src_camara_comercio' => '',
                    'src_rut' => '',
                ]
            );

            $documentos = [
                'src_representante_legal' => 'representante_legal',
                'src_camara_comercio' => 'camara_comercio',
                'src_rut' => 'rut',
            ];

            foreach ($documentos as $campo => $nombreArchivo) {
                if ($request->hasFile($campo)) {
                    $file = $request->file($campo);
                    $extension = $file->getClientOriginalExtension();
                    $path = 'empresas/' . $empresa->id . '/';
                    $fileName = $nombreArchivo . '.' . $extension;
                    $uploadSuccess = Storage::disk('archivos')->put($path . $fileName, file_get_contents($file));

                    if ($uploadSuccess) {
                        $documentoEmpresa->update([$campo => $path . $fileName]);
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

            $empresa = Empresa::findOrFail($id);

            $empresa->delete();

            DB::commit();
            return response()->json(['status' => 200]);
        } catch (Throwable $e) {
            DB::rollBack();
            $message = $e->getMessage() . ' in line ' . $e->getLine() . ' in file ' . $e->getFile();
            return response()->json(['message' => $message], 500);
        }
    }
}
