<?php

namespace App\Http\Controllers;

use Auth;
use \App\Consultas as Consultas;
use \App\User as User;
use Illuminate\Http\Request;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $labels = Array();
        $fechadesde = date("Y-m-1 00:00:00");
        $fechahasta = date("Y-m-d 23:59:59");

        setlocale(LC_ALL,"es_ES");
        $labels['mes_actual'] = GetLabelEspanolMeses(date("m"));

        if (IsUser()) {
            $user = Auth::user();

            $consultas = Consultas::orderBy('id', 'desc')
                ->where('users_id', Auth::user()->id)
                ->whereBetween('created_at', [$fechadesde, $fechahasta])->get();

            $labels['total_consultas'] = $consultas->count();
            $labels['consultas']['silver'] = $consultas->where('tipo_consulta', 1)->count();
            $labels['consultas']['gold'] = $consultas->where('tipo_consulta', 2)->count();
            $labels['consultas']['diamond'] = $consultas->where('tipo_consulta', 3)->count();

            if (IsUserCreator()) {
                $labels['usuarios_activos'] = count($user->usuarioshijos);
                foreach ($user->usuarioshijos as $key => $usuario) {
                    $consultas = Consultas::orderBy('id', 'desc')
                        ->where('users_id', $usuario->id)
                        ->whereBetween('created_at', [$fechadesde, $fechahasta])
                        ->get();

                    $labels['total_consultas'] += $consultas->count();
                    $labels['consultas']['silver'] += $consultas->where('tipo_consulta', 1)->count();
                    $labels['consultas']['gold'] += $consultas->where('tipo_consulta', 2)->count();
                    $labels['consultas']['diamond'] += $consultas->where('tipo_consulta', 3)->count();
                }
            }
        } elseif (IsCompany()) {
            $user = Auth::user();

            $consultas = Consultas::orderBy('id', 'desc')
                ->where('users_id', $user->id)
                ->whereBetween('created_at', [$fechadesde, $fechahasta])->get();

            $labels['total_consultas'] = $consultas->count();
            $labels['consultas']['silver'] = $consultas->where('tipo_consulta', 1)->count();
            $labels['consultas']['gold'] = $consultas->where('tipo_consulta', 2)->count();
            $labels['consultas']['diamond'] = $consultas->where('tipo_consulta', 3)->count();
            $labels['usuarios_activos'] = count($user->usuarioscompany);

            foreach ($user->usuarioscompany as $key => $usuario) {
                $consultas = Consultas::orderBy('id', 'desc')
                    ->where('users_id', $usuario->id)
                    ->whereBetween('created_at', [$fechadesde, $fechahasta])
                    ->get();

                $labels['total_consultas'] += $consultas->count();
                $labels['consultas']['silver'] += $consultas->where('tipo_consulta', 1)->count();
                $labels['consultas']['gold'] += $consultas->where('tipo_consulta', 2)->count();
                $labels['consultas']['diamond'] += $consultas->where('tipo_consulta', 3)->count();
            }
        } else {
            $consultas = Consultas::orderBy('id', 'desc')
                ->whereBetween('created_at', [$fechadesde, $fechahasta])->get();

            $labels['total_consultas'] = $consultas->count();
            $labels['usuarios_activos'] = User::all()->count();
            $labels['usuarios_nuevos'] = User::orderBy('id', 'desc')
                ->whereBetween('created_at', [$fechadesde, $fechahasta])
                ->count();
        }

        return view('home')->with([
            "labels" => $labels
        ]);
    }
}
