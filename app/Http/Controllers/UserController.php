<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Mantiene tu middleware de roles
        $this->middleware('role:ADMIN_SISTEMA,ADMIN_HEGO,ADMIN_AMI,COMPANY,CREAUSUARIOS');
    }

    public function index()
    {
        if (IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin()) {
            $lista = User::orderBy('id', 'desc')->get();
        } else {
            if (IsCompany()) {
                $lista = User::where('id_company', Auth::user()->id)
                    ->orWhere('id_padre', Auth::user()->id)
                    ->orderBy('id', 'desc')
                    ->get();
            } else {
                $lista = User::where('id_padre', Auth::user()->id)
                    ->orderBy('id', 'desc')
                    ->get();
            }
        }

        return view('usuarios/index', ['lista' => $lista]);
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('usuarios/crear', ['roles' => $roles]);
    }

    public function store(Request $request)
    {
        $baseRules = [
            'name'     => ['required', 'string', 'max:150'],
            'email'    => ['required', 'email', 'max:190', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ];

        if (IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin()) {
            $baseRules['role'] = ['required', Rule::exists('roles', 'id')];
        }

        $validated = $request->validate($baseRules);

        $usuario = new User();
        $usuario->name     = strtoupper($validated['name']);
        $usuario->email    = $validated['email'];
        $usuario->password = bcrypt($validated['password']);

        if (IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin()) {
            $usuario->role_id = (int) $request->input('role');
            $usuario->hego    = $request->boolean('hego') ? 1 : null;
        } else {
            $usuario->role_id  = Role::where('name', 'USUARIO')->value('id');
            $usuario->id_padre = Auth::id();

            $usuario->ami_silver  = $request->boolean('ami_silver') ? 1 : null;
            $usuario->ami_gold    = $request->boolean('ami_gold') ? 1 : null;
            $usuario->ami_diamond = $request->boolean('ami_diamond') ? 1 : null;

            if (IsCompany()) {
                $usuario->id_company   = Auth::id();
                $usuario->creausuarios = $request->boolean('creausuarios') ? 1 : null;
            } else {
                $usuario->id_company = Auth::user()->id_company;
            }
        }

        $usuario->save();

        return redirect('usuarios')->with('status', 'Usuario creado correctamente.');
    }

    public function show($id)
    {
        abort(404);
    }

    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        $roles   = Role::orderBy('name')->get();
        return view('usuarios/editar', ['roles' => $roles, 'usuario' => $usuario]);
    }

    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $rules = [
            'name'  => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:190', Rule::unique('users', 'email')->ignore($usuario->id)],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['string', 'min:6'];
        }

        if (IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin()) {
            $rules['role'] = ['required', Rule::exists('roles', 'id')];
        }

        $validated = $request->validate($rules);

        $usuario->name  = strtoupper($validated['name']);
        $usuario->email = $validated['email'];

        if ($request->filled('password')) {
            $usuario->password = bcrypt($request->input('password'));
        }

        if (IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin()) {
            $usuario->role_id = (int) $request->input('role');
            $usuario->hego    = $request->boolean('hego') ? 1 : null;
        } else {
            $usuario->ami_silver  = $request->boolean('ami_silver') ? 1 : null;
            $usuario->ami_gold    = $request->boolean('ami_gold') ? 1 : null;
            $usuario->ami_diamond = $request->boolean('ami_diamond') ? 1 : null;

            if (IsCompany()) {
                $usuario->creausuarios = $request->boolean('creausuarios') ? 1 : null;
            }
        }

        $usuario->save();

        return redirect('usuarios')->with('status', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete(); // Soft delete si el modelo lo tiene; si no, borra duro
        return redirect('usuarios')->with('status', 'Usuario eliminado correctamente.');
    }
}
