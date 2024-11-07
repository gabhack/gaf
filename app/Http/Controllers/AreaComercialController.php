<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AreaComercialController extends Controller
{
	public function index()
	{
		return view('area-comerciales.index');
	}

	public function crear()
	{
		return view('area-comerciales.crear');
	}
}
