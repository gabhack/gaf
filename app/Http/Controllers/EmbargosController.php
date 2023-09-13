<?php

namespace App\Http\Controllers;

use App\EmbargosSemBarranquilla;
use App\EmbargosSedCauca;
use App\EmbargosSedAtlantico;
use App\EmbargosSedBolivar;
use App\EmbargosSedChoco;
use App\EmbargosSedCordoba;
use App\EmbargosSedValle;
use App\EmbargosSemCali;
use App\EmbargosSemPopayan;
use App\EmbargosSemMonteria;
use App\EmbargosSemQuibdo;
use Illuminate\Http\Request;

class EmbargosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $doc = $request->doc;
        $embargoType = $request->pagaduria;

        $models = [
            EmbargosSedCauca::class => 'doc',
            EmbargosSedChoco::class => 'doc',
            EmbargosSedCordoba::class => 'doc',
            EmbargosSemBarranquilla::class => 'doc',
            EmbargosSedAtlantico::class => 'doc',
            EmbargosSedValle::class => 'doc',
            EmbargosSemCali::class => 'doc',
            EmbargosSemPopayan::class => 'doc',
            EmbargosSemMonteria::class => 'doc',
            EmbargosSemQuibdo::class => 'idemp',
            EmbargosSedBolivar::class => 'idemp',
        ];

        $results = [];

        foreach ($models as $model => $column) {
            $className = class_basename($model);

            if ($className == $embargoType) {
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
