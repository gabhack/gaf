@extends('layouts.app2')

@section('title')
HEGO | Tesorería
@endsection

@section('header-content')
Tesorería
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ url('home') }}">
        <i class="fa fa-dashboard mr-2"></i>Inicio
    </a>
</li>
<li class="breadcrumb-item active">Tesorería</li>
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
                        <h2>DESEMBOLSO</h2>
                        <div class="clearfix" style="background-color: #E7F2F8; padding: 20px; margin-right: 100px;">
                        <table border="0" cellspacing="1" cellpadding="2" align="right">
                        <tbody><tr>
                            <td>NO LIBRANZA</td>
                            <td><input type="text" name="no_libranza" value="{{ $detalleTesoreria['numero_libranza'] }}" size="35" readonly=""></td>
                        </tr>
                        <tr>
                            <td>NOMBRE</td>
                            <td><input type="text" name="nombre" value="{{ $detalleTesoreria['nombre'] }} {{ $detalleTesoreria['apellido'] }}" size="35" readonly=""></td>
                        </tr>
                        <tr>
                            <td>NÚMERO DE CÉDULA</td>
                            <td><input type="text" name="cedula" value="{{ $detalleTesoreria['cedula'] }}" size="35" readonly=""></td>
                        </tr>
                        <tr>
                            <td>DIRECCIÓN</td>
                            <td><input type="text" name="direccion" value="{{ $detalleTesoreria['direccion'] }}" size="35" readonly=""></td>
                        </tr>
                        <tr>
                            <td>TELÉFONO</td>
                            <td><input type="text" name="telefono" value="{{ $detalleTesoreria['telefono'] }}" size="35" readonly=""></td>
                        </tr>
                        <tr>
                            <td>CORREO ELECTRÓNICO</td>
                            <td><input type="text" name="mail" value="{{ $detalleTesoreria['correo_electronico'] }}" size="35" readonly=""></td>
                        </tr>
                        <tr>
                            <td>PAGADURÍA</td>
                            <td><input type="text" name="pagaduria" value="{{ $detalleTesoreria['pagaduria'] }}" size="35" readonly=""></td>
                        </tr>
                        <tr>
                            <td>ESTADO</td>
                            <td><input type="text" name="estado" value="" size="35" readonly=""></td>
                        </tr>
                        <tr>
                            <td>FECHA</td>
                            <td><input type="text" name="fecha_tesoreria" value="{{ $detalleTesoreria['fecha'] }}" size="35" readonly=""></td>
                        </tr>
                        <tr>
                            <td>SOLICITADO</td>
                            <td><input type="text" name="opcion_desembolso" value="{{ $detalleTesoreria['solicitado'] }}" size="35" style="text-align:right;" readonly=""></td>
                        </tr>
                        <tr>
                            <td>PLAZO</td>
                            <td><input type="text" name="plazo" value="{{ $detalleTesoreria['plazo'] }}" size="35" style="text-align:right;" readonly=""></td>
                        </tr>
                        <tr>
                            <td>TASA DE INTERÉS DEL CRÉDITO</td>
                            <td><input type="text" name="tasa_interes" value="" size="35" style=" text-align:right;" readonly=""></td>
                        </tr>
                        <tr>
                            <td>CUOTA CORRIENTE</td>
                            <td><input type="text" name="cuota_corriente" value="" size="35" style="text-align:right;" readonly=""></td>
                        </tr>
                        <tr>
                            <td>SEGURO DE VIDA</td>
                            <td><input type="text" name="seguro_vida" value="" size="35" style="text-align:right;" readonly=""></td>
                        </tr>
                        <tr>
                            <td>CUOTA TOTAL</td>
                            <td><input type="text" name="opcion_cuota" value="{{ $detalleTesoreria['cuota_total'] }}" size="35" style="text-align:right;" readonly=""></td>
                        </tr>
                        </tbody></table>
                        </div>
                    </td>
                    <td>&nbsp;</td>
                    <td valign="top">
                        <h2>LIQUIDACIÓN</h2>
                        <div class="box1 clearfix" style="background-color: #10dd8e; padding: 20px; ">
                        <table border="0" cellspacing="1" cellpadding="2">
                        <tbody><tr>
                            <td>FECHA DESEMBOLSO</td>
                            <td><input type="text" name="fecha_desembolso" value="" size="15" style="text-align:center;" readonly=""></td>
                        </tr>
                        <tr>
                            <td>VALOR CRÉDITO</td>
                            <td><input type="text" name="valor_credito" value="{{ $detalleTesoreria['valor_credito'] }}" size="15" style="text-align:right;" readonly=""></td>
                        </tr>
                        <tr>
                            <td>- COMPRAS DE CARTERA</td>
                            <td><input type="text" name="compras_cartera" value="{{ $detalleTesoreria['compras_cartera'] }}" size="15" style="text-align:right;" readonly=""></td>
                        </tr>
                        <tr>
                            <td>- COSTOS TR</td>
                            <td><input type="text" name="descuento5_valor" value="" size="15" style="text-align:right;" readonly=""></td>
                        </tr>
                        <tr>
                            <td>- IVA COSTOS TR</td>
                            <td><input type="text" name="descuento6_valor" value="" size="15" style="text-align:right;" readonly=""></td>
                        </tr>
                        <tr>
                            <td>- INTERESES ANTICIPADOS</td>
                            <td><input type="text" name="intereses_anticipados" value="{{ $detalleTesoreria['intereses_anticipados'] }}" size="15" style="text-align:right;" readonly=""></td>
                        </tr>
                        <tr>
                            <td>- ASESORÍA FINANCIERA</td>
                            <td><input type="text" name="asesoria_financiera" value="" size="15" style="text-align:right;" readonly=""></td>
                        </tr>
                        <tr>
                            <td>- IVA</td>
                            <td><input type="text" name="iva" value="" size="15" style="text-align:right;" readonly=""></td>
                        </tr>
                        <tr>
                            <td>- GMF</td>
                            <td><input type="text" name="gmf" value="{{ $detalleTesoreria['gmf'] }}" size="15" style="text-align:right;" readonly=""></td>
                        </tr>
                        <tr>
                            <td>- TRANSFERENCIA</td>
                            <td><input type="text" name="transferencia" value="" size="15" style="text-align:right;" readonly=""></td>
                        </tr>
                        <tr>
                            <td>DESEMBOLSO CLIENTE</td>
                            <td><input type="text" name="desembolso_cliente" value="{{ $detalleTesoreria['desembolso_cliente'] }}" size="15" style="text-align:right;" readonly=""></td>
                        </tr>
                        <tr>
                            <td>- RETENCIÓN DE CUOTA</td>
                            <td><input type="text" name="retencion_cuota" value="" size="15" style="text-align:right;" readonly=""></td>
                        </tr>
                        <tr>
                            <td>- GIROS REALIZADOS</td>
                            <td><input type="text" name="giros_realizados" value="" size="15" style="text-align:right;" readonly=""></td>
                        </tr>
                        <tr>
                            <td>SALDO A GIRAR</td>
                            <td><input type="text" name="saldo_girar" value="{{ $detalleTesoreria['saldo_girar'] }}" size="15" style="text-align:right;" readonly=""></td>
                        </tr>
                        <tr><td colspan="2">&nbsp;</td></tr>
                        <tr>
                            <td>MES PROD</td>
                            <td><input type="text" name="mes_cartera" value="" size="15" style="text-align:center; background-color:#EAF1DD;" onchange="if(validarfechacorta(this.value)==false) {this.value=''; return false}"></td>
                        </tr>
                        </tbody></table>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
            <br>
            <br>
            <h2>COMPRAS DE CARTERA</h2>
            <table border="0" cellspacing="1" cellpadding="2&quot;" align="center" class="tab1">
                <thead>
                    <tr>
                        <th>Entidad</th>
                        <th>Cuota Retenida</th>
                        <th>Valor a Pagar</th>
                        <th>F Certificación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    @foreach($carteras as $cart)
                        <?php $total+=$cart->valor_ini; ?>
                        <tr>
                            <td><input type="text" id="entidad1" name="entidad1" value="{{ $cart->nombre_obligacion  }}" style="width:200;" readonly=""></td>
                            <td><input type="text" id="cuota_retenida1" name="cuota_retenida1" value="{{ $cart->cuota }}" size="15" onfocus="this.value = this.value.replace(/\,/g, '')" onblur="if(isnumber(this.value)==false) {this.value='0'; return false} else { if (this.value == '') { this.value = '0'; } separador_miles(this); }" style="text-align:right; background-color:#EAF1DD"></td>
                            <td><input type="text" id="valor_pagar1" name="valor_pagar1" value="{{ $cart->valor_ini }}" size="15" style="text-align:right;" readonly=""></td>
                            <td align="center"><input type="text" name="fecha_vencimiento1" value="{{ $cart->fecha_vence }}" size="10" style="text-align:center; background-color:#EAF1DD;" onchange="if (this.value != '') { if(validarfecha(this.value)==false) {this.value=''; return false} }"></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td align="center">&nbsp;</td>	<td align="right"><b>TOTAL</b></td>
                        <td><input type="text" name="total_cuota_retenida" value="{{ $total }}" size="15" style="text-align:right; font-weight:bold;" readonly=""></td>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                </tbody></table>
            <br>
            <br>
            <h2>GIROS</h2>
            <table border="0" cellspacing="1" cellpadding="2" class="tablee" align="center"width="95%">
                <thead>
                    <tr>
                        <th>Beneficiario</th>
                        <th>Nit</th>
                        <th>Forma Pago</th>
                        <th>Valor a Girar</th>
                        <th>Banco</th>
                        <th>Tipo Cuenta</th>
                        <th>Nro Cuenta</th>
                        <th>Nro Cheque</th>	<th>Clasificación</th>
                        <th>Cuenta Giro</th>	<th>F Giro</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($giros as $gir)
                        <tr>
                            <td>{{ $gir->beneficiario }}</td>
                            <td>{{ $gir->identificacion }}</td>
                            <td>{{ $gir->forma_pago }}</td>
                            <td>{{ $gir->valor_girar }}</td>
                            <td>{{ $gir->id_banco }}</td>
                            <td>{{ $gir->tipo_cuenta }}</td>
                            <td>{{ $gir->nro_cuenta }}</td>
                            <td>{{ $gir->nro_cheque }}</td>	
                            <td>{{ $gir->clasificacion }}</td>
                            <td>{{ $gir->referencia }}</td>	
                            <td>{{ $gir->fecha_giro }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
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