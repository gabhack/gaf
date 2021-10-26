<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
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
