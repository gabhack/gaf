@extends('layouts.app2')

@section('title')
HEGO | Venta Cartera
@endsection

@section('header-content')
Venta Cartera
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ url('home') }}">
        <i class="fa fa-dashboard mr-2"></i>Inicio
    </a>
</li>
<li class="breadcrumb-item active">Venta Cartera</li>
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
            <table>
            <tbody><tr>
            <td>
            <div class="clearfix"  style="background-color: #E7F2F8; padding: 20px; margin-right: 50px">
            <table border="0" cellspacing="1" cellpadding="2">
            <tbody><tr>
                <td align="right">* No. Venta</td><td><input type="text" name="nro_venta" value="" size="6" style="text-align:center; background-color:#EAF1DD"></td>
                <td width="10">&nbsp;</td>
                <td align="right">* Fecha Venta </td><td><input type="text" name="fecha" value="" size="10" style="text-align:center; background-color:#EAF1DD"></td>
                <td width="10">&nbsp;</td>
                <td align="right">* Comprador </td><td>
                    <select name="id_comprador" style="width:155px; background-color:#EAF1DD">

                    </select>
                </td>
                <td width="10">&nbsp;</td>
                <td align="right">* Tasa Venta</td><td><input type="text" name="tasa_venta" value="" size="6" onchange="if(isnumber_punto(this.value)==false) {this.value='1.6000'; return false}" style="text-align:center; background-color:#EAF1DD"></td>
            </tr>
            </tbody></table>
            </div>
            </td>
            </tr>
            </tbody></table>
            <hr noshade="" size="1" width="350">

            <table border="0" cellspacing="1" cellpadding="2" class="tablee">
                <thead>
                    <tr>
                        <th>Cédula</th>
                        <th>Nombre</th>
                        <th>No. Libranza</th>
                        <th>Tasa</th>
                        <th>Cuota</th>
                        <th>Vr Crédito</th>
                        <th>Vr Capital</th>
                        <th>Pagaduría</th>
                        <th>Plazo</th>
                        <th>Estado</th>
                        <th>Vr Crédito<br>Comprador</th>
                        <th>F. Esperada<br>Pago</th>
                    </tr>
                </thead>
                </tbody>
                
                </tbody>
                <tfooter>
                    <tr class="tr_bold">
                        <td colspan="4">&nbsp;</td>
                        <td align="right"><b></b></td>
                        <td align="right"><b></b></td>
                        <td align="right"><b></b></td>
                        <td colspan="8">&nbsp;</td>
                    </tr>
                </tfooter>
            </table>
            <br>
            <p align="center"></p>
            </form>

        
    </div>
</div>
@endsection

@section('css')

    <style>
        .tablee, .tablee thead, .tablee thead tr th, .tablee tbody tr td{
            border: 1px solid black;
        }

        .tablee tbody{
            border: 1px solid black;
            font-size: smaller;
        }

    </style>
@endsection