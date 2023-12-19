<?php

namespace App\Http\Controllers;

use App\DescuentosSedAtlantico;
use App\DescuentosSedCauca;
use App\DescuentosSedCordoba;
use App\DescuentosSedChoco;
use App\DescuentosSedCaldas;
use App\DescuentosSedValle;
use App\DescuentosSemCali;
use App\DescuentosSemBarranquilla;
use App\DescuentosSemPopayan;
use App\DescuentosSemMonteria;
use App\DescuentosSemQuibdo;
use App\DescuentosGen;
use Illuminate\Http\Request;

class DescuentosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $doc = $request->doc;
        $descuentoType = $request->pagaduria;
        $pagaduriaLabel = $request->pagaduriaLabel;

        $models = [
            DescuentosSedAtlantico::class => 'doc',
            DescuentosSemMonteria::class => 'doc',
            DescuentosSemBarranquilla::class => 'doc',
            DescuentosSedCauca::class => 'doc',
            DescuentosSedCaldas::class => 'doc',
            DescuentosSedCordoba::class => 'doc',
            DescuentosSedChoco::class => 'doc',
            DescuentosSedValle::class => 'doc',
            DescuentosSemCali::class => 'doc',
            DescuentosSemPopayan::class => 'doc',
            DescuentosSemQuibdo::class => 'doc',
        ];

        $results = [];

        foreach ($models as $model => $column) {
            $className = class_basename($model);

            if ($className == $descuentoType) {
                $results = array_merge($results, $model::where($column, 'LIKE', '%' . $doc . '%')->get()->toArray());
            }
        }

        // General descuentos
        $dataGen = DescuentosGen::where('doc', 'LIKE', '%' . $doc . '%')
            ->where(function ($query) use ($descuentoType, $pagaduriaLabel) {
                $query->where('pagaduria', $descuentoType)
                    ->orWhere('pagaduria', $pagaduriaLabel);
            })->get()->toArray();

        $results = array_merge($results, $dataGen);

        return response()->json($results, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
