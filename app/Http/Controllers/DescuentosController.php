<?php

namespace App\Http\Controllers;

use App\DescuentosSedAtlantico;
use App\DescuentosSedCauca;
use App\DescuentosSedCordoba;
use App\DescuentosSedChoco;
use App\DescuentosSedValle;
use App\DescuentosSemCali;
use App\DescuentosSemBarranquilla;
use App\DescuentosSemPopayan;
use App\DescuentosSemMonteria;
use App\DescuentosSemQuibdo;
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

        $models = [
            DescuentosSedAtlantico::class => 'doc',
            DescuentosSemMonteria::class => 'doc',
            DescuentosSemBarranquilla::class => 'doc',
            DescuentosSedCauca::class => 'doc',
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
                $results = $model::where($column, 'LIKE', '%' . $doc . '%')->get();
            }
        }

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
