<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

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
use App\DatamesGen;
use App\Pagadurias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        Log::info('Entró a buscar la cédula', ['doc' => $doc]);
    
        $user      = Auth::user();
        $userType  = IsCompany() ? 'empresa' : (IsComercial() ? 'comercial' : null);
    
        if ($userType && $user->$userType->consultas_diarias <= 0) {
            Log::warning('Usuario sin consultas disponibles', ['user_id' => $user->id, 'tipo' => $userType]);
            return response()->json(['message' => 'No tienes consultas disponibles'], 400);
        }
    
        DB::beginTransaction();
    
        try {
            $results = [];
    
            $latestIds = DatamesGen::select('pagaduria', DB::raw('MAX(id) as id'))
                ->where('doc', $doc)
                ->groupBy('pagaduria')
                ->pluck('id', 'pagaduria');
    
            Log::info('Consultando DatamesGen', ['pagadurias_distintas' => $latestIds->count()]);
    
            if ($latestIds->isNotEmpty()) {
                $items = DatamesGen::whereIn('id', $latestIds->values())->get()->keyBy('pagaduria');
                foreach ($items as $pagaduria => $item) {
                    $results[$pagaduria] = $item;
                    Log::info('Resultado de DatamesGen', ['pagaduria' => $pagaduria, 'id' => $item->id]);
                }
            }
    
            if ($userType) {
                $user->$userType->decrement('consultas_diarias');
                Log::info('Consulta descontada para el usuario', ['user_id' => $user->id, 'tipo' => $userType]);
            }
    
            DB::commit();
    
            $payload = !empty($results) ? $results : (object) [];
            Log::info('Resultados finales', ['total_resultados' => count((array) $payload)]);
    
            return response()->json($payload, 200);
        } catch (\Throwable $e) {
            DB::rollBack();
    
            Log::error('Error al buscar pagadurías por documento', [
                'doc'       => $doc,
                'exception' => $e->getMessage(),
                'stack'     => $e->getTraceAsString(),
            ]);
    
            return response()->json([
                'error'   => 'Ocurrió un error al procesar la solicitud',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
    

    public function getPagaduriasNames()
    {
        $nombres = Pagadurias::pluck('pagaduria');
        return response()->json($nombres, 200);
    }

    public function getPagaduriasNamesAmi()
    {
        $nombres = [
            "SED AMAZONAS",
            "SED ANTIOQUIA",
            "SED ARAUCA",
            "SED ATLANTICO",
            "SED BOLIVAR",
            "SED BOYACA",
            "SED CALDAS",
            "SED CAQUETA",
            "SED CASANARE",
            "SED CAUCA",
            "SED CESAR",
            "SED CHOCO",
            "SED CORDOBA",
            "SED CUNDINAMARCA",
            "SED GUAJIRA",
            "SED GUAVIARE",
            "SED HUILA",
            "SED MAGDALENA",
            "SED META",
            "SED NARINO",
            "SED NORTE DE SANTANDER",
            "SED PUTUMAYO",
            "SED QUINDIO",
            "SED RISARALDA",
            "SED SANTANDER",
            "SED SINCELEJO",
            "SED SUCRE",
            "SED TOLIMA",
            "SED VALLE",
            "SED VAUPES",
            "SED VICHADA",
            "SEM APARTADO",
            "SEM ARMENIA",
            "SEM BARRANCABERMEJA",
            "SEM BARRANQUILLA",
            "SEM BELLO",
            "SEM BUCARAMANGA",
            "SEM BUENAVENTURA",
            "SEM BUGA",
            "SEM CALI",
            "SEM CARTAGENA",
            "SEM CARTAGO",
            "SEM CHIA",
            "SEM CIENAGA",
            "SEM CUCUTA",
            "SEM DOSQUEBRADAS",
            "SEM DUITAMA",
            "SEM ENVIGADO",
            "SEM FACATATIVA",
            "SEM FLORENCIA",
            "SEM FLORIDABLANCA",
            "SEM FUNZA",
            "SEM FUSAGASUGA",
            "SEM GIRARDOT",
            "SEM GIRON",
            "SEM GUAINIA",
            "SEM IBAGUE",
            "SEM IPIALES",
            "SEM ITAGUI",
            "SEM JAMUNDI",
            "SEM LORICA",
            "SEM MAGANGUE",
            "SEM MAICAO",
            "SEM MALAMBO",
            "SEM MANIZALES",
            "SEM MEDELLIN",
            "SEM MONTERIA",
            "SEM MOSQUERA",
            "SEM NEIVA",
            "SEM PALMIRA",
            "SEM PASTO",
            "SEM PEREIRA",
            "SEM PIEDECUESTA",
            "SEM PITALITO",
            "SEM POPAYAN",
            "SEM QUIBDO",
            "SEM RIOHACHA",
            "SEM RIONEGRO",
            "SEM SABANETA",
            "SEM SAHAGUN",
            "SEM SAN ANDRES",
            "SEM SANTA MARTA",
            "SEM SOACHA",
            "SEM SOGAMOSO",
            "SEM SOLEDAD",
            "SEM TULUA",
            "SEM TUMACO",
            "SEM TUNJA",
            "SEM TURBO",
            "SEM URIBIA",
            "SEM VALLEDUPAR",
            "SEM VILLAVICENCIO",
            "SEM YOPAL",
            "SEM YUMBO",
            "SEM ZIPAQUIRA",
            "casur",
            "fiduprevisora"
        ];

        return response()->json($nombres, 200);
    }

    public function getSituacionLaboralByDoc($doc)
    {
        try {
            Log::info("Consultando la situación laboral para el documento: {$doc}");

            $data = DatamesGen::where('doc', 'like', "%{$doc}%")
                ->latest('id')
                ->first(['situacion_laboral']);

            if (!$data) {
                Log::info("No se encontró la situación laboral para el documento proporcionado.", ['doc' => $doc]);
                return response()->json(['mensaje' => 'No se encontró la situación laboral para el documento proporcionado.'], 404);
            }

            Log::info("Situación laboral encontrada.", ['doc' => $doc, 'situacion_laboral' => $data->situacion_laboral]);

            return response()->json($data->situacion_laboral, 200);
        } catch (\Exception $e) {
            Log::error("Error al buscar la situación laboral por documento: {$doc}", [
                'doc' => $doc,
                'exception' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => 'Ocurrió un error al procesar la solicitud'], 500);
        }
    }

    public function getSituacionLaboralByDocs(Request $request)
    {
        try {
            $documentos = $request->input('documentos', []);
            Log::info("Consultando la situación laboral para múltiples documentos.");

            $situaciones = DatamesGen::whereIn('doc', $documentos)
                ->latest('id')
                ->get()
                ->keyBy('doc')  // Esto asume que 'doc' es único
                ->map(function ($item) {
                    return $item->situacion_laboral;
                });

            if ($situaciones->isEmpty()) {
                Log::info("No se encontraron situaciones laborales para los documentos proporcionados.");
                return response()->json(['mensaje' => 'No se encontraron situaciones laborales para los documentos proporcionados.'], 404);
            }

            Log::info("Situaciones laborales encontradas.", ['situaciones' => $situaciones]);

            return response()->json($situaciones, 200);
        } catch (\Exception $e) {
            Log::error("Error al buscar las situaciones laborales por documentos", [
                'documentos' => $documentos,
                'exception' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);

            return response()->json(['error' => 'Ocurrió un error al procesar la solicitud'], 500);
        }
    }
}
