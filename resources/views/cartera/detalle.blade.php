@extends('layouts.app2')

@section('title')
HEGO | Detalle Cartera
@endsection

@section('header-content')
Detalle Cartera
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ url('home') }}">
        <i class="fa fa-dashboard mr-2"></i>Inicio
    </a>
</li>
<li class="breadcrumb-item active">Detalle Cartera</li>
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
            <table border="0" cellspacing="1" cellpadding="2" align="center">
                <tbody><tr>
                    <td valign="top">
                        <h2>DATOS CLIENTE</h2>
                        <div class="clearfix"  style="background-color: #E7F2F8; padding: 20px; margin-right: 50px;">
                            <table border="1" cellspacing="1" cellpadding="2" align="right">

                                <tbody>
                                    <tr>
                                    <td>NO LIBRANZA</td>
                                    <td><input type="text" name="no_libranza" value="{{$cartera->id}}" style="width:200; background-color:#8DB4E3;" readonly=""></td>
                                </tr>
                                <tr>
                                    <td>NOMBRE</td>
                                    @if(is_object($cartera->estudio->datacotizer))
                                        <td><input type="text" name="nombre" value="{{$cartera->estudio->datacotizer->firstName.' '.$cartera->estudio->datacotizer->firstLastname}}" style="width:200;" readonly=""></td>
                                    @else
                                        <td><input type="text" name="nombre" style="width:200;" readonly=""></td>
                                    @endif
                                    </tr>
                                <tr>
                                    <td>NÚMERO DE CÉDULA</td>
                                    @if(is_object($cartera->estudio->datacotizer))
                                        <td><input type="text" name="cedula" value="{{$cartera->estudio->datacotizer->idNumber}}" style="width:200;" readonly=""></td>
                                    @else
                                        <td><input type="text" name="cedula" style="width:200;" readonly=""></td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>DIRECCIÓN</td>
                                    @if(is_object($cartera->estudio->datacotizer))
                                        <td><input type="text" name="direccion" value="{{$cartera->estudio->datacotizer->addressWork}}" style="width:200; background-color:#EAF1DD" readonly=""></td>
                                    @else
                                       <td> <input type="text" name="direccion" style="width:200;" readonly=""></td>
                                    @endif
                                    </tr>
                                <tr>
                                    <td>CIUDAD</td>
                                    
                                    @if(is_object($cartera->estudio->datacotizer))
                                        <td><input type="text" name="ciudad" value="{{$cartera->estudio->datacotizer->city}}" style="width:200; background-color:#EAF1DD" readonly=""></td>
                                    @else
                                       <td><input type="text" name="ciudad" style="width:200;" readonly=""></td>
                                    @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>TELÉFONO</td>
                                    @if(is_object($cartera->estudio->datacotizer))
                                        <td><input type="text" name="telefono" value="{{$cartera->estudio->datacotizer->phoneNumberFijo}}" style="width:200; background-color:#EAF1DD" readonly=""></td>
                                    @else
                                       <td> <input type="text" name="telefono" style="width:200;" readonly=""></td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>CELULAR</td>
                                    @if(is_object($cartera->estudio->datacotizer))
                                        <td><input type="text" name="movil" value="{{$cartera->estudio->datacotizer->phoneNumber}}" style="width:200; background-color:#EAF1DD" readonly=""></td>
                                    @else
                                       <td> <input type="text" name="movil" style="width:200;" readonly=""></td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>CORREO ELECTRÓNICO</td>
                                    @if(is_object($cartera->estudio->datacotizer))
                                        <td><input type="text" name="mail" value="{{$cartera->estudio->datacotizer->email}}" style="width:200; background-color:#EAF1DD" readonly=""></td>
                                    @else
                                       <td> <input type="text" name="mail" style="width:200;" readonly=""></td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>PAGADURÍA</td>
                                    @if(is_object($cartera->estudio->pagaduria))
                                        <td><input type="text" name="pagaduria" value="{{$cartera->estudio->pagaduria->pagaduria}}" style="width:200;" readonly=""></td>
                                    @else
                                       <td> <input type="text" name="pagaduria" style="width:200;" readonly=""></td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>FECHA ESTUDIO</td>
                                    <td><input type="text" name="fecha_estudio" value="{{$cartera->estudio->fecha}}" style="width:200;" readonly=""></td>
                                </tr>
                            </tbody></table>
                        </div>
                    </td>
                    <td>&nbsp;</td>
                    <td valign="top">
                        <h2>CRÉDITO</h2>
                        <div class="clearfix" style="background-color: #E7F2F8; padding: 20px; margin-right: 50px;">
                            <table border="0" cellspacing="1" cellpadding="2">
                                <tbody><tr>
                                    <td>SOLICITADO</td>
                                    @if(is_object($cartera->estudio->solicitudcredito))
                                        <td><input type="text" value="{{$cartera->estudio->solicitudcredito->valor_solicitado}}" size="15" style="text-align:right;" readonly=""></td>
                                    @else
                                        <td><input type="text" value="" size="15" style="text-align:right;" readonly=""></td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>PLAZO</td>
                                    <td><input type="text" value="" size="15" style="text-align:right;" readonly=""></td>
                                </tr>
                                <tr>
                                    <td>TASA DE INTERÉS DEL CRÉDITO</td>
                                    @if(is_object($cartera->estudio->solicitudcredito))
                                        <td><input type="text" value="{{$cartera->estudio->solicitudcredito->tasa_interes}}" size="15" style="text-align:right;" readonly=""></td>
                                    @else
                                        <td><input type="text" value="" size="15" style="text-align:right;" readonly=""></td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>CUOTA CORRIENTE</td>
                                    @if(is_object($cartera->estudio->solicitudcredito))
                                        <td><input type="text" value="{{$cartera->estudio->solicitudcredito->cuota_corriente}}" size="15" style="text-align:right;" readonly=""></td>
                                    @else
                                        <td><input type="text" value="" size="15" style="text-align:right;" readonly=""></td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>SEGURO DE VIDA</td>
                                    @if(is_object($cartera->estudio->solicitudcredito))
                                    <td><input type="text" value="{{$cartera->estudio->solicitudcredito->seguro}}" size="15" style="text-align:right;" readonly=""></td>
                                    @else
                                        <td><input type="text" value="" size="15" style="text-align:right;" readonly=""></td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>CUOTA TOTAL</td>
                                    @if(is_object($cartera->estudio->solicitudcredito))
                                        <td><input type="text" value="{{$cartera->estudio->solicitudcredito->cuota}}" size="15" style="text-align:right;" readonly=""></td>
                                    @else
                                        <td><input type="text" value="" size="15" style="text-align:right;" readonly=""></td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>VALOR CRÉDITO</td>
                                    @if(is_object($cartera->estudio->solicitudcredito))
                                    <td><input type="text" value="{{$cartera->estudio->solicitudcredito->credito_total}}" size="15" style="text-align:right;" readonly=""></td>
                                    @else
                                        <td><input type="text" value="" size="15" style="text-align:right;" readonly=""></td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>FECHA DESEMBOLSO</td>
                                    <td><input type="text" value="" size="15" style="text-align:center;" readonly=""></td>
                                </tr>
                                <tr>
                                    <td>FECHA PRIMERA CUOTA</td>
                                    <td><input type="text" value="" size="15" style="text-align:center; background-color:#EAF1DD;" readonly=""></td>
                                </tr>
                               
                            </tbody></table>
                        </div>
                    </td>
                        <td>&nbsp;</td>
                        <td valign="top">
                            <h2>VALORES HOY</h2>
                            <div class="clearfix" style="background-color: #E7F2F8; padding: 20px; margin-right: 50px;">
                                <table border="0" cellspacing="1" cellpadding="2">
                                    <tbody><tr>
                                        <td>SALDO CAPITAL</td>
                                        <td><input type="text" name="saldo_capital" value="" size="15" style="text-align:right;" readonly=""></td>
                                    </tr>
                                    <tr>
                                        <td>INTERESES CORRIENTES</td>
                                        <td><input type="text" name="interes_corrientes" value="" size="15" style="text-align:right;" readonly=""></td>
                                    </tr>
                                    <tr>
                                        <td>SEGURO DE VIDA</td>
                                        <td><input type="text" name="seguro_vida2" value="" size="15" style="text-align:right;" readonly=""></td>
                                    </tr>
                                    <tr>
                                        <td>CUOTAS CAUSADAS</td>
                                        <td><input type="text" name="cuotas_causadas" value="" size="15" style="text-align:right;" readonly=""></td>
                                    </tr>
                                    <tr>
                                        <td>CUOTAS PAGADAS</td>
                                        <td><input type="text" name="cuotas_pagadas" value="" size="15" style="text-align:right;" readonly=""></td>
                                    </tr>
                                    <tr>
                                        <td>CUOTA EN MORA</td>
                                        <td><input type="text" name="cuotas_mora" value="" size="15" style="text-align:right;" readonly=""></td>
                                    </tr>
                                    <tr>
                                        <td>TOTAL EN MORA</td>
                                        <td><input type="text" name="total_mora" value="" size="15" style="text-align:right;" readonly=""></td>
                                    </tr>
                                    <tr>
                                        <td>TOTAL A PAGAR</td>
                                        <td><input type="text" name="total_pagar" value="" size="14" style="text-align:right; font-weight:bold;" readonly=""></td>
                                    </tr>
                                    <!-- 004 -->
                                    <tr>
                                        <td>CALIFICACIÓN</td>
                                        <td><input type="text" name="calificacion" value="" size="14" style="text-align:center;" readonly=""></td>
                                    </tr>

                                </tbody></table>
                            </div>
                        </td>
                </tr>
            </tbody></table>
            <br>
            <br>
            <h2>VALORES RECAUDADOS</h2>
            <table class="tablee" border="0" cellspacing="1" cellpadding="2&quot;" align="center" class="tab1">
                <thead>
                    <tr>
                        <th>Cuota</th>
                        <th>F Cuota</th>
                        <th>F Recaudo</th>
                        <th>Valor Recaudado</th>
                        <th>Seguro</th>
                        <th>Interés</th>
                        <th>Capital</th>
                        <th>Tipo Recaudo</th>
                        <th>&nbsp;</th>       
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    </tr>
                </tbody>
            </table>
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