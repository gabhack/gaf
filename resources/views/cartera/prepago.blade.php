@extends('layouts.app2')

@section('title')
HEGO | Ingresar Prepago
@endsection

@section('header-content')
Ingresar Prepago
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ url('home') }}">
        <i class="fa fa-dashboard mr-2"></i>Inicio
    </a>
</li>
<li class="breadcrumb-item active">Ingresar Prepago</li>
@endsection

@section('content')

<div class="container-fluid" style="display: flex; justify-content: center;">
    <div class="row">

        <div class="col-md-12">
            <div class="btn-group mr-2 float-right" role="group">
                <a type="button" class="btn btn-secondary" href="{{url('estudios')}}"><i class="fa fa-arrow-left"></i> Atrás</a>
            </div>
        </div>

        <form>
            <table border="0" cellspacing="1" cellpadding="2" >
            <tbody><tr>
                <td>
                    <h2>DATOS PREGAGO</h2>
                    <div class="box1 clearfix">
                    <table border="0" cellspacing="1" cellpadding="2">
                    <tbody><tr>
                        <td>* Comprador</td>
                        <td>
                            <select name="tipo_recaudo" style="">
                                <option value=""></option>

                            </select>
                        </td>
                        <td width="20">&nbsp;</td>
                        <td>* F PREPAGO</td>
                        <td><input type="text" name="fecha" size="10" style="text-align:center; " ></td>
                        <td width="20">&nbsp;</td>
                        <td>* VR PREPAGADO</td>
                        <td><input type="text" name="fecha" size="10" style="text-align:center; " ></td>
                        
                        <td width="20">&nbsp;</td>
                        <td>SOPORTE</td>
                        <td><input type="file" name="archivo" style="text-align:center; "></td>
                    </tr>
                    </tbody></table>
                    </div>
                </td>
            </tr>
            </tbody></table>
            <br>
            <br>
            <button clasS="btn btn-primary" align="center">Ingresar</button>
            <p align="center">
            </p>
            </form>
        
    
    </div>
</div>
@endsection
