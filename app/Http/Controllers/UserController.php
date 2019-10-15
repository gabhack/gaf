<?php

namespace App\Http\Controllers;

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
        $this->middleware('superadmin');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lista = \App\User::all();
        return view("usuarios/index")->with(["lista" => $lista]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dptos = \App\Departamentos::all();
        $oficinas = \App\Oficinas::all();
		$roles = \App\Roles::OrderBy('rol')->get();
        return view('usuarios/crear')->with([
            'roles' => $roles, 
            'dptos' => $dptos,
            'oficinas' => $oficinas
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
		$usuario->roles_id = $request->input('rol');
		$usuario->oficinas_id = $request->input('oficina');
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
        $dptos = \App\Departamentos::all();
		$roles = \App\Roles::OrderBy('rol')->get();
        $usuario = \App\User::find($id);
		return view("usuarios/editar")->with(["roles" => $roles, "usuario" => $usuario, 'dptos' => $dptos]);
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
		
		$usuario->roles_id = $request->input('rol');
		$usuario->oficinas_id = $request->input('oficina');
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
        \App\User::find($id)->delete();		
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