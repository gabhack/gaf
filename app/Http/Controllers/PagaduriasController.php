<?php

namespace App\Http\Controllers;

use App\DataMes;
use App\DatamesSedCauca;
use App\DatamesSedChoco;
use App\Datamesfidu;
use App\Datamesseccali;
use App\Datamesseceduc;
use App\Pagadurias;
use Illuminate\Http\Request;

class PagaduriasController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('role:ADMIN_SISTEMA');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lista = Pagadurias::all();
        return view("pagadurias/index")->with(["lista" => $lista]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pagadurias/crear');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pagaduria = new Pagadurias;
        $pagaduria->codigo = strtoupper(str_replace(" ", "_", $request->input('pagaduria')));
        $pagaduria->pagaduria = strtoupper($request->input('pagaduria'));
        $pagaduria->save();

        return redirect('pagadurias');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pagaduria = Pagadurias::find($id);
        return view("pagadurias/editar")->with(["pagaduria" => $pagaduria]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pagaduria = Pagadurias::find($id);
        $pagaduria->pagaduria = strtoupper($request->input('pagaduria'));
        $pagaduria->save();

        return redirect('pagadurias');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Pagadurias::find($id)->delete();
        return redirect('pagadurias');
    }

    public function perDoc($doc)
    {
        $datames = DataMes::where('doc', $doc)->first();
        $datamesSedCauca = DatamesSedCauca::where('doc', $doc)->first();
        $datamesSedChoco = DatamesSedChoco::where('doc', $doc)->first();
        $datamesfidu = Datamesfidu::where('doc', $doc)->first();
        $datamesseccali = Datamesseccali::where('doc', $doc)->first();
        $datamesseceduc = Datamesseceduc::where('doc', $doc)->first();

        $results = [
            'datames' => $datames,
            'datamesSedCauca' => $datamesSedCauca,
            'datamesSedChoco' => $datamesSedChoco,
            'datamesfidu' => $datamesfidu,
            'datamesseccali' => $datamesseccali,
            'datamesseceduc' => $datamesseceduc,
        ];

        return response()->json($results, 200);
    }
}
