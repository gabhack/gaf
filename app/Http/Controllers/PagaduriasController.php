<?php

namespace App\Http\Controllers;

use App\DatamesSedCauca;
use App\DatamesSedValle;
use App\DatamesSemCali;
use App\DatamesSedAtlantico;
use App\DatamesSemBarranquilla;
use App\DatamesSedBolivar;
use App\DatamesSedChoco;
use App\DatamesFidu;
use App\DatamesFopep;
use App\DatamesSedAntioquia;
use App\DatamesSedArauca;
use App\DatamesSedBoyaca;
use App\DatamesSedCaldas;
use App\DatamesSedCasanare;
use App\DatamesSedCesar;
use App\DatamesSedCordoba;
use App\DatamesSedCundinamarca;
use App\DatamesSedGuajira;
use App\DatamesSedHuila;
use App\DatamesSedMagdalena;
use App\DatamesSedMeta;
use App\DatamesSedNarino;
use App\DatamesSedNorteSantander;
use App\DatamesSedRisaralda;
use App\DatamesSedSantander;
use App\DatamesSedSucre;
use App\DatamesSedTolima;
use App\DatamesSemBuga;
use App\DatamesSemCartagena;
use App\DatamesSemGirardot;
use App\DatamesSemIbague;
use App\DatamesSemIpiales;
use App\DatamesSemJamundi;
use App\DatamesSemMagangue;
use App\DatamesSemMedellin;
use App\DatamesSemMonteria;
use App\DatamesSemMosquera;
use App\DatamesSemNeiva;
use App\DatamesSemPalmira;
use App\DatamesSemPasto;
use App\DatamesSemPopayan;
use App\DatamesSemQuibdo;
use App\DatamesSemRioNegro;
use App\DatamesSemSabaneta;
use App\DatamesSemSahagun;
use App\DatamesSemSincelejo;
use App\DatamesSemSoledad;
use App\DatamesSemValledupar;
use App\DatamesSemYopal;
use App\DatamesSemYumbo;
use App\DatamesSemZipaquira;
use App\Pagadurias;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        return view('pagadurias/index')->with(['lista' => $lista]);
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
        $pagaduria = new Pagadurias();
        $pagaduria->codigo = strtoupper(str_replace(' ', '_', $request->input('pagaduria')));
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
        return view('pagadurias/editar')->with(['pagaduria' => $pagaduria]);
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
        $models = [
            DatamesFidu::class => 'doc',
            DatamesFopep::class => 'doc',
            DatamesSedAntioquia::class => 'doc',
            DatamesSedArauca::class => 'doc',
            DatamesSedAtlantico::class => 'doc',
            DatamesSedBolivar::class => 'doc',
            DatamesSedBoyaca::class => 'doc',
            DatamesSedCaldas::class => 'doc',
            DatamesSedCasanare::class => 'doc',
            DatamesSedCauca::class => 'doc',
            DatamesSedCesar::class => 'doc',
            DatamesSedChoco::class => 'doc',
            DatamesSedCordoba::class => 'doc',
            DatamesSedCundinamarca::class => 'doc',
            DatamesSedGuajira::class => 'doc',
            DatamesSedHuila::class => 'doc',
            DatamesSedMagdalena::class => 'codempleado',
            DatamesSedMeta::class => 'doc',
            DatamesSedNarino::class => 'doc',
            DatamesSedNorteSantander::class => 'doc',
            DatamesSedRisaralda::class => 'doc',
            DatamesSedSantander::class => 'doc',
            DatamesSedSucre::class => 'doc',
            DatamesSedTolima::class => 'doc',
            DatamesSedValle::class => 'doc',
            DatamesSemBarranquilla::class => 'doc',
            DatamesSemBuga::class => 'doc',
            DatamesSemCali::class => 'doc',
            DatamesSemCartagena::class => 'doc',
            DatamesSemGirardot::class => 'doc',
            DatamesSemIbague::class => 'doc',
            DatamesSemIpiales::class => 'doc',
            DatamesSemJamundi::class => 'doc',
            DatamesSemMagangue::class => 'doc',
            DatamesSemMedellin::class => 'doc',
            DatamesSemMonteria::class => 'doc',
            DatamesSemMosquera::class => 'doc',
            DatamesSemNeiva::class => 'doc',
            DatamesSemPalmira::class => 'doc',
            DatamesSemPasto::class => 'doc',
            DatamesSemPopayan::class => 'doc',
            DatamesSemQuibdo::class => 'doc',
            DatamesSemRioNegro::class => 'doc',
            DatamesSemSabaneta::class => 'doc',
            DatamesSemSahagun::class => 'codempleado',
            DatamesSemSincelejo::class => 'doc',
            DatamesSemSoledad::class => 'doc',
            DatamesSemValledupar::class => 'doc',
            DatamesSemYopal::class => 'doc',
            DatamesSemYumbo::class => 'doc',
            DatamesSemZipaquira::class => 'doc',
        ];

        $results = [];

        foreach ($models as $model => $column) {
            $data = $model::where($column, $doc)->first();

            if ($data) {
                $modelName = class_basename($model);
                $results[Str::camel($modelName)] = $data;
            }
        }

        $results = !empty($results) ? $results : (object)[];

        return response()->json($results, 200);
    }
}
