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
                <div class="panel mb-3">
                    <div class="panel-heading text-center"><b>INFORMACION BASICA</b></div>
                    <div class="panel-body">
                        <div class="row">
                            <!---->
                            @if (isset($resultado))
                            <div class="col-2">
                                <b class="panel-label">TIPO IDENTIFICACIÓN:</b>
                                <div>
                                    <p class="panel-value">{{ $resultado["@attributes"]["tipoIdDigitado"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($resultado["JuridicaNacional"]["@attributes"]["identificacion"]))
                            <div class="col-2">
                                <b class="panel-label">IDENTIFICACIÓN:</b>
                                <div>
                                    <p class="panel-value">{{ $resultado["JuridicaNacional"]["@attributes"]["identificacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            <!-- @if (isset($resultado["NaturalNacional"]["Identificacion"]))
                            <div class="col-2">
                                <b class="panel-label">IDENTIFICACIÓN:</b>
                                <div>
                                    <p class="panel-value">{{  $resultado["NaturalNacional"]["Identificacion"]["numero"] }}</p>
                                </div>
                            </div>
                            @endif -->
                            @if (isset($resultado["JuridicaNacional"]))
                            <div class="col-2">
                                <b class="panel-label">ESTADO DOCUMENTO:</b>
                                <div>
                                    <p class="panel-value">{{ $resultado["JuridicaNacional"]["@attributes"]["validada"] }}</p>
                                </div>
                            </div>
                            @endif
                            <div class="col-3">
                                <b class="panel-label">LUGAR:</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col-3">
                                <b class="panel-label">FECHA EXPEDICIÓN:</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <b class="panel-label">NOMBRES APELLIDOS - RAZÓN SOCIAL:</b>
                                <div>
                                    <p class="panel-value">{{ $resultado["JuridicaNacional"]["@attributes"]["razonSocial"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label">RANGO EDAD:</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label">GENERO:</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label">TIENE RUT?:</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label">ANTIGUEDAD:</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col-3">
                                <b class="panel-label">ACTIVIDAD ECONOMICA:</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col-3">
                                <b class="panel-label">EMPLEADOR:</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label">TIPO DE CONTRATO:</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label">FECHA CONTRATO:</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label">OPER INTERNACIONALES:</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DETALLE SOCIO DEMOGRAFICO -->
            <div class="col-12">
                <div class="panel mb-3">
                    <div class="panel-heading">Detalle Información Socio Demográfica</div>
                    <div class="panel-body">
                        <div class="row">
                            <!---->
                            <div class="col-2">
                                <b class="panel-label">REPORTADO POR:</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label">FECHA REPORTE:</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label">ACT ECONOMICA:</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label">EMPLEADOR:</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label">TIPO CONTRATO:</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label">FECHA CONTRATO:</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SCORE -->
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
            @endif

            <!-- RESUMEN -->
            <div class="col-12">
                <div class="panel mb-3">
                    <div class="panel-heading mb-1 text-center"><b>RESUMEN</b></div>
                    <div class="panel-heading">Perfil General</div>
                    <div class="panel-body">
                        @php $field = $resultado["InfoAgregada"]["Resumen"]["Principales"]["@attributes"]; @endphp
                        <div class="row">
                            <div class="col-md-2">
                                <b class="panel-label">SECTORES</b>
                                <div>
                                    <p class="panel-value">CREDITOS VIGENTES</p>
                                    <p class="panel-value">CREDITOS CERRADOS</p>
                                    <p class="panel-value">CREDITOS RESTRUCTURADOS</p>
                                    <p class="panel-value">CREDITOS REFINANCIADOS</p>
                                    <p class="panel-value">CONSULTAS ULTIMOS 6 MESES</p>
                                    <p class="panel-value">DESCUENTOS VIGENTES A LA FECHA</p>
                                    <p class="panel-value">ANTIGUEDAD DESDE</p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <b class="panel-label">SECTOR FINANCIERO</b>
                                <div>
                                    <p class="panel-value">{{ $field["creditosCerrados"] }}</p>
                                    <p class="panel-value">{{ $field["creditoVigentes"] }}</p>
                                    <p class="panel-value">{{ $field["creditosCerrados"] }}</p>
                                    <p class="panel-value">{{ $field["creditosActualesNegativos"] }}</p>
                                    <p class="panel-value">{{ $field["consultadasUlt6meses"] }}</p>
                                    <p class="panel-value">{{ $field["desacuerdosALaFecha"] }}</p>
                                    <p class="panel-value">{{ $field["antiguedadDesde"] }}</p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <b class="panel-label">SECTOR COOPERATIVO</b>
                                <div>
                                    <p class="panel-value">{{ $field["creditosActualesNegativos"] }}</p>
                                    <p class="panel-value">{{ $field["creditoVigentes"] }}</p>
                                    <p class="panel-value">{{ $field["creditosCerrados"] }}</p>
                                    <p class="panel-value">{{ $field["creditosActualesNegativos"] }}</p>
                                    <p class="panel-value">{{ $field["consultadasUlt6meses"] }}</p>
                                    <p class="panel-value">{{ $field["desacuerdosALaFecha"] }}</p>
                                    <p class="panel-value">{{ $field["antiguedadDesde"] }}</p>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <b class="panel-label">SECTOR REAL</b>
                                <div>
                                    <p class="panel-value">{{ $field["cuentasAbiertasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["creditoVigentes"] }}</p>
                                    <p class="panel-value">{{ $field["creditosCerrados"] }}</p>
                                    <p class="panel-value">{{ $field["creditosActualesNegativos"] }}</p>
                                    <p class="panel-value">{{ $field["consultadasUlt6meses"] }}</p>
                                    <p class="panel-value">{{ $field["desacuerdosALaFecha"] }}</p>
                                    <p class="panel-value">{{ $field["antiguedadDesde"] }}</p>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <b class="panel-label">SECTOR TELCOS</b>
                                <div>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["creditoVigentes"] }}</p>
                                    <p class="panel-value">{{ $field["creditosCerrados"] }}</p>
                                    <p class="panel-value">{{ $field["creditosActualesNegativos"] }}</p>
                                    <p class="panel-value">{{ $field["consultadasUlt6meses"] }}</p>
                                    <p class="panel-value">{{ $field["desacuerdosALaFecha"] }}</p>
                                    <p class="panel-value">{{ $field["antiguedadDesde"] }}</p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <b class="panel-label">TOTAL COMO PRINCIPAL</b>
                                <div>
                                    <p class="panel-value">{{ $field["consultadasUlt6meses"] }}</p>
                                    <p class="panel-value">{{ $field["creditoVigentes"] }}</p>
                                    <p class="panel-value">{{ $field["creditosCerrados"] }}</p>
                                    <p class="panel-value">{{ $field["creditosActualesNegativos"] }}</p>
                                    <p class="panel-value">{{ $field["consultadasUlt6meses"] }}</p>
                                    <p class="panel-value">{{ $field["desacuerdosALaFecha"] }}</p>
                                    <p class="panel-value">{{ $field["antiguedadDesde"] }}</p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <b class="panel-label">TOTAL COMO CODEUDOR - OTROS</b>
                                <div>
                                    <p class="panel-value">{{ $field["consultadasUlt6meses"] }}</p>
                                    <p class="panel-value">{{ $field["creditoVigentes"] }}</p>
                                    <p class="panel-value">{{ $field["creditosCerrados"] }}</p>
                                    <p class="panel-value">{{ $field["creditosActualesNegativos"] }}</p>
                                    <p class="panel-value">{{ $field["consultadasUlt6meses"] }}</p>
                                    <p class="panel-value">{{ $field["desacuerdosALaFecha"] }}</p>
                                    <p class="panel-value">{{ $field["antiguedadDesde"] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel mb-3">
                    <div class="panel-heading">Tendencia de endeudamiento</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3">
                                <b class="panel-label">SALDOS Y MORAS</b>
                                <div>
                                    <p class="panel-value">Saldo Deuda Total en Mora (en miles)</p>
                                    <p class="panel-value">Saldo Deuda Total (en miles)</p>
                                    <p class="panel-value">Moras máx Sector Financiero</p>
                                    <p class="panel-value">Moras máx Sector Cooperativo</p>
                                    <p class="panel-value">Moras máx Sector Real</p>
                                    <p class="panel-value">Moras máx Sector Telcos</p>
                                    <p class="panel-value"><b>Total Moras Máximas</b></p>
                                    <p class="panel-value">Núm créditos con mora =30</p>
                                    <p class="panel-value">Núm créditos con mora >= 60</p>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <b class="panel-label">JUL 22</b>
                                <div>
                                    <p class="panel-value">{{ $field["creditosCerrados"] }}</p>
                                    <p class="panel-value">{{ $field["creditosCerrados"] }}</p>
                                    <p class="panel-value">{{ $field["creditosCerrados"] }}</p>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <b class="panel-label">JUN 22</b>
                                <div>
                                    <p class="panel-value">{{ $field["creditosActualesNegativos"] }}</p>
                                    <p class="panel-value">{{ $field["creditosActualesNegativos"] }}</p>
                                    <p class="panel-value">{{ $field["creditosActualesNegativos"] }}</p>
                                    <p class="panel-value">{{ $field["creditosActualesNegativos"] }}</p>
                                    <p class="panel-value">{{ $field["creditosActualesNegativos"] }}</p>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <b class="panel-label">MAY 22</b>
                                <div>
                                    <p class="panel-value">{{ $field["cuentasAbiertasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasAbiertasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasAbiertasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasAbiertasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasAbiertasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasAbiertasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasAbiertasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasAbiertasAHOCCB"] }}</p>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <b class="panel-label">ABR 22</b>
                                <div>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <b class="panel-label">MAR 22</b>
                                <div>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <b class="panel-label">FEB 22</b>
                                <div>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <b class="panel-label">ENE 22</b>
                                <div>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <b class="panel-label">DEC 21</b>
                                <div>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <b class="panel-label">NOV 21</b>
                                <div>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                    <p class="panel-value">{{ $field["cuentasCerradasAHOCCB"] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel mb-1">
                    <div class="panel-heading">Endeudamiento Actual</div>
                    <div class="panel-body text-center">
                        <div class="row">
                            <div class="col-md-2">
                                <b class="panel-label">CARTERAS</b>
                            </div>
                            <div class="col-md-1">
                                <b class="panel-label">CALIDAD</b>
                            </div>
                            <div class="col-md-1">
                                <b class="panel-label">NUM</b>
                            </div>
                            <div class="col-md-1">
                                <b class="panel-label">ESTADO ACTUAL</b>
                            </div>
                            <div class="col-md-1">
                                <b class="panel-label">CALF</b>
                            </div>
                            <div class="col-md-1">
                                <b class="panel-label">CUPO INICIAL</b>
                            </div>
                            <div class="col-md-1">
                                <b class="panel-label">SALDO ACTUAL</b>
                            </div>
                            <div class="col-md-1">
                                <b class="panel-label">SALDO EN MORA</b>
                            </div>
                            <div class="col-md-1">
                                <b class="panel-label">VALOR CUOTA</b>
                            </div>
                            <div class="col-md-1">
                                <b class="panel-label">% PART</b>
                            </div>
                            <div class="col-md-1">
                                <b class="panel-label">% DEUDA</b>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel mb-3">
                    <div class="panel-heading">Sector Financiero</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-2">
                                <p class="panel-value">Cartera bancaria</p>
                                <b class="panel-value">Total Sector Financiero</b>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-value">Principal</p>
                                <p class="panel-value"></p>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">{{ $field["desacuerdosALaFecha"] }}</p>
                                <p class="panel-value"></p>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">Esta en mora 120</p>
                                <p class="panel-value"></p>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">D</p>
                                <p class="panel-value"></p>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">93,742</p>
                                <b class="panel-label">93,742</b>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">94,971</p>
                                <b class="panel-label">94,971</b>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">12,069</p>
                                <b class="panel-label">12,069</b>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">1,543</p>
                                <b class="panel-label">1,543</b>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">87.3%</p>
                                <b class="panel-label">87.3%</b>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">101.3%</p>
                                <b class="panel-label">101.3%</b>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel mb-3">
                    <div class="panel-heading">Sector Cooperativo</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-2">
                                <p class="panel-value">Cartera bancaria</p>
                                <b class="panel-value">Total Sector Financiero</b>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-value">Principal</p>
                                <p class="panel-value"></p>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">{{ $field["desacuerdosALaFecha"] }}</p>
                                <p class="panel-value"></p>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">Esta en mora 120</p>
                                <p class="panel-value"></p>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">D</p>
                                <p class="panel-value"></p>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">93,742</p>
                                <b class="panel-label">93,742</b>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">94,971</p>
                                <b class="panel-label">94,971</b>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">12,069</p>
                                <b class="panel-label">12,069</b>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">1,543</p>
                                <b class="panel-label">1,543</b>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">87.3%</p>
                                <b class="panel-label">87.3%</b>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">101.3%</p>
                                <b class="panel-label">101.3%</b>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel mb-3">
                    <div class="panel-heading">Sector Real</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-2">
                                <p class="panel-value">Cartera bancaria</p>
                                <b class="panel-value">Total Sector Financiero</b>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-value">Principal</p>
                                <p class="panel-value"></p>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">{{ $field["desacuerdosALaFecha"] }}</p>
                                <p class="panel-value"></p>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">Esta en mora 120</p>
                                <p class="panel-value"></p>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">D</p>
                                <p class="panel-value"></p>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">93,742</p>
                                <b class="panel-label">93,742</b>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">94,971</p>
                                <b class="panel-label">94,971</b>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">12,069</p>
                                <b class="panel-label">12,069</b>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">1,543</p>
                                <b class="panel-label">1,543</b>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">87.3%</p>
                                <b class="panel-label">87.3%</b>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">101.3%</p>
                                <b class="panel-label">101.3%</b>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel mb-3">
                    <div class="panel-heading">Sector Telcos</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-2">
                                <p class="panel-value">Cartera bancaria</p>
                                <b class="panel-value">Total Sector Financiero</b>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-value">Principal</p>
                                <p class="panel-value"></p>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">{{ $field["desacuerdosALaFecha"] }}</p>
                                <p class="panel-value"></p>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">Esta en mora 120</p>
                                <p class="panel-value"></p>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">D</p>
                                <p class="panel-value"></p>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">93,742</p>
                                <b class="panel-label">93,742</b>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">94,971</p>
                                <b class="panel-label">94,971</b>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">12,069</p>
                                <b class="panel-label">12,069</b>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">1,543</p>
                                <b class="panel-label">1,543</b>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">87.3%</p>
                                <b class="panel-label">87.3%</b>
                            </div>
                            <div class="col-md-1">
                                <p class="panel-label">101.3%</p>
                                <b class="panel-label">101.3%</b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- HÁBITO DE PAGO DE OBLIGACIONES ABIERTAS / VIGENTES  -->
            <div class="col-md-12">
                <div class="panel panel-primary mb-3">
                    <div class="panel-heading text-center mb-1"><b>HÁBITO DE PAGO DE OBLIGACIONES ABIERTAS / VIGENTES</b></div>
                    <!-- SECTOR FINANCIERO -->
                    <div class="panel-heading">Sector Financiero</div>
                    <div class="panel-body text-center">
                        <div class="row">
                            <div class="col-auto">
                                <b class="panel-label">ENTIDAD INFORMANTE</b>
                                <div>
                                    <p class="panel-value">BCO DE BOGOTA</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">TIPO CUENTA</b>
                                <div>
                                    <p class="panel-value">AHO</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">NUM CTA 9 DIGITOS</b>
                                <div>
                                    <p class="panel-value">314342494</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">CALF</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">ESTADO DE LA OBLIGACION</b>
                                <div>
                                    <p class="panel-value">Activa</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA ACTUALIZACION</b>
                                <div>
                                    <p class="panel-value">20220630</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">ADJETIVO-FECHA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA APERTURA</b>
                                <div>
                                    <p class="panel-value">20081111</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA VENCIMIENTO</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">MORA MAXIMA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">47 MESES</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <b class="panel-label">DESACUERDO CON LA INF</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">ESTADO DEL TITULAR</b>
                                <div>
                                    <p class="panel-value">NORMAL</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">MARCA/CLASE</b>
                                <div>
                                    <p class="panel-value">NORMAL</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">TIPO GARANTIA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">VIR O CUPO INICIAL</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">SALDO ACTUAL (MILES $)</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA LIMITE PAGO</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA DEL PAGO</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">PERM</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">No. CHEQUES DEVUELTOS</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">CUOTAS/M/VIGENCIA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">% DEUDA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">OFICINA/DEUDOR</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTOR COOPERATIVO -->
                    <div class="panel-heading">Sector Cooperativo</div>
                    <div class="panel-body text-center">
                        <div class="row">
                            <div class="col-auto">
                                <b class="panel-label">ENTIDAD INFORMANTE</b>
                                <div>
                                    <p class="panel-value">BCO DE BOGOTA</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">TIPO CUENTA</b>
                                <div>
                                    <p class="panel-value">AHO</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">NUM CTA 9 DIGITOS</b>
                                <div>
                                    <p class="panel-value">314342494</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">CALF</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">ESTADO DE LA OBLIGACION</b>
                                <div>
                                    <p class="panel-value">Activa</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA ACTUALIZACION</b>
                                <div>
                                    <p class="panel-value">20220630</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">ADJETIVO-FECHA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA APERTURA</b>
                                <div>
                                    <p class="panel-value">20081111</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA VENCIMIENTO</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">MORA MAXIMA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">47 MESES</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <b class="panel-label">DESACUERDO CON LA INF</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">ESTADO DEL TITULAR</b>
                                <div>
                                    <p class="panel-value">NORMAL</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">MARCA/CLASE</b>
                                <div>
                                    <p class="panel-value">NORMAL</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">TIPO GARANTIA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">VIR O CUPO INICIAL</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">SALDO ACTUAL (MILES $)</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA LIMITE PAGO</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA DEL PAGO</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">PERM</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">No. CHEQUES DEVUELTOS</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">CUOTAS/M/VIGENCIA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">% DEUDA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">OFICINA/DEUDOR</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- SECTOR REAL -->
                    <div class="panel-heading">Sector Real</div>
                    <div class="panel-body text-center">
                        <div class="row">
                            <div class="col-auto">
                                <b class="panel-label">ENTIDAD INFORMANTE</b>
                                <div>
                                    <p class="panel-value">BCO DE BOGOTA</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">TIPO CUENTA</b>
                                <div>
                                    <p class="panel-value">AHO</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">NUM CTA 9 DIGITOS</b>
                                <div>
                                    <p class="panel-value">314342494</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">CALF</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">ESTADO DE LA OBLIGACION</b>
                                <div>
                                    <p class="panel-value">Activa</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA ACTUALIZACION</b>
                                <div>
                                    <p class="panel-value">20220630</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">ADJETIVO-FECHA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA APERTURA</b>
                                <div>
                                    <p class="panel-value">20081111</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA VENCIMIENTO</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">MORA MAXIMA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">47 MESES</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <b class="panel-label">DESACUERDO CON LA INF</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">ESTADO DEL TITULAR</b>
                                <div>
                                    <p class="panel-value">NORMAL</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">MARCA/CLASE</b>
                                <div>
                                    <p class="panel-value">NORMAL</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">TIPO GARANTIA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">VIR O CUPO INICIAL</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">SALDO ACTUAL (MILES $)</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA LIMITE PAGO</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA DEL PAGO</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">PERM</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">No. CHEQUES DEVUELTOS</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">CUOTAS/M/VIGENCIA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">% DEUDA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">OFICINA/DEUDOR</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- SECTOR TELCOS -->
                    <div class="panel-heading">Sector Telcos</div>
                    <div class="panel-body text-center">
                        <div class="row">
                            <div class="col-auto">
                                <b class="panel-label">ENTIDAD INFORMANTE</b>
                                <div>
                                    <p class="panel-value">BCO DE BOGOTA</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">TIPO CUENTA</b>
                                <div>
                                    <p class="panel-value">AHO</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">NUM CTA 9 DIGITOS</b>
                                <div>
                                    <p class="panel-value">314342494</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">CALF</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">ESTADO DE LA OBLIGACION</b>
                                <div>
                                    <p class="panel-value">Activa</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA ACTUALIZACION</b>
                                <div>
                                    <p class="panel-value">20220630</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">ADJETIVO-FECHA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA APERTURA</b>
                                <div>
                                    <p class="panel-value">20081111</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA VENCIMIENTO</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">MORA MAXIMA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">47 MESES</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <b class="panel-label">DESACUERDO CON LA INF</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">ESTADO DEL TITULAR</b>
                                <div>
                                    <p class="panel-value">NORMAL</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">MARCA/CLASE</b>
                                <div>
                                    <p class="panel-value">NORMAL</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">TIPO GARANTIA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">VIR O CUPO INICIAL</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">SALDO ACTUAL (MILES $)</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA LIMITE PAGO</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA DEL PAGO</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">PERM</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">No. CHEQUES DEVUELTOS</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">CUOTAS/M/VIGENCIA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">% DEUDA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">OFICINA/DEUDOR</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- HÁBITO DE PAGO DE OBLIGACIONES CERRADAS / INACTIVAS  -->
            <div class="col-md-12">
                <div class="panel panel-primary mb-3">
                    <div class="panel-heading text-center mb-1"><b>HÁBITO DE PAGO DE OBLIGACIONES AS / INACTIVAS</b></div>
                    <!-- SECTOR FINANCIERO -->
                    <div class="panel-heading">Sector Financiero</div>
                    <div class="panel-body text-center">
                        <div class="row">
                            <div class="col-auto">
                                <b class="panel-label">ENTIDAD INFORMANTE</b>
                                <div>
                                    <p class="panel-value">BCO DE BOGOTA</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">TIPO CUENTA</b>
                                <div>
                                    <p class="panel-value">AHO</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">NUM CTA 9 DIGITOS</b>
                                <div>
                                    <p class="panel-value">314342494</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">CALF</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">ESTADO DE LA OBLIGACION</b>
                                <div>
                                    <p class="panel-value">Activa</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA ACTUALIZACION</b>
                                <div>
                                    <p class="panel-value">20220630</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">ADJETIVO-FECHA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA APERTURA</b>
                                <div>
                                    <p class="panel-value">20081111</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA VENCIMIENTO</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">MORA MAXIMA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">47 MESES</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <b class="panel-label">DESACUERDO CON LA INF</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">ESTADO DEL TITULAR</b>
                                <div>
                                    <p class="panel-value">NORMAL</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">MARCA/CLASE</b>
                                <div>
                                    <p class="panel-value">NORMAL</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">TIPO GARANTIA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">VIR O CUPO INICIAL</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">SALDO ACTUAL (MILES $)</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA LIMITE PAGO</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA DEL PAGO</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">PERM</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">No. CHEQUES DEVUELTOS</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">CUOTAS/M/VIGENCIA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">% DEUDA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">OFICINA/DEUDOR</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- SECTOR COOPERATIVO -->
                    <div class="panel-heading">Sector Cooperativo</div>
                    <div class="panel-body text-center">
                        <div class="row">
                            <div class="col-auto">
                                <b class="panel-label">ENTIDAD INFORMANTE</b>
                                <div>
                                    <p class="panel-value">BCO DE BOGOTA</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">TIPO CUENTA</b>
                                <div>
                                    <p class="panel-value">AHO</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">NUM CTA 9 DIGITOS</b>
                                <div>
                                    <p class="panel-value">314342494</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">CALF</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">ESTADO DE LA OBLIGACION</b>
                                <div>
                                    <p class="panel-value">Activa</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA ACTUALIZACION</b>
                                <div>
                                    <p class="panel-value">20220630</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">ADJETIVO-FECHA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA APERTURA</b>
                                <div>
                                    <p class="panel-value">20081111</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA VENCIMIENTO</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">MORA MAXIMA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">47 MESES</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <b class="panel-label">DESACUERDO CON LA INF</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">ESTADO DEL TITULAR</b>
                                <div>
                                    <p class="panel-value">NORMAL</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">MARCA/CLASE</b>
                                <div>
                                    <p class="panel-value">NORMAL</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">TIPO GARANTIA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">VIR O CUPO INICIAL</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">SALDO ACTUAL (MILES $)</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA LIMITE PAGO</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA DEL PAGO</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">PERM</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">No. CHEQUES DEVUELTOS</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">CUOTAS/M/VIGENCIA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">% DEUDA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">OFICINA/DEUDOR</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- SECTOR REAL -->
                    <div class="panel-heading">Sector Real</div>
                    <div class="panel-body text-center">
                        <div class="row">
                            <div class="col-auto">
                                <b class="panel-label">ENTIDAD INFORMANTE</b>
                                <div>
                                    <p class="panel-value">BCO DE BOGOTA</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">TIPO CUENTA</b>
                                <div>
                                    <p class="panel-value">AHO</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">NUM CTA 9 DIGITOS</b>
                                <div>
                                    <p class="panel-value">314342494</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">CALF</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">ESTADO DE LA OBLIGACION</b>
                                <div>
                                    <p class="panel-value">Activa</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA ACTUALIZACION</b>
                                <div>
                                    <p class="panel-value">20220630</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">ADJETIVO-FECHA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA APERTURA</b>
                                <div>
                                    <p class="panel-value">20081111</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA VENCIMIENTO</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">MORA MAXIMA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">47 MESES</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <b class="panel-label">DESACUERDO CON LA INF</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">ESTADO DEL TITULAR</b>
                                <div>
                                    <p class="panel-value">NORMAL</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">MARCA/CLASE</b>
                                <div>
                                    <p class="panel-value">NORMAL</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">TIPO GARANTIA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">VIR O CUPO INICIAL</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">SALDO ACTUAL (MILES $)</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA LIMITE PAGO</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA DEL PAGO</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">PERM</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">No. CHEQUES DEVUELTOS</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">CUOTAS/M/VIGENCIA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">% DEUDA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">OFICINA/DEUDOR</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- SECTOR TELCOS -->
                    <div class="panel-heading">Sector Telcos</div>
                    <div class="panel-body text-center">
                        <div class="row">
                            <div class="col-auto">
                                <b class="panel-label">ENTIDAD INFORMANTE</b>
                                <div>
                                    <p class="panel-value">BCO DE BOGOTA</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">TIPO CUENTA</b>
                                <div>
                                    <p class="panel-value">AHO</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">NUM CTA 9 DIGITOS</b>
                                <div>
                                    <p class="panel-value">314342494</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">CALF</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">ESTADO DE LA OBLIGACION</b>
                                <div>
                                    <p class="panel-value">Activa</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA ACTUALIZACION</b>
                                <div>
                                    <p class="panel-value">20220630</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">ADJETIVO-FECHA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA APERTURA</b>
                                <div>
                                    <p class="panel-value">20081111</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA VENCIMIENTO</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">MORA MAXIMA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">47 MESES</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-auto">
                                <b class="panel-label">DESACUERDO CON LA INF</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">ESTADO DEL TITULAR</b>
                                <div>
                                    <p class="panel-value">NORMAL</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">MARCA/CLASE</b>
                                <div>
                                    <p class="panel-value">NORMAL</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">TIPO GARANTIA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">VIR O CUPO INICIAL</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">SALDO ACTUAL (MILES $)</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA LIMITE PAGO</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">FECHA DEL PAGO</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">PERM</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">No. CHEQUES DEVUELTOS</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">CUOTAS/M/VIGENCIA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">% DEUDA</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                            <div class="col">
                                <b class="panel-label">OFICINA/DEUDOR</b>
                                <div>
                                    <p class="panel-value">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
            <div class="col-12">
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
                        <!-- Sector Financiero -->
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
                    </tbody>
                </table>
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