<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('reportes/index');
    }
	
	
	public function consultas()
	{
		$pagadurias = \App\Pagadurias::orderBy('pagaduria')->get();
		return view('reportes/consultas')->with(['pagadurias' => $pagadurias]);
	}
	
	
	public function personalizados()
	{
		$pagadurias = \App\Pagadurias::orderBy('pagaduria')->get();
		$departamentos = \App\Departamentos::orderBy('departamento')->get();
		return view('reportes/personalizados')->with(['pagadurias' => $pagadurias, 'departamentos' => $departamentos]);
	}
}
