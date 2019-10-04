<?php

namespace App\Http\Controllers;

use App\Clientes as Clientes;
use Illuminate\Http\Request;

class ConsultasController extends Controller
{
    /**
     * Display the consult resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("consultas/index");
    }

    /**
     * Display the consult by document.
     *
     * @return \Illuminate\Http\Response
     */
    public function consultar(Request $request)
    {
        $cliente = Clientes::where("documento", "=", $request->documento)->first();

        return view("consultas/consulta")->with([
            "cliente" => $cliente
        ]);
    }
}
