@extends('layouts.app2')

@section('content')
<div class="container-fluid">
    <div>
        @if ($resultado)
        <div class="text-right mb-3">
            <download-pdf-button></download-pdf-button>
        </div>
        <div id="consulta-container" class="row">


            <!-- INFORMACION BASICA -->
            <div class="col-12">
                <div class="panel-table-title">
                    INFORMACION BASICA
                </div>
                <table class="table panel-table">
                    <tbody>
                        <tr class="text-center">
                            <td class="td-class">Tipo de documento</td>
                            <td>C.C</td>
                            <td class="td-class">Numero</td>
                            <td>{{ $resultado["JuridicaNacional"]["@attributes"]["identificacion"] }}</td>
                            <td class="td-class">Estado Documento</td>
                            <td>Vigente</td>
                            <td class="td-class">Lugar Expedicion</td>
                            <td colspan="2">PRADERA</td>
                            <td class="td-class">Fecha Expedicion</td>
                            <td>28/10/1985</td>
                        </tr>
                        <tr>
                            <td class="td-class">Nombre</td>
                            <td colspan="2">{{ $resultado["JuridicaNacional"]["@attributes"]["razonSocial"] }}</td>
                            <td class="td-class">Rango Edad</td>
                            <td>45-55</td>
                            <td class="td-class">Genero</td>
                            <td>Femenino</td>
                            <td class="td-class">Tiene RUT?</td>
                            <td>SI</td>
                            <td class="td-class">Antiguedad</td>
                            <td>54 Meses Urbana</td>
                        </tr>
                        <tr>
                            <td class="td-class">Actividad Economica</td>
                            <td colspan="2">Otro</td>
                            <td class="td-class">Empleador</td>
                            <td>-</td>
                            <td class="td-class">Tipo de contrato</td>
                            <td>-</td>
                            <td class="td-class">Fecha Contrato</td>
                            <td>28/10/1985</td>
                            <td class="td-class">Operaciones Internacionales</td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
                <!-- DETALLE SOCIO DEMOGRAFICO -->
                <table class="table panel-table">
                    <tbody>
                        <tr>
                            <td colspan="6" class="panel-table-subtitle">
                                Detalle Información Socio Demográfica
                            </td>
                        </tr>
                        <tr class="panel-table-foot text-center">
                            <td>Reportado Por</td>
                            <td>Fecha Reporte</td>
                            <td>Act. Económica</td>
                            <td>Empleador</td>
                            <td>Tipo Contrato</td>
                            <td>Fecha Contrato</td>
                        </tr>
                        <tr>
                            <td>COOPERATIVA DE CREDITO Y SERVICIO COMUNIDAD COOMUNIDAD</td>
                            <td>30/11/2021</td>
                            <td>Otro</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>COOPERATIVA DE CREDITO Y SERVICIO COMUNIDAD COOMUNIDAD</td>
                            <td>30/11/2021</td>
                            <td>Otro</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- SCORE
            @if (isset($resultado["Score"]["@attributes"]["puntaje"]))
            <div class="col-md-6">
                <div class="panel panel-primary mb-3">
                    <div class="panel-heading"><b>SCORE</b></div>
                    <div class="panel-body">
                        <div class="row">
                            @php $field = $resultado["Score"]["@attributes"]; @endphp
                            <div class="col-md-3">
                                <b class="panel-label table-text">TIPO:</b>
                                <div>
                                    <p class="panel-value">{{ $field["tipo"] ? $field["tipo"] : "-" }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <b class="panel-label table-text">PUNTAJE:</b>
                                <div>
                                    <p class="panel-value">{{ $field["puntaje"] ? $field["puntaje"] : "-" }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <b class="panel-label table-text">FECHA:</b>
                                <div>
                                    <p class="panel-value">{{ $field["fecha"] ? $field["fecha"] : "-" }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <b class="panel-label table-text">POBLACION:</b>
                                <div>
                                    <p class="panel-value">{{ $field["poblacion"] ? $field["poblacion"] : "-" }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif -->

            <!-- RESUMEN -->
            <div class="col-12">
                <div class="panel-table-title">
                    RESUMEN
                </div>
                <!-- Perfil general -->
                <table class="table panel-table">
                    <tbody>
                        <tr>
                            <td colspan="8" class="panel-table-subtitle">
                                Perfil general
                            </td>
                        </tr>
                        <tr class="panel-table-foot text-center">
                            <td>SECTORES</td>
                            <td>SECTOR FINANCIERO</td>
                            <td>SECTOR COOPERATIVO</td>
                            <td>SECTOR REAL</td>
                            <td>SECTOR TELCOS</td>
                            <td>TOTAL SECTORES</td>
                            <td>TOTAL COMO PRINCIPAL</td>
                            <td>TOTAL COMO CODEUDOR - OTROS</td>
                        </tr>
                        <tr>
                            <td>
                                CREDITOS VIGENTES
                            </td>
                            <td>1</td>
                            <td>0</td>
                            <td>1</td>
                            <td>1</td>
                            <td>0</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>
                                CREDITOS CERRADOS
                            </td>
                            <td>1</td>
                            <td>0</td>
                            <td>17</td>
                            <td>1</td>
                            <td>0</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>
                                CREDITOS RESTRUCTURADOS
                            </td>
                            <td>1</td>
                            <td>0</td>
                            <td>14</td>
                            <td>1</td>
                            <td>0</td>
                            <td>0</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>
                                CREDITOS REFINANCIADOS
                            </td>
                            <td>1</td>
                            <td>0</td>
                            <td>1</td>
                            <td>1</td>
                            <td>0</td>
                            <td>0</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>
                                CONSULTAS ULTIMOS 6 MESES
                            </td>
                            <td>1</td>
                            <td>1</td>
                            <td>0</td>
                            <td>11</td>
                            <td>1</td>
                            <td>0</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>
                                DESCUENTOS VIGENTES A LA FECHA
                            </td>
                            <td>1</td>
                            <td>0</td>
                            <td>-</td>
                            <td>-</td>
                            <td>1</td>
                            <td>0</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>
                                ANTIGUEDAD DESDE
                            </td>
                            <td>1997-12-01</td>
                            <td>-</td>
                            <td>1997-12-01</td>
                            <td>1997-12-01</td>
                            <td>-</td>
                            <td>1997-12-01</td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
                <!-- Tendencia de endeudamiento -->
                <table class="table panel-table">
                    <tbody>
                        <tr>
                            <td colspan="13" class="panel-table-subtitle">
                                Tendencia de endeudamiento
                            </td>
                        </tr>
                        <tr class="panel-table-foot">
                            <td>
                                SALDOS Y MORAS
                            </td>
                            <td>JUL 22</td>
                            <td>JUN 22</td>
                            <td>MAY 22</td>
                            <td>ABR 22</td>
                            <td>MAR 22</td>
                            <td>FEB 22</td>
                            <td>ENE 22</td>
                            <td>DIC 21</td>
                            <td>NOV 21</td>
                            <td>OCT 21</td>
                            <td>SEP 21</td>
                            <td>AGO 21</td>
                        </tr>
                        <tr>
                            <td>Saldo Deuda Total en Mora (en miles)</td>
                            <td>0</td>
                            <td>1</td>
                            <td>0</td>
                            <td>1</td>
                            <td>1</td>
                            <td>2</td>
                            <td>2</td>
                            <td>1</td>
                            <td>2</td>
                            <td>2</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>Saldo Deuda Total (en miles)</td>
                            <td>0</td>
                            <td>1</td>
                            <td>0</td>
                            <td>1</td>
                            <td>1</td>
                            <td>2</td>
                            <td>2</td>
                            <td>1</td>
                            <td>2</td>
                            <td>2</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>Moras máx Sector Financiero</td>
                            <td>0</td>
                            <td>1</td>
                            <td>0</td>
                            <td>1</td>
                            <td>1</td>
                            <td>2</td>
                            <td>1</td>
                            <td>1</td>
                            <td>2</td>
                            <td>2</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>Moras máx Sector Cooperativo</td>
                            <td>0</td>
                            <td>1</td>
                            <td>0</td>
                            <td>1</td>
                            <td>1</td>
                            <td>2</td>
                            <td>1</td>
                            <td>1</td>
                            <td>2</td>
                            <td>2</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>Moras máx Sector Real</td>
                            <td>0</td>
                            <td>1</td>
                            <td>0</td>
                            <td>1</td>
                            <td>1</td>
                            <td>2</td>
                            <td>2</td>
                            <td>1</td>
                            <td>2</td>
                            <td>2</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>Moras máx Sector Telcos </td>
                            <td>0</td>
                            <td>1</td>
                            <td>0</td>
                            <td>1</td>
                            <td>1</td>
                            <td>2</td>
                            <td>2</td>
                            <td>1</td>
                            <td>2</td>
                            <td>2</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td><b>Total Moras Máximas</b></td>
                            <td>0</td>
                            <td>1</td>
                            <td>0</td>
                            <td>1</td>
                            <td>1</td>
                            <td>2</td>
                            <td>1</td>
                            <td>1</td>
                            <td>2</td>
                            <td>2</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>Núm créditos con mora =30</td>
                            <td>0</td>
                            <td>1</td>
                            <td>0</td>
                            <td>1</td>
                            <td>1</td>
                            <td>2</td>
                            <td>2</td>
                            <td>2</td>
                            <td>2</td>
                            <td>2</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>Núm créditos con mora >= 60 </td>
                            <td>0</td>
                            <td>1</td>
                            <td>0</td>
                            <td>1</td>
                            <td>1</td>
                            <td>2</td>
                            <td>2</td>
                            <td>2</td>
                            <td>2</td>
                            <td>2</td>
                            <td>2</td>
                            <td>2</td>
                        </tr>
                    </tbody>
                </table>
                <!-- Endeudamiento Actual -->
                <div class="panel-table-subtitle">
                    Endeudamiento Actual
                </div>
                <table class="table panel-table">
                    <thead>
                        <tr>
                            <th>Carteras</th>
                            <th>Calidad</th>
                            <th>Núm</th>
                            <th>Estado Actual</th>
                            <th>Calf</th>
                            <th>Vir o cupo inicial</th>
                            <th>Saldo actual</th>
                            <th>Saldo en mora</th>
                            <th>Valor cuota</th>
                            <th>% Part</th>
                            <th>% Deuda</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="11" class="panel-table-subtitle">
                                Sector Financiero
                            </td>
                        </tr>
                        <tr>
                            <td>Cartera bancaria</td>
                            <td>Principal</td>
                            <td>1</td>
                            <td>Esta en mora 120</td>
                            <td>D</td>
                            <td>93,742</td>
                            <td>94,971</td>
                            <td>12,069</td>
                            <td>1,543</td>
                            <td>87.3%</td>
                            <td>101.3%</td>
                        </tr>
                        <tr>
                            <td><b>Total Sector Financiero</b></td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td><b>93,742</b></td>
                            <td><b>94,971</b></td>
                            <td><b>12,069</b></td>
                            <td><b>1,543</b></td>
                            <td><b>87.3%</b></td>
                            <td><b>101.3%</b></td>
                        </tr>
                        <tr>
                            <td colspan="11" class="panel-table-subtitle">
                                Sector Cooperativo
                            </td>
                        </tr>
                        <tr>
                            <td>Cartera bancaria</td>
                            <td>Principal</td>
                            <td>1</td>
                            <td>Esta en mora 120</td>
                            <td>D</td>
                            <td>93,742</td>
                            <td>94,971</td>
                            <td>12,069</td>
                            <td>1,543</td>
                            <td>87.3%</td>
                            <td>101.3%</td>
                        </tr>
                        <tr>
                            <td><b>Total Sector Cooperativo</b></td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td><b>93,742</b></td>
                            <td><b>94,971</b></td>
                            <td><b>12,069</b></td>
                            <td><b>1,543</b></td>
                            <td><b>87.3%</b></td>
                            <td><b>101.3%</b></td>
                        </tr>
                        <tr>
                            <td colspan="11" class="panel-table-subtitle">
                                Sector Real
                            </td>
                        </tr>
                        <tr>
                            <td>Cartera bancaria</td>
                            <td>Principal</td>
                            <td>1</td>
                            <td>Esta en mora 120</td>
                            <td>D</td>
                            <td>93,742</td>
                            <td>94,971</td>
                            <td>12,069</td>
                            <td>1,543</td>
                            <td>87.3%</td>
                            <td>101.3%</td>
                        </tr>
                        <tr>
                            <td><b>Total Sector Real</b></td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td><b>93,742</b></td>
                            <td><b>94,971</b></td>
                            <td><b>12,069</b></td>
                            <td><b>1,543</b></td>
                            <td><b>87.3%</b></td>
                            <td><b>101.3%</b></td>
                        </tr>
                        <tr>
                            <td colspan="11" class="panel-table-subtitle">
                                Sector Telos
                            </td>
                        </tr>
                        <tr>
                            <td>Cartera bancaria</td>
                            <td>Principal</td>
                            <td>1</td>
                            <td>Esta en mora 120</td>
                            <td>D</td>
                            <td>93,742</td>
                            <td>94,971</td>
                            <td>12,069</td>
                            <td>1,543</td>
                            <td>87.3%</td>
                            <td>101.3%</td>
                        </tr>
                        <tr>
                            <td><b>Total Sector Telos</b></td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td><b>93,742</b></td>
                            <td><b>94,971</b></td>
                            <td><b>12,069</b></td>
                            <td><b>1,543</b></td>
                            <td><b>87.3%</b></td>
                            <td><b>101.3%</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- HÁBITO DE PAGO DE OBLIGACIONES ABIERTAS / VIGENTES  -->
            <div class="col-12">
                <div class="panel-table-title">
                    HÁBITO DE PAGO DE OBLIGACIONES ABIERTAS / VIGENTES
                </div>
                <!-- Sector Financiero -->
                <table class="table panel-table mb-0">
                    <tbody>
                        <tr>
                            <td colspan="11" class="panel-table-subtitle">
                                Sector Financiero
                            </td>
                        </tr>
                        <tr class="panel-table-foot text-center">
                            <td>Entidad Informante</td>
                            <td>Tipo Cuenta</td>
                            <td>Num cta 9 digitos</td>
                            <td>Calf</td>
                            <td>Estado de la Obligacion</td>
                            <td>Fecha Actualizacion</td>
                            <td>Adjetivo-fecha</td>
                            <td>Fecha Apertua</td>
                            <td>Fecha Vencimiento</td>
                            <td>Mora Maxima</td>
                            <td>47 meses</td>
                        </tr>
                        <tr>
                            <td>BCO DE BOGOTA</td>
                            <td>AHO</td>
                            <td>231135135</td>
                            <td>-</td>
                            <td>Activa</td>
                            <td>20220630</td>
                            <td>-</td>
                            <td>20081111</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table panel-table mb-0">
                    <tbody>
                        <tr class="panel-table-foot text-center">
                            <td>Desacuerdo con la inform</td>
                            <td>Estado del Titular</td>
                            <td>Marca/Clase</td>
                            <td>Tipo Garantia</td>
                            <td>Vlr o cupo inicial</td>
                            <td>Saldo Actual(Miles $)</td>
                            <td>Saldo en mora</td>
                            <td>Valor Cuota(Miles $)</td>
                            <td>Fecha Limite Pago</td>
                            <td>Perm</td>
                            <td>No.Cheq Devueltos</td>
                            <td>Cuotas/M/Vigencia</td>
                            <td>% deuda</td>
                            <td>Oficina/Deudor</td>
                        </tr>
                        <tr>
                            <td>-</td>
                            <td>Normal</td>
                            <td>Nomina</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>0.0%</td>
                            <td>0314 FLORIDA / -</td>
                        </tr>
                    </tbody>
                </table>
                <!-- Sector Cooperativo -->
                <table class="table panel-table mb-0">
                    <tbody>
                        <tr>
                            <td colspan="11" class="panel-table-subtitle">
                                Sector Cooperativo
                            </td>
                        </tr>
                        <tr class="panel-table-foot text-center">
                            <td>Entidad Informante</td>
                            <td>Tipo Cuenta</td>
                            <td>Num cta 9 digitos</td>
                            <td>Calf</td>
                            <td>Estado de la Obligacion</td>
                            <td>Fecha Actualizacion</td>
                            <td>Adjetivo-fecha</td>
                            <td>Fecha Apertua</td>
                            <td>Fecha Vencimiento</td>
                            <td>Mora Maxima</td>
                            <td>47 meses</td>
                        </tr>
                        <tr>
                            <td>BCO DE BOGOTA</td>
                            <td>AHO</td>
                            <td>231135135</td>
                            <td>-</td>
                            <td>Activa</td>
                            <td>20220630</td>
                            <td>-</td>
                            <td>20081111</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table panel-table mb-0">
                    <tbody>
                        <tr class="panel-table-foot text-center">
                            <td>Desacuerdo con la inform</td>
                            <td>Estado del Titular</td>
                            <td>Marca/Clase</td>
                            <td>Tipo Garantia</td>
                            <td>Vlr o cupo inicial</td>
                            <td>Saldo Actual(Miles $)</td>
                            <td>Saldo en mora</td>
                            <td>Valor Cuota(Miles $)</td>
                            <td>Fecha Limite Pago</td>
                            <td>Perm</td>
                            <td>No.Cheq Devueltos</td>
                            <td>Cuotas/M/Vigencia</td>
                            <td>% deuda</td>
                            <td>Oficina/Deudor</td>
                        </tr>
                        <tr>
                            <td>-</td>
                            <td>Normal</td>
                            <td>Nomina</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>0.0%</td>
                            <td>0314 FLORIDA / -</td>
                        </tr>
                    </tbody>
                </table>
                <!-- Sector Real -->
                <table class="table panel-table mb-0">
                    <tbody>
                        <tr>
                            <td colspan="11" class="panel-table-subtitle">
                                Sector Real
                            </td>
                        </tr>
                        <tr class="panel-table-foot text-center">
                            <td>Entidad Informante</td>
                            <td>Tipo Cuenta</td>
                            <td>Num cta 9 digitos</td>
                            <td>Calf</td>
                            <td>Estado de la Obligacion</td>
                            <td>Fecha Actualizacion</td>
                            <td>Adjetivo-fecha</td>
                            <td>Fecha Apertua</td>
                            <td>Fecha Vencimiento</td>
                            <td>Mora Maxima</td>
                            <td>47 meses</td>
                        </tr>
                        <tr>
                            <td>BCO DE BOGOTA</td>
                            <td>AHO</td>
                            <td>231135135</td>
                            <td>-</td>
                            <td>Activa</td>
                            <td>20220630</td>
                            <td>-</td>
                            <td>20081111</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table panel-table mb-0">
                    <tbody>
                        <tr class="panel-table-foot text-center">
                            <td>Desacuerdo con la inform</td>
                            <td>Estado del Titular</td>
                            <td>Marca/Clase</td>
                            <td>Tipo Garantia</td>
                            <td>Vlr o cupo inicial</td>
                            <td>Saldo Actual(Miles $)</td>
                            <td>Saldo en mora</td>
                            <td>Valor Cuota(Miles $)</td>
                            <td>Fecha Limite Pago</td>
                            <td>Perm</td>
                            <td>No.Cheq Devueltos</td>
                            <td>Cuotas/M/Vigencia</td>
                            <td>% deuda</td>
                            <td>Oficina/Deudor</td>
                        </tr>
                        <tr>
                            <td>-</td>
                            <td>Normal</td>
                            <td>Nomina</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>0.0%</td>
                            <td>0314 FLORIDA / -</td>
                        </tr>
                    </tbody>
                </table>
                <!-- Sector Telcos -->
                <table class="table panel-table mb-0">
                    <tbody>
                        <tr>
                            <td colspan="11" class="panel-table-subtitle">
                                Sector Telcos
                            </td>
                        </tr>
                        <tr class="panel-table-foot text-center">
                            <td>Entidad Informante</td>
                            <td>Tipo Cuenta</td>
                            <td>Num cta 9 digitos</td>
                            <td>Calf</td>
                            <td>Estado de la Obligacion</td>
                            <td>Fecha Actualizacion</td>
                            <td>Adjetivo-fecha</td>
                            <td>Fecha Apertua</td>
                            <td>Fecha Vencimiento</td>
                            <td>Mora Maxima</td>
                            <td>47 meses</td>
                        </tr>
                        <tr>
                            <td>BCO DE BOGOTA</td>
                            <td>AHO</td>
                            <td>231135135</td>
                            <td>-</td>
                            <td>Activa</td>
                            <td>20220630</td>
                            <td>-</td>
                            <td>20081111</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table panel-table">
                    <tbody>
                        <tr class="panel-table-foot text-center">
                            <td>Desacuerdo con la inform</td>
                            <td>Estado del Titular</td>
                            <td>Marca/Clase</td>
                            <td>Tipo Garantia</td>
                            <td>Vlr o cupo inicial</td>
                            <td>Saldo Actual(Miles $)</td>
                            <td>Saldo en mora</td>
                            <td>Valor Cuota(Miles $)</td>
                            <td>Fecha Limite Pago</td>
                            <td>Perm</td>
                            <td>No.Cheq Devueltos</td>
                            <td>Cuotas/M/Vigencia</td>
                            <td>% deuda</td>
                            <td>Oficina/Deudor</td>
                        </tr>
                        <tr>
                            <td>-</td>
                            <td>Normal</td>
                            <td>Nomina</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>0.0%</td>
                            <td>0314 FLORIDA / -</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- HÁBITO DE PAGO DE OBLIGACIONES CERRADAS / INACTIVAS  -->
            <div class="col-12 mt-2">
                <div class="panel-table-title">
                    HÁBITO DE PAGO DE OBLIGACIONES CERRADAS / INACTIVAS
                </div>
                <!-- Sector Financiero -->
                <table class="table panel-table mb-0">
                    <tbody>
                        <tr>
                            <td colspan="13" class="panel-table-subtitle">
                                Sector Financiero
                            </td>
                        </tr>
                        <tr class="panel-table-foot text-center">
                            <td>Entidad Informante</td>
                            <td>Tipo Cuenta</td>
                            <td>Estado de la Obligacion</td>
                            <td>Calf</td>
                            <td>Adjetivo-fecha</td>
                            <td>Num cta 9 digitos</td>
                            <td>Fecha Apertua</td>
                            <td>Fecha Cierre</td>
                            <td>Vlr o cupo inicial</td>
                            <td>Ciudad/Fecha</td>
                            <td>Oficina/Deudor</td>
                            <td>Desacuerdo con la inform</td>
                            <td>47 meses</td>
                        </tr>
                        <tr>
                            <td>BCO DE BOGOTA</td>
                            <td>AHO</td>
                            <td>Saldada</td>
                            <td>-</td>
                            <td>-</td>
                            <td>231135135</td>
                            <td>20220630</td>
                            <td>20220630</td>
                            <td>-</td>
                            <td>-</td>
                            <td>Of Virtual Valle DelCauc / -</td>
                            <td>-</td>
                            <td>[-----------N][-NNNNN------] BANCOOMEVA [------------][-----------N]</td>
                        </tr>
                        <tr>
                            <td>BCO DE BOGOTA</td>
                            <td>AHO</td>
                            <td>Saldada</td>
                            <td>-</td>
                            <td>-</td>
                            <td>231135135</td>
                            <td>20220630</td>
                            <td>20220630</td>
                            <td>-</td>
                            <td>-</td>
                            <td>Of Virtual Valle DelCauc / -</td>
                            <td>-</td>
                            <td>[-----------N][-NNNNN------] BANCOOMEVA [------------][-----------N]</td>
                        </tr>
                        <tr>
                            <td>BCO DE BOGOTA</td>
                            <td>AHO</td>
                            <td>Saldada</td>
                            <td>-</td>
                            <td>-</td>
                            <td>231135135</td>
                            <td>20220630</td>
                            <td>20220630</td>
                            <td>-</td>
                            <td>-</td>
                            <td>Of Virtual Valle DelCauc / -</td>
                            <td>-</td>
                            <td>[-----------N][-NNNNN------] BANCOOMEVA [------------][-----------N]</td>
                        </tr>
                    </tbody>
                </table>
                <!-- Sector Cooperativo -->
                <table class="table panel-table mb-0">
                    <tbody>
                        <tr>
                            <td colspan="13" class="panel-table-subtitle">
                                Sector Cooperativo
                            </td>
                        </tr>
                        <tr class="panel-table-foot text-center">
                            <td>Entidad Informante</td>
                            <td>Tipo Cuenta</td>
                            <td>Estado de la Obligacion</td>
                            <td>Calf</td>
                            <td>Adjetivo-fecha</td>
                            <td>Num cta 9 digitos</td>
                            <td>Fecha Apertua</td>
                            <td>Fecha Cierre</td>
                            <td>Vlr o cupo inicial</td>
                            <td>Ciudad/Fecha</td>
                            <td>Oficina/Deudor</td>
                            <td>Desacuerdo con la inform</td>
                            <td>47 meses</td>
                        </tr>
                        <tr>
                            <td>BCO DE BOGOTA</td>
                            <td>AHO</td>
                            <td>Saldada</td>
                            <td>-</td>
                            <td>-</td>
                            <td>231135135</td>
                            <td>20220630</td>
                            <td>20220630</td>
                            <td>-</td>
                            <td>-</td>
                            <td>Of Virtual Valle DelCauc / -</td>
                            <td>-</td>
                            <td>[-----------N][-NNNNN------] BANCOOMEVA [------------][-----------N]</td>
                        </tr>
                        <tr>
                            <td>BCO DE BOGOTA</td>
                            <td>AHO</td>
                            <td>Saldada</td>
                            <td>-</td>
                            <td>-</td>
                            <td>231135135</td>
                            <td>20220630</td>
                            <td>20220630</td>
                            <td>-</td>
                            <td>-</td>
                            <td>Of Virtual Valle DelCauc / -</td>
                            <td>-</td>
                            <td>[-----------N][-NNNNN------] BANCOOMEVA [------------][-----------N]</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Sector Real -->
                <table class="table panel-table mb-0">
                    <tbody>
                        <tr>
                            <td colspan="13" class="panel-table-subtitle">
                                Sector Real
                            </td>
                        </tr>
                        <tr class="panel-table-foot text-center">
                            <td>Entidad Informante</td>
                            <td>Tipo Cuenta</td>
                            <td>Estado de la Obligacion</td>
                            <td>Calf</td>
                            <td>Adjetivo-fecha</td>
                            <td>Num cta 9 digitos</td>
                            <td>Fecha Apertua</td>
                            <td>Fecha Cierre</td>
                            <td>Vlr o cupo inicial</td>
                            <td>Ciudad/Fecha</td>
                            <td>Oficina/Deudor</td>
                            <td>Desacuerdo con la inform</td>
                            <td>47 meses</td>
                        </tr>
                        <tr>
                            <td>BCO DE BOGOTA</td>
                            <td>AHO</td>
                            <td>Saldada</td>
                            <td>-</td>
                            <td>-</td>
                            <td>231135135</td>
                            <td>20220630</td>
                            <td>20220630</td>
                            <td>-</td>
                            <td>-</td>
                            <td>Of Virtual Valle DelCauc / -</td>
                            <td>-</td>
                            <td>[-----------N][-NNNNN------] BANCOOMEVA [------------][-----------N]</td>
                        </tr>
                        <tr>
                            <td>BCO DE BOGOTA</td>
                            <td>AHO</td>
                            <td>Saldada</td>
                            <td>-</td>
                            <td>-</td>
                            <td>231135135</td>
                            <td>20220630</td>
                            <td>20220630</td>
                            <td>-</td>
                            <td>-</td>
                            <td>Of Virtual Valle DelCauc / -</td>
                            <td>-</td>
                            <td>[-----------N][-NNNNN------] BANCOOMEVA [------------][-----------N]</td>
                        </tr>
                        <tr>
                            <td>BCO DE BOGOTA</td>
                            <td>AHO</td>
                            <td>Saldada</td>
                            <td>-</td>
                            <td>-</td>
                            <td>231135135</td>
                            <td>20220630</td>
                            <td>20220630</td>
                            <td>-</td>
                            <td>-</td>
                            <td>Of Virtual Valle DelCauc / -</td>
                            <td>-</td>
                            <td>[-----------N][-NNNNN------] BANCOOMEVA [------------][-----------N]</td>
                        </tr>
                        <tr>
                            <td>BCO DE BOGOTA</td>
                            <td>AHO</td>
                            <td>Saldada</td>
                            <td>-</td>
                            <td>-</td>
                            <td>231135135</td>
                            <td>20220630</td>
                            <td>20220630</td>
                            <td>-</td>
                            <td>-</td>
                            <td>Of Virtual Valle DelCauc / -</td>
                            <td>-</td>
                            <td>[-----------N][-NNNNN------] BANCOOMEVA [------------][-----------N]</td>
                        </tr>
                        <tr>
                            <td>BCO DE BOGOTA</td>
                            <td>AHO</td>
                            <td>Saldada</td>
                            <td>-</td>
                            <td>-</td>
                            <td>231135135</td>
                            <td>20220630</td>
                            <td>20220630</td>
                            <td>-</td>
                            <td>-</td>
                            <td>Of Virtual Valle DelCauc / -</td>
                            <td>-</td>
                            <td>[-----------N][-NNNNN------] BANCOOMEVA [------------][-----------N]</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Sector Telcos -->
                <table class="table panel-table">
                    <tbody>
                        <tr>
                            <td colspan="13" class="panel-table-subtitle">
                                Sector Telcos
                            </td>
                        </tr>
                        <tr class="panel-table-foot text-center">
                            <td>Entidad Informante</td>
                            <td>Tipo Cuenta</td>
                            <td>Estado de la Obligacion</td>
                            <td>Calf</td>
                            <td>Adjetivo-fecha</td>
                            <td>Num cta 9 digitos</td>
                            <td>Fecha Apertua</td>
                            <td>Fecha Cierre</td>
                            <td>Vlr o cupo inicial</td>
                            <td>Ciudad/Fecha</td>
                            <td>Oficina/Deudor</td>
                            <td>Desacuerdo con la inform</td>
                            <td>47 meses</td>
                        </tr>
                        <tr>
                            <td>BCO DE BOGOTA</td>
                            <td>AHO</td>
                            <td>Saldada</td>
                            <td>-</td>
                            <td>-</td>
                            <td>231135135</td>
                            <td>20220630</td>
                            <td>20220630</td>
                            <td>-</td>
                            <td>-</td>
                            <td>Of Virtual Valle DelCauc / -</td>
                            <td>-</td>
                            <td>[-----------N][-NNNNN------] BANCOOMEVA [------------][-----------N]</td>
                        </tr>
                        <tr>
                            <td>BCO DE BOGOTA</td>
                            <td>AHO</td>
                            <td>Saldada</td>
                            <td>-</td>
                            <td>-</td>
                            <td>231135135</td>
                            <td>20220630</td>
                            <td>20220630</td>
                            <td>-</td>
                            <td>-</td>
                            <td>Of Virtual Valle DelCauc / -</td>
                            <td>-</td>
                            <td>[-----------N][-NNNNN------] BANCOOMEVA [------------][-----------N]</td>
                        </tr>
                        <tr>
                            <td>BCO DE BOGOTA</td>
                            <td>AHO</td>
                            <td>Saldada</td>
                            <td>-</td>
                            <td>-</td>
                            <td>231135135</td>
                            <td>20220630</td>
                            <td>20220630</td>
                            <td>-</td>
                            <td>-</td>
                            <td>Of Virtual Valle DelCauc / -</td>
                            <td>-</td>
                            <td>[-----------N][-NNNNN------] BANCOOMEVA [------------][-----------N]</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- @if (isset($resultado["CuentaCorriente"]))
            <div class="col-md-12">
                <div class="panel panel-primary mb-3">
                    <div class="panel-heading"><b>CUENTAS CORRIENTE</b></div>
                    @foreach($resultado["CuentaCorriente"] as $CuentaCorriente )
                    <div class="panel-body">
                        @php $field = $CuentaCorriente["@attributes"]; @endphp
                        <div class="row">
                            <div class="col-2">
                                <b class="panel-label table-text">BLOQUEADA:</b>
                                <div>
                                    <p class="panel-value">{{ $field["bloqueada"] }}</p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <b class="panel-label table-text">ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $field["entidad"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">NUMERO:</b>
                                <div>
                                    <p class="panel-value">{{ $field["numero"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">FECHA APERTURA:</b>
                                <div>
                                    <p class="panel-value">{{ $field["fechaApertura"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">CALIFICACIÓN:</b>
                                <div>
                                    <p class="panel-value">{{ $field["calificacion"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">SITUACIÓN TITULAR:</b>
                                <div>
                                    <p class="panel-value">{{ $field["situacionTitular"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">OFICINA:</b>
                                <div>
                                    <p class="panel-value">{{ $field["oficina"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">CIUDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $field["ciudad"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">IDENTIFICACIÓN:</b>
                                <div>
                                    <p class="panel-value">{{ $field["identificacion"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">SECTOR:</b>
                                <div>
                                    <p class="panel-value">{{ $field["sector"] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if (isset($resultado["CuentaCartera"]))
            <div class="col-md-12">
                <div class="panel panel-primary mb-3">
                    <div class="panel-heading"><b>CUENTAS CARTERA</b></div>
                    @foreach($resultado["CuentaCartera"] as $CuentaCartera )
                    <div class="panel-body">
                        @php $field = $CuentaCartera["@attributes"]; @endphp
                        <div class="row">
                            <div class="col-2">
                                <b class="panel-label table-text">BLOQUEADA:</b>
                                <div>
                                    <p class="panel-value">{{ $field["bloqueada"] === "true" ? "Sí" : "NO" }}</p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <b class="panel-label table-text">ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $field["entidad"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">NÚMERO:</b>
                                <div>
                                    <p class="panel-value">{{ $field["numero"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">FECHA APERTURA:</b>
                                <div>
                                    <p class="panel-value">{{ $field["fechaApertura"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">FECHA VENCIMIENTO:</b>
                                <div>
                                    <p class="panel-value">{{ $field["fechaVencimiento"] }}</p>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <b class="panel-label text-center">COMPORTAMIENTO:</b>
                                <div>
                                    <p class="panel-value">{{ $field["comportamiento"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">FORMA DE PAGO:</b>
                                <div>
                                    <p class="panel-value">{{ $field["formaPago"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">PROBABILIDAD DE INCUMPLIMIENTO:</b>
                                <div>
                                    <p class="panel-value">{{ $field["probabilidadIncumplimiento"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">CALIFICACIÓN:</b>
                                <div>
                                    <p class="panel-value">{{ $field["calificacion"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">SITUACIÓN TITULAR:</b>
                                <div>
                                    <p class="panel-value">{{ $field["situacionTitular"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">OFICINA:</b>
                                <div>
                                    <p class="panel-value">{{ $field["oficina"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">CIUDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $field["ciudad"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">TIPO DE IDENTIFICACIÓN:</b>
                                <div>
                                    <p class="panel-value">{{ $field["tipoIdentificacion"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">IDENTIFICACIÓN:</b>
                                <div>
                                    <p class="panel-value">{{ $field["identificacion"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">SECTOR:</b>
                                <div>
                                    <p class="panel-value">{{ $field["sector"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">CALIFICACIÓN HD:</b>
                                <div>
                                    <p class="panel-value">{{ $field["calificacionHD"] === "true" ? "Sí" : "NO" }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif -->

            <!-- EVOLUCIÓN DE LA DEUDA -->
            <div class="col-12 mb-3">
                <div class="panel-table-title">
                    EVOLUCIÓN DE LA DEUDA
                </div>
                <table class="table panel-table">
                    <thead>
                        <tr>
                            <th>Tipo Cuenta</th>
                            <th>Valores</th>
                            <th>Trimestre 2022/09</th>
                            <th>Trimestre 2022/06</th>
                            <th>Trimestre 2022/03</th>
                            <th>Trimestre 2021/12</th>
                            <th>Trimestre 2021/09</th>
                        </tr>
                    </thead>
                    <!-- Sector Financiero -->
                    <tbody>
                        <tr>
                            <td colspan="7" class="panel-table-subtitle">
                                Sector Financiero
                            </td>
                        </tr>
                        <tr>
                            <td rowspan="7" class="align-middle">
                                Cartera bancaria
                            </td>
                            <td>Num</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>Vlr o Cupo Inicial</td>
                            <td>93,742</td>
                            <td>93,742</td>
                            <td>93,742</td>
                            <td>93,742</td>
                            <td>174,742</td>
                        </tr>
                        <tr>
                            <td>Saldo</td>
                            <td>94,971</td>
                            <td>95,551</td>
                            <td>95,551</td>
                            <td>95,551</td>
                            <td>93,921</td>
                        </tr>
                        <tr>
                            <td>Saldo en Mora</td>
                            <td>12,069</td>
                            <td>13,015</td>
                            <td>8,118</td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td>Valor Cuota</td>
                            <td>1,543</td>
                            <td>1,543</td>
                            <td>1,349</td>
                            <td>1,349</td>
                            <td>1,349</td>
                        </tr>
                        <tr>
                            <td>% Deuda</td>
                            <td>101.3%</td>
                            <td>101.9%</td>
                            <td>101.9%</td>
                            <td>101.9%</td>
                            <td>53.7%</td>
                        </tr>
                        <tr>
                            <td>
                                &lt; Calificación
                            </td>
                            <td>D</td>
                            <td>D</td>
                            <td>D</td>
                            <td>A</td>
                            <td>AA</td>
                        </tr>
                        <tr class="panel-table-foot">
                            <td>Total</td>
                            <td>Saldo Financiero </td>
                            <td>94,971</td>
                            <td>95,551</td>
                            <td>95,551</td>
                            <td>95,551</td>
                            <td>93,921</td>
                        </tr>
                        <!-- Sector Cooperativo -->
                        <tr>
                            <td colspan="7" class="panel-table-subtitle">
                                Sector Cooperativo
                            </td>
                        </tr>
                        <tr>
                            <td rowspan="7" class="align-middle">
                                Cartera Libranza
                            </td>
                            <td>Num</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td>Vlr o Cupo Inicial</td>
                            <td>9,871</td>
                            <td>9,871</td>
                            <td>9,871</td>
                            <td>9,871</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td>Saldo</td>
                            <td>94,971</td>
                            <td>95,551</td>
                            <td>95,551</td>
                            <td>95,551</td>
                            <td>93,921</td>
                        </tr>
                        <tr>
                            <td>Saldo en Mora</td>
                            <td>12,069</td>
                            <td>13,015</td>
                            <td>8,118</td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td>Valor Cuota</td>
                            <td>1,543</td>
                            <td>1,543</td>
                            <td>1,349</td>
                            <td>1,349</td>
                            <td>1,349</td>
                        </tr>
                        <tr>
                            <td>% Deuda</td>
                            <td>101.3%</td>
                            <td>101.9%</td>
                            <td>101.9%</td>
                            <td>101.9%</td>
                            <td>53.7%</td>
                        </tr>
                        <tr>
                            <td>
                                &lt; Calificación
                            </td>
                            <td>D</td>
                            <td>D</td>
                            <td>D</td>
                            <td>A</td>
                            <td>AA</td>
                        </tr>
                        <tr class="panel-table-foot">
                            <td>Total</td>
                            <td>Saldo Cooperativo</td>
                            <td>94,971</td>
                            <td>95,551</td>
                            <td>95,551</td>
                            <td>95,551</td>
                            <td>93,921</td>
                        </tr>
                        <!-- Sector Real -->
                        <tr>
                            <td colspan="7" class="panel-table-subtitle">
                                Sector Real
                            </td>
                        </tr>
                        <tr>
                            <td rowspan="7" class="align-middle">
                                Servicios financieros
                            </td>
                            <td>Num</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td>Vlr o Cupo Inicial</td>
                            <td>9,871</td>
                            <td>9,871</td>
                            <td>9,871</td>
                            <td>9,871</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td>Saldo</td>
                            <td>94,971</td>
                            <td>95,551</td>
                            <td>95,551</td>
                            <td>95,551</td>
                            <td>93,921</td>
                        </tr>
                        <tr>
                            <td>Saldo en Mora</td>
                            <td>12,069</td>
                            <td>13,015</td>
                            <td>8,118</td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td>Valor Cuota</td>
                            <td>1,543</td>
                            <td>1,543</td>
                            <td>1,349</td>
                            <td>1,349</td>
                            <td>1,349</td>
                        </tr>
                        <tr>
                            <td>% Deuda</td>
                            <td>101.3%</td>
                            <td>101.9%</td>
                            <td>101.9%</td>
                            <td>101.9%</td>
                            <td>53.7%</td>
                        </tr>
                        <tr>
                            <td>
                                &lt; Calificación
                            </td>
                            <td>D</td>
                            <td>D</td>
                            <td>D</td>
                            <td>A</td>
                            <td>AA</td>
                        </tr>
                        <tr class="panel-table-foot">
                            <td>Total</td>
                            <td>Saldo Real </td>
                            <td>94,971</td>
                            <td>95,551</td>
                            <td>95,551</td>
                            <td>95,551</td>
                            <td>93,921</td>
                        </tr>
                        <!-- Sector Telcos -->
                        <tr>
                            <td colspan="7" class="panel-table-subtitle">
                                Sector Telcos
                            </td>
                        </tr>
                        <tr>
                            <td rowspan="7" class="align-middle">
                                Cartera telefonía celular
                            </td>
                            <td>Num</td>
                            <td>1</td>
                            <td>1</td>
                            <td>1</td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td>Vlr o Cupo Inicial</td>
                            <td>9,871</td>
                            <td>9,871</td>
                            <td>9,871</td>
                            <td>9,871</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td>Saldo</td>
                            <td>94,971</td>
                            <td>95,551</td>
                            <td>95,551</td>
                            <td>95,551</td>
                            <td>93,921</td>
                        </tr>
                        <tr>
                            <td>Saldo en Mora</td>
                            <td>12,069</td>
                            <td>13,015</td>
                            <td>8,118</td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                        <tr>
                            <td>Valor Cuota</td>
                            <td>1,543</td>
                            <td>1,543</td>
                            <td>1,349</td>
                            <td>1,349</td>
                            <td>1,349</td>
                        </tr>
                        <tr>
                            <td>% Deuda</td>
                            <td>101.3%</td>
                            <td>101.9%</td>
                            <td>101.9%</td>
                            <td>101.9%</td>
                            <td>53.7%</td>
                        </tr>
                        <tr>
                            <td>
                                &lt; Calificación
                            </td>
                            <td>D</td>
                            <td>D</td>
                            <td>D</td>
                            <td>A</td>
                            <td>AA</td>
                        </tr>
                        <tr class="panel-table-foot">
                            <td>Total</td>
                            <td>Saldo Telcos </td>
                            <td>94,971</td>
                            <td>95,551</td>
                            <td>95,551</td>
                            <td>95,551</td>
                            <td>93,921</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- DEMANDAS JUDICIALES -->
            <div class="col-12 mb-3">
                <div class="panel-table-title">
                    <span>DEMANDAS JUDICIALES</span>
                    <span>9QQ4586</span>
                </div>
                <!-- Procesos Judiciales -->
                <table class="table panel-table">
                    <thead>
                        <tr>
                            <th class="text-left">Procesos Judiciales</th>
                            <th>2022</th>
                            <th>2021</th>
                            <th>2020</th>
                            <th>2019</th>
                            <th>2018</th>
                            <th>2017</th>
                            <th>Años Anteriores</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th class="text-left">Demandado</th>
                            <td>1</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>1</td>
                        </tr>
                        <tr>
                            <th class="text-left">Demandante</th>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                    </tbody>
                </table>

                <table class="table panel-table">
                    <thead>
                        <tr>
                            <th colspan="12" class="subtitle">
                                Procesos Judiciales como Demandado
                            </th>
                        </tr>
                        <tr>
                            <th>Fecha – última novedad procesal</th>
                            <th>Nombre del Demandante</th>
                            <th>Nit Demandante</th>
                            <th>Jurisdicción</th>
                            <th>Tipo de Juzgado</th>
                            <th>Tipo de Proceso</th>
                            <th>Fecha de Inicio del Proceso</th>
                            <th>Rango de Pretensiones</th>
                            <th>Instancia del Proceso</th>
                            <th>Estado del Proceso</th>
                            <th>Tiene Garantías</th>
                            <th>Tipo de Garantías</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>11/07/2022</td>
                            <td>COOPERATIVA DE CREDITO Y SERVICIO COMUNIDAD SIGLA COOMUNIDAD</td>
                            <td>804015582</td>
                            <td>Cali</td>
                            <td>Municipal Civil</td>
                            <td>Ejecución Singular</td>
                            <td>01/06/2022</td>
                            <td>hasta 40 SMLMV</td>
                            <td>1° Instancia</td>
                            <td>Activo</td>
                            <td>NO</td>
                            <td>-</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table panel-table">
                    <thead>
                        <tr>
                            <th class="subtitle">
                                Procesos Judiciales como Demandante
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>No existe información Judicial para esta CC como Demandante</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- HISTÓRICO DE CONSULTAS -->
            <div class="col-12 mb-3">
                <div class="panel-table-title">
                    <span>HISTÓRICO DE CONSULTAS</span>
                    <span>9QQ4586</span>
                </div>
                <table class="table panel-table mb-2">
                    <thead>
                        <tr>
                            <th>Fecha Ult. Consulta</th>
                            <th>Consultante</th>
                            <th>No. de Consultas mes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>12/07/2022</td>
                            <td>CK COMERCIALIZ ADORA</td>
                            <td>01</td>
                        </tr>
                        <tr>
                            <td>12/07/2022</td>
                            <td>KREDIT PLUS SAS</td>
                            <td>01</td>
                        </tr>
                        <tr>
                            <td>12/07/2022</td>
                            <td>EXCELCREDIT SA</td>
                            <td>01</td>
                        </tr>
                        <tr>
                            <td>12/07/2022</td>
                            <td>LABOR FINAN CIERA</td>
                            <td>01</td>
                        </tr>
                        <tr>
                            <td>12/07/2022</td>
                            <td>KREDIT PLUS SAS</td>
                            <td>01</td>
                        </tr>
                        <tr>
                            <td>12/07/2022</td>
                            <td>FINANCIERA JURISCOOP S.A</td>
                            <td>01</td>
                        </tr>
                        <tr>
                            <td>12/07/2022</td>
                            <td>BCO AVVILLAS COMERCIAL</td>
                            <td>01</td>
                        </tr>
                    </tbody>
                </table>
                <p>
                    <small>*CONSULTAS LOTE: corresponden a consultas realizadas por las Entidades para la supervisión y control del riesgo crediticio.</small>
                </p>
            </div>

            <!-- ENDEUDAMIENTO GLOBAL CLASIFICADO -->
            <div class="col-12 mb-3">
                <div class="panel-table-title">
                    <span>ENDEUDAMIENTO GLOBAL CLASIFICADO</span>
                    <span>9QQ4586</span>
                </div>
                <table class="table panel-table">
                    <!-- Sector Financiero -->
                    <tr>
                        <th colspan="17" class="subtitle-max">
                            TRIMESTRE 2021/09
                        </th>
                    </tr>
                    <tr>
                        <th colspan="17" class="subtitle">
                            Sector Financiero
                        </th>
                    </tr>
                    <tr>
                        <th rowspan="2">Entidad Informante</th>
                        <th rowspan="2">Calf</th>
                        <th rowspan="2">Num</th>
                        <th rowspan="2">Saldo total</th>
                        <th colspan="2">Comercial</th>
                        <th colspan="2">Hipotecario</th>
                        <th colspan="2">Consumo y Tarjeta de Crédito</th>
                        <th colspan="2">Microcrédito</th>
                        <th colspan="3">Garantías</th>
                        <th rowspan="2">Moneda</th>
                        <th rowspan="2">Fuente</th>
                    </tr>
                    <tr>
                        <th>Nro</th>
                        <th>Miles $</th>
                        <th>Nro</th>
                        <th>Miles $</th>
                        <th>Nro</th>
                        <th>Miles $</th>
                        <th>Nro</th>
                        <th>Miles $</th>
                        <th>Tipo</th>
                        <th>Fecha Avalúo</th>
                        <th>Valor</th>
                    </tr>
                    <tr>
                        <td>AVVILLAS - BC</td>
                        <td>A</td>
                        <td>1</td>
                        <td>$95,295</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>1</td>
                        <td>$95,295</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>SIN GAR</td>
                        <td>-</td>
                        <td>$0</td>
                        <td>ML</td>
                        <td>S</td>
                    </tr>
                    <tr class="panel-table-foot">
                        <td>TOTAL</td>
                        <td colspan="2">1</td>
                        <td>$95,295</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>1</td>
                        <td>$95,295</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <!-- Sector Real -->
                    <tr>
                        <th colspan="17" class="subtitle">
                            Sector Real
                        </th>
                    </tr>
                    <tr>
                        <th rowspan="2">Entidad Informante</th>
                        <th rowspan="2">Calf</th>
                        <th rowspan="2">Num</th>
                        <th rowspan="2">Saldo total</th>
                        <th colspan="2">Comercial</th>
                        <th colspan="2">Hipotecario</th>
                        <th colspan="2">Consumo y Tarjeta de Crédito</th>
                        <th colspan="2">Microcrédito</th>
                        <th colspan="3">Garantías</th>
                        <th rowspan="2">Moneda</th>
                        <th rowspan="2">Fuente</th>
                    </tr>
                    <tr>
                        <th>Nro</th>
                        <th>Miles $</th>
                        <th>Nro</th>
                        <th>Miles $</th>
                        <th>Nro</th>
                        <th>Miles $</th>
                        <th>Nro</th>
                        <th>Miles $</th>
                        <th>Tipo</th>
                        <th>Fecha Avalúo</th>
                        <th>Valor</th>
                    </tr>
                    <tr>
                        <td>CREDIVALORES - CREDISERVICIOS</td>
                        <td>A</td>
                        <td>1</td>
                        <td>$95,295</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>1</td>
                        <td>$95,295</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>SIN GAR</td>
                        <td>-</td>
                        <td>$0</td>
                        <td>ML</td>
                        <td>S</td>
                    </tr>
                    <tr class="panel-table-foot">
                        <td>TOTAL</td>
                        <td colspan="2">1</td>
                        <td>$95,295</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>1</td>
                        <td>$95,295</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <!-- Sector Telcos -->
                    <tr>
                        <th colspan="17" class="subtitle">
                            Sector Telcos
                        </th>
                    </tr>
                    <tr>
                        <th rowspan="2">Entidad Informante</th>
                        <th rowspan="2">Calf</th>
                        <th rowspan="2">Num</th>
                        <th rowspan="2">Saldo total</th>
                        <th colspan="2">Comercial</th>
                        <th colspan="2">Hipotecario</th>
                        <th colspan="2">Consumo y Tarjeta de Crédito</th>
                        <th colspan="2">Microcrédito</th>
                        <th colspan="3">Garantías</th>
                        <th rowspan="2">Moneda</th>
                        <th rowspan="2">Fuente</th>
                    </tr>
                    <tr>
                        <th>Nro</th>
                        <th>Miles $</th>
                        <th>Nro</th>
                        <th>Miles $</th>
                        <th>Nro</th>
                        <th>Miles $</th>
                        <th>Nro</th>
                        <th>Miles $</th>
                        <th>Tipo</th>
                        <th>Fecha Avalúo</th>
                        <th>Valor</th>
                    </tr>
                    <tr>
                        <td>CLARO - SERVICIO MOVIL</td>
                        <td>A</td>
                        <td>1</td>
                        <td>$95,295</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>1</td>
                        <td>$95,295</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>SIN GAR</td>
                        <td>-</td>
                        <td>$0</td>
                        <td>ML</td>
                        <td>S</td>
                    </tr>
                    <tr class="panel-table-foot">
                        <td>TOTAL</td>
                        <td colspan="2">1</td>
                        <td>$95,295</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>1</td>
                        <td>$95,295</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                </table>
            </div>

            <!-- RESUMEN ENDEUDAMIENTO GLOBAL -->
            <div class="col-12 mb-3">
                <div class="panel-table-title">
                    <span>RESUMEN ENDEUDAMIENTO GLOBAL</span>
                    <span>9QQ4586</span>
                </div>
                <table class="table panel-table">
                    <tr>
                        <th rowspan="2">Fecha Corte</th>
                        <th rowspan="2">Sector</th>
                        <th colspan="2">Comercial</th>
                        <th colspan="2">Hipotecario</th>
                        <th colspan="2">Consumo y Tarjeta de Crédito</th>
                        <th colspan="2">Microcrédito</th>
                        <th rowspan="2">% Participación</th>
                    </tr>
                    <tr>
                        <th>Nro</th>
                        <th>Miles $</th>
                        <th>Nro</th>
                        <th>Miles $</th>
                        <th>Nro</th>
                        <th>Miles $</th>
                        <th>Nro</th>
                        <th>Miles $</th>
                    </tr>
                    <tr>
                        <td rowspan="4">2021/09</td>
                        <td>Financiero</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>1</td>
                        <td>$95,295</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>91.4%</td>
                    </tr>
                    <tr>
                        <td>Cooperativo</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>1</td>
                        <td>$95,295</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>91.4%</td>
                    </tr>
                    <tr>
                        <td>Real</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>1</td>
                        <td>$95,295</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>91.4%</td>
                    </tr>
                    <tr>
                        <td>Telcos</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>1</td>
                        <td>$95,295</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>91.4%</td>
                    </tr>
                    <tr class="panel-table-foot">
                        <td></td>
                        <td>Total</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>1</td>
                        <td>$95,295</td>
                        <td>0</td>
                        <td>$0</td>
                        <td>100%</td>
                    </tr>
                </table>
            </div>

            <!-- RECONOCER+ -->
            <div class="col-12 mb-3">
                <div class="panel-table-title">
                    <span>RECONOCER+</span>
                    <span>9QQ4586</span>
                </div>
                <table class="table panel-table">
                    <tr>
                        <th>#</th>
                        <th>Dirección</th>
                        <th>Estrato</th>
                        <th>Tipo</th>
                        <th>Zona</th>
                        <th>Ciudad</th>
                        <th>Departamento</th>
                        <th>Reportado</th>
                        <th>Ultimo Reporte</th>
                        <th># de Reportes</th>
                        <th># de Entidades</th>
                        <th>Fuente</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>KR 15 9 18 LA LIBERTAD</td>
                        <td>-</td>
                        <td>RES - LAB - CRR</td>
                        <td>URB</td>
                        <td>PRADERA</td>
                        <td>VALLE DEL CAUCA</td>
                        <td>FEB - 2018</td>
                        <td>JUN - 2022</td>
                        <td>21</td>
                        <td>9</td>
                        <td>SUS</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>KR 15 9 18 LA LIBERTAD</td>
                        <td>-</td>
                        <td>RES - LAB - CRR</td>
                        <td>URB</td>
                        <td>PRADERA</td>
                        <td>VALLE DEL CAUCA</td>
                        <td>FEB - 2018</td>
                        <td>JUN - 2022</td>
                        <td>21</td>
                        <td>9</td>
                        <td>SUS</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>KR 15 9 18 LA LIBERTAD</td>
                        <td>-</td>
                        <td>RES - LAB - CRR</td>
                        <td>URB</td>
                        <td>PRADERA</td>
                        <td>VALLE DEL CAUCA</td>
                        <td>FEB - 2018</td>
                        <td>JUN - 2022</td>
                        <td>21</td>
                        <td>9</td>
                        <td>SUS</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>KR 15 9 18 LA LIBERTAD</td>
                        <td>-</td>
                        <td>RES - LAB - CRR</td>
                        <td>URB</td>
                        <td>PRADERA</td>
                        <td>VALLE DEL CAUCA</td>
                        <td>FEB - 2018</td>
                        <td>JUN - 2022</td>
                        <td>21</td>
                        <td>9</td>
                        <td>SUS</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>KR 15 9 18 LA LIBERTAD</td>
                        <td>-</td>
                        <td>RES - LAB - CRR</td>
                        <td>URB</td>
                        <td>PRADERA</td>
                        <td>VALLE DEL CAUCA</td>
                        <td>FEB - 2018</td>
                        <td>JUN - 2022</td>
                        <td>21</td>
                        <td>9</td>
                        <td>SUS</td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>KR 15 9 18 LA LIBERTAD</td>
                        <td>-</td>
                        <td>RES - LAB - CRR</td>
                        <td>URB</td>
                        <td>PRADERA</td>
                        <td>VALLE DEL CAUCA</td>
                        <td>FEB - 2018</td>
                        <td>JUN - 2022</td>
                        <td>21</td>
                        <td>9</td>
                        <td>SUS</td>
                    </tr>
                </table>

                <table class="table panel-table">
                    <tr>
                        <th colspan="25" class="subtitle">
                            Vector de Direcciones
                        </th>
                    </tr>
                    <tr>
                        <th>Tipo</th>
                        <th>Ago 20</th>
                        <th>Sep 20</th>
                        <th>Oct 20</th>
                        <th>Nov 20</th>
                        <th>Dic 20</th>
                        <th>Ene 21</th>
                        <th>Feb 21</th>
                        <th>Mar 21</th>
                        <th>Abr 21</th>
                        <th>May 21</th>
                        <th>Jun 21</th>
                        <th>Jul 21</th>
                        <th>Ago 21</th>
                        <th>Sep 21</th>
                        <th>Oct 21</th>
                        <th>Nov 21</th>
                        <th>Dic 21</th>
                        <th>Ene 22</th>
                        <th>Feb 22</th>
                        <th>Mar 22</th>
                        <th>Abr 22</th>
                        <th>May 22</th>
                        <th>Jun 22</th>
                        <th>Jul 22</th>
                    </tr>
                    <tr>
                        <td>RES</td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>LAB</td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-warning">
                                <i class="fas fa-plus"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-warning">
                                <i class="fas fa-plus"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>CRR</td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                        <td>
                            <div class="circle-icon bg-success">
                                <i class="fas fa-equals"></i>
                            </div>
                        </td>
                    </tr>
                </table>

                <table class="table panel-table">
                    <tr>
                        <th>#</th>
                        <th>Teléfono Fijo</th>
                        <th>Tipo</th>
                        <th>Ciudad</th>
                        <th>Departamento</th>
                        <th>Reportado Desde</th>
                        <th>Ultimo Reporte</th>
                        <th># de Reportes</th>
                        <th># de Entidades F</th>
                        <th>Fuente</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>2642106 </td>
                        <td>LAB</td>
                        <td>PRADERA</td>
                        <td>VALLE DEL CAUCA</td>
                        <td>AGO - 2019</td>
                        <td>JUL - 2022</td>
                        <td>4</td>
                        <td>2</td>
                        <td>SUS</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>2642106 </td>
                        <td>LAB</td>
                        <td>PRADERA</td>
                        <td>VALLE DEL CAUCA</td>
                        <td>AGO - 2019</td>
                        <td>JUL - 2022</td>
                        <td>4</td>
                        <td>2</td>
                        <td>SUS</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>2642106 </td>
                        <td>LAB</td>
                        <td>PRADERA</td>
                        <td>VALLE DEL CAUCA</td>
                        <td>AGO - 2019</td>
                        <td>JUL - 2022</td>
                        <td>4</td>
                        <td>2</td>
                        <td>SUS</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>2642106 </td>
                        <td>LAB</td>
                        <td>PRADERA</td>
                        <td>VALLE DEL CAUCA</td>
                        <td>AGO - 2019</td>
                        <td>JUL - 2022</td>
                        <td>4</td>
                        <td>2</td>
                        <td>SUS</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>2642106 </td>
                        <td>LAB</td>
                        <td>PRADERA</td>
                        <td>VALLE DEL CAUCA</td>
                        <td>AGO - 2019</td>
                        <td>JUL - 2022</td>
                        <td>4</td>
                        <td>2</td>
                        <td>SUS</td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>2642106 </td>
                        <td>LAB</td>
                        <td>PRADERA</td>
                        <td>VALLE DEL CAUCA</td>
                        <td>AGO - 2019</td>
                        <td>JUL - 2022</td>
                        <td>4</td>
                        <td>2</td>
                        <td>SUS</td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>2642106</td>
                        <td>LAB</td>
                        <td>PRADERA</td>
                        <td>VALLE DEL CAUCA</td>
                        <td>AGO - 2019</td>
                        <td>JUL - 2022</td>
                        <td>4</td>
                        <td>2</td>
                        <td>SUS</td>
                    </tr>
                </table>

                <table class="table panel-table">
                    <tr>
                        <th>#</th>
                        <th>Celular</th>
                        <th>Reportado Por</th>
                        <th>Reportado Desde</th>
                        <th>Último Reporte</th>
                        <th># de Reportes</th>
                        <th># de Entidades</th>
                        <th>Fuente</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>3117422598</td>
                        <td>-</td>
                        <td>FEB - 2018</td>
                        <td>JUL - 2022</td>
                        <td>14</td>
                        <td>8</td>
                        <td>SUS</td>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th colspan="2">Correo Electrónico</th>
                        <th>Reportado Por</th>
                        <th>Reportado Desde</th>
                        <th>Último Reporte</th>
                        <th># de Reportes</th>
                        <th>Fuente</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td colspan="2">evecaor@hotmail.com</td>
                        <td>-</td>
                        <td>NOV - 2008</td>
                        <td>JUN - 2022</td>
                        <td>8</td>
                        <td>SUS</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td colspan="2">evecaor@hotmail.com</td>
                        <td>-</td>
                        <td>NOV - 2008</td>
                        <td>JUN - 2022</td>
                        <td>8</td>
                        <td>SUS</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td colspan="2">evecaor@hotmail.com</td>
                        <td>-</td>
                        <td>NOV - 2008</td>
                        <td>JUN - 2022</td>
                        <td>8</td>
                        <td>SUS</td>
                    </tr>
                </table>
                <div class="panel-table-title">
                    Fin-consulta Tipo 1. La consulta fue efectiva.
                </div>
            </div>
        </div>
        @else
        <div class="col-md-12">
            <h4>Aún no hay usuarios para mostrar.</h4>
        </div>
        @endif
    </div>
</div>
@endsection

@section('title')
Consulta Cifin
@endsection

@section('header-content')
Consulta Cifin
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('home') }}"><i class="fa fa-dashboard mr-2"></i>Inicio</a></li>
<li class="breadcrumb-item"><a href="{{ url('cifin') }}">Datacredito</a></li>
<li class="breadcrumb-item active">Consulta</li>
@endsection