<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function uploadComprobantes (Request $request) {
        $data = json_decode($request['data']);

        echo '<pre>';
        print_r($data);
        echo '<pre>';
    }
}
