<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:ADMIN_SISTEMA,ADMIN_HEGO,ADMIN_AMI,COMPANY,CREAUSUARIOS');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin()) {
            $lista = \App\User::all();
        } else {
            if (IsCompany()) {
                $lista = \App\User::where('id_company', Auth::user()->id)
                    ->orWhere('id_padre', Auth::user()->id)->get();
            } else {
                $lista = \App\User::where('id_padre', Auth::user()->id)->get();
            }
        }

        return view("usuarios/index")->with(["lista" => $lista]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$roles = \App\Roles::OrderBy('rol')->get();
        return view('usuarios/crear')->with([
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $usuario = new \App\User;
		$usuario->name = $request->input('name');
		$usuario->email = $request->input('email');
		$usuario->password = bcrypt($request->input('password'));
        if (IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin()) {
		    $usuario->roles_id = $request->input('rol');
            if ($request->input('hego')) {
                $usuario->hego = 1;
            } else {
                $usuario->hego = NULL;
            }
        } else {
		    $usuario->roles_id =  \App\Roles::where('rol', 'USUARIO')->first()->id;
            $usuario->id_padre = Auth::user()->id;
            if ($request->input('ami_silver')) {
                $usuario->ami_silver = 1;
            } else {
                $usuario->ami_silver = NULL;
            }
            if ($request->input('ami_gold')) {
                $usuario->ami_gold = 1;
            } else {
                $usuario->ami_gold = NULL;
            }
            if ($request->input('ami_diamond')) {
                $usuario->ami_diamond = 1;
            } else {
                $usuario->ami_diamond = NULL;
            }
            if (IsCompany()) {
                $usuario->id_company = Auth::user()->id;
                if ($request->input('creausuarios')) {
                    $usuario->creausuarios = 1;
                } else {
                    $usuario->creausuarios = NULL;
                }
            } else {
                $usuario->id_company = Auth::user()->id_company;
            }
        }
        
		$usuario->save();
		
		return redirect('usuarios');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
		$roles = \App\Roles::OrderBy('rol')->get();
        $usuario = \App\User::find($id);
		return view("usuarios/editar")->with(["roles" => $roles, "usuario" => $usuario]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $usuario = \App\User::find($id);
		$usuario->name = strtoupper($request->input('name'));
		$usuario->email = $request->input('email');
		
		if($request->input('password') != "")
			$usuario->password = bcrypt($request->input('password'));
		
        if (IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin()) {
            if ($request->input('hego')) {
                $usuario->hego = 1;
            } else {
                $usuario->hego = NULL;
            }
        } else {
            if ($request->input('ami_silver')) {
                $usuario->ami_silver = 1;
            } else {
                $usuario->ami_silver = NULL;
            }
            if ($request->input('ami_gold')) {
                $usuario->ami_gold = 1;
            } else {
                $usuario->ami_gold = NULL;
            }
            if ($request->input('ami_diamond')) {
                $usuario->ami_diamond = 1;
            } else {
                $usuario->ami_diamond = NULL;
            }
        }

        if (IsCompany()) {
            if ($request->input('creausuarios')) {
                $usuario->creausuarios = 1;
            } else {
                $usuario->creausuarios = NULL;
            }
        }
        
		$usuario->save();
		
		return redirect('usuarios');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // \App\User::find($id)->forceDelete();
        \App\User::find($id)->Delete();
		return redirect('usuarios');
    }
	
	
	public function profile()
	{
		return view('usuarios/profile')->with('user', \Auth::user());
	}
	
	public function saveProfile(Request $request)
    {
		$email = \App\User::where( "email", $request->input("email") )
						->where("id", "<>", \Auth::user()->id)
						->count();
		if($email == 0){
			$user = \App\User::find(\Auth::user()->id);
			$user->name = $request->input("nombre");
			$user->email= $request->input("email");
			
			if(NULL != $request->input("password2"))
				$user->password = bcrypt( $request->input("password2") );
			
			$user->save();
			
			setMessage("Informaci&oacute;n modificada exitosamente", "success");
				
		}else{
			setMessage("Este correo ya se encuentra registrado", "info");
		}
			
		return redirect('profile');	
    }
	
}