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
                                    <p class="panel-value">{{  $resultado["JuridicaNacional"]["@attributes"]["identificacion"] }}</p>
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
                    <div class="panel-heading text-center"><b>HÁBITO DE PAGO DE OBLIGACIONES ABIERTAS / VIGENTES</b></div>
                    <div class="panel-heading">Perfil General</div>
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
                    <div class="panel-heading"></div>
                    @endforeach
                </div>
            </div>
            

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

            @if (isset($dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]) and count($dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"])>0 and count( $dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]) == count($dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"], 1))
            <div class="col-md-12">
                <div class="panel panel-primary mb-3">
                    <div class="panel-heading"><b>REGISTROS</b></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-2">
                                <b class="panel-label table-text">PAQUETE INFORMACIÓN:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]["PaqueteInformacion"] }}</p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <b class="panel-label table-text">NUMERO OBLIGACIONES:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]["NumeroObligaciones"] }}</p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <b class="panel-label table-text">TOTAL SALDO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]["TotalSaldo"] }}</p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <b class="panel-label table-text">PARTICIPACIÓN DEUDA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]["ParticipacionDeuda"] }}</p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <b class="panel-label table-text">NUMERO OBLIGACIONES DIA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]["NumeroObligacionesDia"] }}</p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <b class="panel-label table-text">SALDO OBLIGACIONES DIA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]["SaldoObligacionesDia"] }}</p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <b class="panel-label table-text">CUOTA OBLIGACIONES DIA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]["CuotaObligacionesDia"] }}</p>
                                </div>
                            </div>


                            <div class="col-md-2">
                                <b class="panel-label table-text">CANTIDAD OBLIGACIONES MORA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]["CantidadObligacionesMora"] }}</p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <b class="panel-label table-text">SALDO OBLIGACIONES MORA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]["SaldoObligacionesMora"] }}</p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <b class="panel-label table-text">CUOTA OBLIGACIONES MORA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]["CuotaObligacionesMora"] }}</p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR MORA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]["ValorMora"] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif



            @if (isset($resultado["CuentaCorriente"]))
            <div class="col-md-12">
                <div class="panel panel-primary mb-3">
                    <div class="panel-heading"><b>CUENTAS CORRIENTE</b></div>
                    @foreach($resultado["CuentaCorriente"] as $CuentaCorriente )
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-2">
                                <b class="panel-label table-text">BLOQUEADA:</b>
                                <div>
                                    <p class="panel-value">{{ $CuentaCorriente["@attributes"]["bloqueada"] }}</p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <b class="panel-label table-text">ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $CuentaCorriente["@attributes"]["entidad"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">NUMERO:</b>
                                <div>
                                    <p class="panel-value">{{ $CuentaCorriente["@attributes"]["numero"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">FECHA APERTURA:</b>
                                <div>
                                    <p class="panel-value">{{ $CuentaCorriente["@attributes"]["fechaApertura"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">CALIFICACION:</b>
                                <div>
                                    <p class="panel-value">{{ $CuentaCorriente["@attributes"]["calificacion"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">SITUACION TITULAR:</b>
                                <div>
                                    <p class="panel-value">{{ $CuentaCorriente["@attributes"]["situacionTitular"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">OFICINA:</b>
                                <div>
                                    <p class="panel-value">{{ $CuentaCorriente["@attributes"]["oficina"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">CIUDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $CuentaCorriente["@attributes"]["ciudad"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">IDENTIFICACION:</b>
                                <div>
                                    <p class="panel-value">{{ $CuentaCorriente["@attributes"]["identificacion"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">SECTOR:</b>
                                <div>
                                    <p class="panel-value">{{ $CuentaCorriente["@attributes"]["sector"] }}</p>
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
                        <div class="row">
                            <div class="col-2">
                                <b class="panel-label table-text">BLOQUEADA:</b>
                                <div>
                                    <p class="panel-value">{{ $CuentaCartera["@attributes"]["bloqueada"] }}</p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <b class="panel-label table-text">ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $CuentaCartera["@attributes"]["entidad"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">NUMERO:</b>
                                <div>
                                    <p class="panel-value">{{ $CuentaCartera["@attributes"]["numero"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">FECHA APERTURA:</b>
                                <div>
                                    <p class="panel-value">{{ $CuentaCartera["@attributes"]["fechaApertura"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">FECHA VENCIMIENTO:</b>
                                <div>
                                    <p class="panel-value">{{ $CuentaCartera["@attributes"]["fechaVencimiento"] }}</p>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <b class="panel-label text-center">COMPORTAMIENTO:</b>
                                <div>
                                    <p class="panel-value">{{ $CuentaCartera["@attributes"]["comportamiento"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">FORMA DE PAGO:</b>
                                <div>
                                    <p class="panel-value">{{ $CuentaCartera["@attributes"]["formaPago"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">PROBABILIDAD DE INCUMPLIMIENTO:</b>
                                <div>
                                    <p class="panel-value">{{ $CuentaCartera["@attributes"]["probabilidadIncumplimiento"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">CALIFICACION:</b>
                                <div>
                                    <p class="panel-value">{{ $CuentaCartera["@attributes"]["calificacion"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">SITUACION TITULAR:</b>
                                <div>
                                    <p class="panel-value">{{ $CuentaCartera["@attributes"]["situacionTitular"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">OFICINA:</b>
                                <div>
                                    <p class="panel-value">{{ $CuentaCartera["@attributes"]["oficina"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">CIUDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $CuentaCartera["@attributes"]["ciudad"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">TIPO DE IDENTIFICACION:</b>
                                <div>
                                    <p class="panel-value">{{ $CuentaCartera["@attributes"]["tipoIdentificacion"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">IDENTIFICACION:</b>
                                <div>
                                    <p class="panel-value">{{ $CuentaCartera["@attributes"]["identificacion"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">SECTOR:</b>
                                <div>
                                    <p class="panel-value">{{ $CuentaCartera["@attributes"]["sector"] }}</p>
                                </div>
                            </div>
                            <div class="col-2">
                                <b class="panel-label table-text">CALIFICACION HD:</b>
                                <div>
                                    <p class="panel-value">{{ $CuentaCartera["@attributes"]["calificacionHD"] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]) and count($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"])>0 and count( $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]) != count($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"], 1))
            <div class="col-md-12">
                <div class="panel panel-primary mb-3">
                    <div class="panel-heading"><b>SECTOR FINANCIOER AL DIA</b></div>
                    @foreach($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"] as $SectorFinancieroAlDia)
                    <div class="panel-body">
                        <div class="row">
                            @if (isset($SectorFinancieroAlDia["PaqueteInformacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PAQUETE INFORMACIÓN:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["PaqueteInformacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["IdentificadorLinea"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">IDENTIFICADOR LINEA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["IdentificadorLinea"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["TipoContrato"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO CONTRATO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["TipoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["EstadoContrato"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ESTADO CONTRATO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["EstadoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["TipoEntidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["TipoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["NombreEntidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["NombreEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["Ciudad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CIUDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["Ciudad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["Sucursal"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">SUCURSAL:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["Sucursal"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["NumeroObligacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NUMERO OBLIGACION:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["NumeroObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["Calidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CALIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["Calidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["EstadoObligacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ESTADO OBLIGACION:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["EstadoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["ModalidadCredito"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">MODALIDAD CREDITO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["ModalidadCredito"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["LineaCredito"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">LINEA CREDITO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["LineaCredito"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["Periodicidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PERIODICIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["Periodicidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["FechaApertura"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA APERTURA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["FechaApertura"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["FechaTerminacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA TERMINACION:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["FechaTerminacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["Calificacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CALIFICACION:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["Calificacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["ValorInicial"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR INICIAL:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["ValorInicial"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["SaldoObligacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">SALDO OBLIGATORIO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["SaldoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["ValorMora"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR MORAL:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["ValorMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["ValorCuota"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR CUOTA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["ValorCuota"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["TipoMoneda"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO MONEDA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["TipoMoneda"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["CuotasCanceladas"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">COUTAS CANCELADAS:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["CuotasCanceladas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["TipoGarantia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO GARANTIA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["TipoGarantia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["CubrimientoGarantia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CUBRIMIENTO GARANTIA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["CubrimientoGarantia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["MoraMaxima"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">MORA MAXIMA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["MoraMaxima"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["Comportamientos"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">COMPORTAMIENTOS:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["Comportamientos"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["ParticipacionDeuda"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PARTICIPACION DEUDA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["ParticipacionDeuda"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["ProbabilidadNoPago"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PROBABILIDAD NO PAGO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["ProbabilidadNoPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["FechaCorte"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA CORTE:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["FechaCorte"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["ModoExtincion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">MODO EXTINCION:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["ModoExtincion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["FechaPago"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA PAGO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["FechaPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["FechaPermanencia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA PERMANENCIA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["FechaPermanencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["NumeroReestructuraciones"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NO REESTRACTURACIONES:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["NumeroReestructuraciones"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["NaturalezaReestructuracion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NATURALEZA REESTRICTURACION:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["NaturalezaReestructuracion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["TipoEntidadOriginadoraCartera"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO ENTIDAD ORIDINADORA CARTERA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["TipoEntidadOriginadoraCartera"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["EntidadOriginadoraCartera"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ENTIDAD ORIDINADORA CARTERA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["EntidadOriginadoraCartera"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["TipoPago"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO PAGO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["TipoPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["EstadoTitular"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ESTADO TITULAR:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["EstadoTitular"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["NumeroCuotasPactadas"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NO CUOTAS PACTADAS:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["NumeroCuotasPactadas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["NumeroCuotasMora"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NO COUTAS MORA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["NumeroCuotasMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["Reestructurado"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">REESTRUCTADO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroAlDia["Reestructurado"] }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="panel-heading"></div>
                    @endforeach
                </div>
            </div>
            @endif

            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]) and count($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"])>0 and count( $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]) == count($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"], 1))
            <div class="col-md-12">
                <div class="panel panel-primary mb-3">
                    <div class="panel-heading"><b>SECTOR FINANCIOER AL DIA</b></div>
                    <div class="panel-body">
                        <div class="row">
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["PaqueteInformacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PAQUETE INFORMACIÓN:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["PaqueteInformacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["IdentificadorLinea"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">IDENTIFICADOR LINEA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["IdentificadorLinea"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["TipoContrato"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO CONTRATO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["TipoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["EstadoContrato"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ESTADO CONTRATO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["EstadoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["TipoEntidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["TipoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["NombreEntidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["NombreEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Ciudad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CIUDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Ciudad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Sucursal"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">SUCURSAL:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Sucursal"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["NumeroObligacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NUMERO OBLIGACION:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["NumeroObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Calidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CALIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Calidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["EstadoObligacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ESTADO OBLIGACION:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["EstadoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ModalidadCredito"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">MODALIDAD CREDITO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ModalidadCredito"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["LineaCredito"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">LINEA CREDITO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["LineaCredito"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Periodicidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PERIODICIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Periodicidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["FechaApertura"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA APERTURA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["FechaApertura"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["FechaTerminacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA TERMINACION:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["FechaTerminacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Calificacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CALIFICACION:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Calificacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ValorInicial"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR INICIAL:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ValorInicial"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["SaldoObligacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">SALDO OBLIGATORIO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["SaldoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ValorMora"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR MORAL:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ValorMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ValorCuota"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR CUOTA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ValorCuota"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["TipoMoneda"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO MONEDA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["TipoMoneda"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["CuotasCanceladas"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">COUTAS CANCELADAS:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["CuotasCanceladas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["TipoGarantia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO GARANTIA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["TipoGarantia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["CubrimientoGarantia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CUBRIMIENTO GARANTIA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["CubrimientoGarantia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["MoraMaxima"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">MORA MAXIMA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["MoraMaxima"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Comportamientos"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">COMPORTAMIENTOS:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Comportamientos"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ParticipacionDeuda"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PARTICIPACION DEUDA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ParticipacionDeuda"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ProbabilidadNoPago"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PROBABILIDAD NO PAGO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ProbabilidadNoPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["FechaCorte"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA CORTE:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["FechaCorte"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ModoExtincion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">MODO EXTINCION:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ModoExtincion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["FechaPago"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA PAGO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["FechaPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["FechaPermanencia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA PERMANENCIA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["FechaPermanencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["NumeroReestructuraciones"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NO REESTRACTURACIONES:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["NumeroReestructuraciones"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["NaturalezaReestructuracion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NATURALEZA REESTRICTURACION:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["NaturalezaReestructuracion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["TipoEntidadOriginadoraCartera"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO ENTIDAD ORIDINADORA CARTERA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["TipoEntidadOriginadoraCartera"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["EntidadOriginadoraCartera"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ENTIDAD ORIDINADORA CARTERA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["EntidadOriginadoraCartera"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["TipoPago"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO PAGO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["TipoPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["EstadoTitular"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ESTADO TITULAR:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["EstadoTitular"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["NumeroCuotasPactadas"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NO CUOTAS PACTADAS:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["NumeroCuotasPactadas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["NumeroCuotasMora"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NO COUTAS MORA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["NumeroCuotasMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Reestructurado"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">REESTRUCTADO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Reestructurado"] }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]) and count($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"])>0 and count( $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]) != count($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"], 1))
            <div class="col-md-12">
                <div class="panel panel-primary mb-3">
                    <div class="panel-heading"><b>SECTOR FINANCIERO EXTINGUIDAS</b></div>
                    @foreach($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"] as $SectorFinancieroExtinguidas)
                    <div class="panel-body">
                        <div class="row">
                            @if (isset($SectorFinancieroExtinguidas["PaqueteInformacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PAQUETE INFORMACIÓN:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["PaqueteInformacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["IdentificadorLinea"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">IDENTIFICADOR LINEA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["IdentificadorLinea"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["TipoContrato"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO CONTRATO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["TipoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["EstadoContrato"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ESTADO CONTRATO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["EstadoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["TipoEntidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["TipoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["NombreEntidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["NombreEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["Ciudad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CIUDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["Ciudad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["Sucursal"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">SUCURSAL:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["Sucursal"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["NumeroObligacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NUMERO OBLIGACION:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["NumeroObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["Calidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CALIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["Calidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["EstadoObligacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ESTADO OBLIGACION:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["EstadoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["ModalidadCredito"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">MODALIDAD CREDITO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["ModalidadCredito"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["LineaCredito"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">LINEA CREDITO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["LineaCredito"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["Periodicidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PERIODICIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["Periodicidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["FechaApertura"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA APERTURA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["FechaApertura"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["FechaTerminacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA TERMINACION:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["FechaTerminacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["Calificacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CALIFICACION:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["Calificacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["ValorInicial"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR INICIAL:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["ValorInicial"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["SaldoObligacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">SALDO OBLIGATORIO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["SaldoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["ValorMora"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR MORAL:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["ValorMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["ValorCuota"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR CUOTA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["ValorCuota"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["TipoMoneda"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO MONEDA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["TipoMoneda"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["CuotasCanceladas"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">COUTAS CANCELADAS:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["CuotasCanceladas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["TipoGarantia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO GARANTIA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["TipoGarantia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["CubrimientoGarantia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CUBRIMIENTO GARANTIA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["CubrimientoGarantia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["MoraMaxima"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">MORA MAXIMA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["MoraMaxima"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["Comportamientos"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">COMPORTAMIENTOS:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["Comportamientos"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["ParticipacionDeuda"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PARTICIPACION DEUDA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["ParticipacionDeuda"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["ProbabilidadNoPago"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PROBABILIDAD NO PAGO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["ProbabilidadNoPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["FechaCorte"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA CORTE:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["FechaCorte"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["ModoExtincion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">MODO EXTINCION:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["ModoExtincion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["FechaPago"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA PAGO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["FechaPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["FechaPermanencia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA PERMANENCIA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["FechaPermanencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["NumeroReestructuraciones"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NO REESTRACTURACIONES:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["NumeroReestructuraciones"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["NaturalezaReestructuracion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NATURALEZA REESTRICTURACION:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["NaturalezaReestructuracion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["TipoEntidadOriginadoraCartera"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO ENTIDAD ORIDINADORA CARTERA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["TipoEntidadOriginadoraCartera"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["EntidadOriginadoraCartera"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ENTIDAD ORIDINADORA CARTERA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["EntidadOriginadoraCartera"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["TipoPago"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO PAGO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["TipoPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["EstadoTitular"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ESTADO TITULAR:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["EstadoTitular"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["NumeroCuotasPactadas"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NO CUOTAS PACTADAS:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["NumeroCuotasPactadas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["NumeroCuotasMora"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NO COUTAS MORA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["NumeroCuotasMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["Reestructurado"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">REESTRUCTADO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorFinancieroExtinguidas["Reestructurado"] }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="panel-heading"></div>
                    @endforeach
                </div>
            </div>
            @endif

            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]) and count($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"])>0 and count( $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]) == count($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"], 1))
            <div class="col-md-12">
                <div class="panel panel-primary mb-3">
                    <div class="panel-heading"><b>SECTOR FINANCIERO EXTINGUIDAS</b></div>
                    <div class="panel-body">
                        <div class="row">
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["PaqueteInformacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PAQUETE INFORMACIÓN:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["PaqueteInformacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["IdentificadorLinea"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">IDENTIFICADOR LINEA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["IdentificadorLinea"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["TipoContrato"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO CONTRATO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["TipoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["EstadoContrato"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ESTADO CONTRATO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["EstadoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["TipoEntidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["TipoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["NombreEntidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["NombreEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Ciudad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CIUDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Ciudad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Sucursal"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">SUCURSAL:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Sucursal"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["NumeroObligacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NUMERO OBLIGACION:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["NumeroObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Calidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CALIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Calidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["EstadoObligacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ESTADO OBLIGACION:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["EstadoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ModalidadCredito"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">MODALIDAD CREDITO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ModalidadCredito"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["LineaCredito"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">LINEA CREDITO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["LineaCredito"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Periodicidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PERIODICIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Periodicidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["FechaApertura"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA APERTURA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["FechaApertura"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["FechaTerminacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA TERMINACION:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["FechaTerminacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Calificacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CALIFICACION:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Calificacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ValorInicial"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR INICIAL:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ValorInicial"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["SaldoObligacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">SALDO OBLIGATORIO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["SaldoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ValorMora"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR MORAL:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ValorMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ValorCuota"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR CUOTA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ValorCuota"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["TipoMoneda"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO MONEDA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["TipoMoneda"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["CuotasCanceladas"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">COUTAS CANCELADAS:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["CuotasCanceladas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["TipoGarantia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO GARANTIA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["TipoGarantia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["CubrimientoGarantia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CUBRIMIENTO GARANTIA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["CubrimientoGarantia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["MoraMaxima"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">MORA MAXIMA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["MoraMaxima"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Comportamientos"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">COMPORTAMIENTOS:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Comportamientos"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ParticipacionDeuda"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PARTICIPACION DEUDA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ParticipacionDeuda"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ProbabilidadNoPago"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PROBABILIDAD NO PAGO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ProbabilidadNoPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["FechaCorte"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA CORTE:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["FechaCorte"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ModoExtincion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">MODO EXTINCION:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ModoExtincion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["FechaPago"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA PAGO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["FechaPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["FechaPermanencia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA PERMANENCIA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["FechaPermanencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["NumeroReestructuraciones"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NO REESTRACTURACIONES:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["NumeroReestructuraciones"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["NaturalezaReestructuracion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NATURALEZA REESTRICTURACION:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["NaturalezaReestructuracion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["TipoEntidadOriginadoraCartera"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO ENTIDAD ORIDINADORA CARTERA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["TipoEntidadOriginadoraCartera"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["EntidadOriginadoraCartera"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ENTIDAD ORIDINADORA CARTERA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["EntidadOriginadoraCartera"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["TipoPago"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO PAGO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["TipoPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["EstadoTitular"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ESTADO TITULAR:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["EstadoTitular"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["NumeroCuotasPactadas"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NO CUOTAS PACTADAS:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["NumeroCuotasPactadas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["NumeroCuotasMora"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NO COUTAS MORA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["NumeroCuotasMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Reestructurado"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">REESTRUCTADO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Reestructurado"] }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif




            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]) and count($dato["Tercero"]["SectorRealAlDia"]["Obligacion"])>0 and count( $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]) != count($dato["Tercero"]["SectorRealAlDia"]["Obligacion"], 1))
            <div class="col-md-12">
                <div class="panel panel-primary mb-3">
                    <div class="panel-heading"><b>SECTOR REAL AL DIA</b></div>
                    @foreach($dato["Tercero"]["SectorRealAlDia"]["Obligacion"] as $SectorRealAlDia)
                    <div class="panel-body">
                        <div class="row">
                            @if (isset($SectorRealAlDia["PaqueteInformacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PAQUETE INFORMACIÓN:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["PaqueteInformacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["IdentificadorLinea"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">IDENTIFICADOR LINEA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["IdentificadorLinea"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["TipoContrato"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO CONTRATO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["TipoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["EstadoContrato"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ESTADO CONTRATO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["EstadoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["TipoEntidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["TipoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["NombreEntidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["NombreEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["Ciudad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CIUDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["Ciudad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["Sucursal"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">SUCURSAL:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["Sucursal"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["NumeroObligacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NUMERO OBLIGACION:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["NumeroObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["Calidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CALIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["Calidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["EstadoObligacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ESTADO OBLIGACION:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["EstadoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["LineaCredito"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">LINEA CREDITO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["LineaCredito"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["Periodicidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PERIODICIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["Periodicidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["FechaApertura"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA APERTURA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["FechaApertura"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["FechaTerminacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA TERMINACION:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["FechaTerminacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["ValorInicial"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR INICIAL:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["ValorInicial"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["SaldoObligacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">SALDO OBLIGATORIO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["SaldoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["ValorMora"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR MORAL:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["ValorMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["ValorCuota"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR CUOTA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["ValorCuota"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["CuotasCanceladas"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">COUTAS CANCELADAS:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["CuotasCanceladas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["TipoGarantia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO GARANTIA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["TipoGarantia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["MoraMaxima"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">MORA MAXIMA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["MoraMaxima"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["Comportamientos"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">COMPORTAMIENTOS:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["Comportamientos"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["ParticipacionDeuda"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PARTICIPACION DEUDA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["ParticipacionDeuda"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["FechaCorte"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA CORTE:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["FechaCorte"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["ModoExtincion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">MODO EXTINCION:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["ModoExtincion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["FechaPago"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA PAGO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["FechaPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["FechaPermanencia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA PERMANENCIA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["FechaPermanencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["ChequesDevueltos"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CHEQUES DEVUELTOS:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["ChequesDevueltos"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["TipoPago"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO PAGO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["TipoPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["EstadoTitular"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ESTADO TITULAR:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["EstadoTitular"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["NumeroCuotasPactadas"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NO CUOTAS PACTADAS:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["NumeroCuotasPactadas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["NumeroCuotasMora"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NO COUTAS MORA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["NumeroCuotasMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["ValorCargoFijo"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR CARGO FIJOA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["ValorCargoFijo"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["ClausulaPermanencia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CLAUSULA PERMANENCIA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["ClausulaPermanencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["Reestructurado"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">REESTRUCTADO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["Reestructurado"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["Vigencia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VIGENCIA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["Vigencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["NumeroMesesContrato"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">N0 MESES CONTRATO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["NumeroMesesContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["NumeroMesesClausula"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NO MESES CLAUSULA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealAlDia["NumeroMesesClausula"] }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="panel-heading"></div>
                    @endforeach
                </div>
            </div>
            @endif

            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]) and count($dato["Tercero"]["SectorRealAlDia"]["Obligacion"])>0 and count( $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]) == count($dato["Tercero"]["SectorRealAlDia"]["Obligacion"], 1))
            <div class="col-md-12">
                <div class="panel panel-primary mb-3">
                    <div class="panel-heading"><b>SECTOR REAL AL DIA</b></div>
                    <div class="panel-body">
                        <div class="row">
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["PaqueteInformacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PAQUETE INFORMACIÓN:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["PaqueteInformacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["IdentificadorLinea"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">IDENTIFICADOR LINEA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["IdentificadorLinea"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["TipoContrato"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO CONTRATO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["TipoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["EstadoContrato"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ESTADO CONTRATO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["EstadoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["TipoEntidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["TipoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["NombreEntidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["NombreEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Ciudad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CIUDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Ciudad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Sucursal"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">SUCURSAL:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Sucursal"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["NumeroObligacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NUMERO OBLIGACION:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["NumeroObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Calidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CALIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Calidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["EstadoObligacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ESTADO OBLIGACION:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["EstadoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["LineaCredito"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">LINEA CREDITO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["LineaCredito"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Periodicidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PERIODICIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Periodicidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["FechaApertura"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA APERTURA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["FechaApertura"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["FechaTerminacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA TERMINACION:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["FechaTerminacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ValorInicial"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR INICIAL:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ValorInicial"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["SaldoObligacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">SALDO OBLIGATORIO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["SaldoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ValorMora"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR MORAL:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ValorMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ValorCuota"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR CUOTA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ValorCuota"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["CuotasCanceladas"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">COUTAS CANCELADAS:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["CuotasCanceladas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["TipoGarantia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO GARANTIA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["TipoGarantia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["MoraMaxima"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">MORA MAXIMA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["MoraMaxima"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Comportamientos"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">COMPORTAMIENTOS:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Comportamientos"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ParticipacionDeuda"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PARTICIPACION DEUDA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ParticipacionDeuda"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["FechaCorte"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA CORTE:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["FechaCorte"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ModoExtincion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">MODO EXTINCION:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ModoExtincion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["FechaPago"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA PAGO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["FechaPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["FechaPermanencia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA PERMANENCIA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["FechaPermanencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ChequesDevueltos"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CHEQUES DEVUELTOS:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ChequesDevueltos"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["TipoPago"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO PAGO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["TipoPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["EstadoTitular"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ESTADO TITULAR:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["EstadoTitular"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["NumeroCuotasPactadas"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NO CUOTAS PACTADAS:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["NumeroCuotasPactadas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["NumeroCuotasMora"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NO COUTAS MORA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["NumeroCuotasMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ValorCargoFijo"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR CARGO FIJOA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ValorCargoFijo"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ClausulaPermanencia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CLAUSULA PERMANENCIA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ClausulaPermanencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Reestructurado"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">REESTRUCTADO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Reestructurado"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Vigencia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VIGENCIA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Vigencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["NumeroMesesContrato"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">N0 MESES CONTRATO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["NumeroMesesContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["NumeroMesesClausula"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NO MESES CLAUSULA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["NumeroMesesClausula"] }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif




            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]) and count($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"])>0 and count( $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]) != count($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"], 1))
            <div class="col-md-12">
                <div class="panel panel-primary mb-3">
                    <div class="panel-heading"><b>SECTOR REAL EXTINGUIDAS</b></div>
                    @foreach($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"] as $SectorRealExtinguidas)
                    <div class="panel-body">
                        <div class="row">
                            @if (isset($SectorRealExtinguidas["PaqueteInformacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PAQUETE INFORMACIÓN:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["PaqueteInformacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["IdentificadorLinea"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">IDENTIFICADOR LINEA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["IdentificadorLinea"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["TipoContrato"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO CONTRATO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["TipoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["EstadoContrato"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ESTADO CONTRATO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["EstadoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["TipoEntidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["TipoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["NombreEntidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["NombreEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["Ciudad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CIUDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["Ciudad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["Sucursal"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">SUCURSAL:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["Sucursal"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["NumeroObligacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NUMERO OBLIGACION:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["NumeroObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["Calidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CALIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["Calidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["EstadoObligacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ESTADO OBLIGACION:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["EstadoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["LineaCredito"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">LINEA CREDITO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["LineaCredito"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["Periodicidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PERIODICIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["Periodicidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["FechaApertura"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA APERTURA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["FechaApertura"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["FechaTerminacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA TERMINACION:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["FechaTerminacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["ValorInicial"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR INICIAL:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["ValorInicial"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["SaldoObligacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">SALDO OBLIGATORIO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["SaldoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["ValorMora"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR MORAL:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["ValorMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["ValorCuota"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR CUOTA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["ValorCuota"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["CuotasCanceladas"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">COUTAS CANCELADAS:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["CuotasCanceladas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["TipoGarantia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO GARANTIA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["TipoGarantia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["MoraMaxima"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">MORA MAXIMA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["MoraMaxima"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["Comportamientos"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">COMPORTAMIENTOS:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["Comportamientos"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["ParticipacionDeuda"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PARTICIPACION DEUDA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["ParticipacionDeuda"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["FechaCorte"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA CORTE:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["FechaCorte"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["ModoExtincion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">MODO EXTINCION:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["ModoExtincion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["FechaPago"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA PAGO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["FechaPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["FechaPermanencia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA PERMANENCIA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["FechaPermanencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["ChequesDevueltos"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CHEQUES DEVUELTOS:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["ChequesDevueltos"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["TipoPago"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO PAGO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["TipoPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["EstadoTitular"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ESTADO TITULAR:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["EstadoTitular"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["NumeroCuotasPactadas"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NO CUOTAS PACTADAS:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["NumeroCuotasPactadas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["NumeroCuotasMora"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NO COUTAS MORA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["NumeroCuotasMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["ValorCargoFijo"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR CARGO FIJOA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["ValorCargoFijo"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["ClausulaPermanencia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CLAUSULA PERMANENCIA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["ClausulaPermanencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["Reestructurado"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">REESTRUCTADO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["Reestructurado"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["Vigencia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VIGENCIA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["Vigencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["NumeroMesesContrato"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">N0 MESES CONTRATO:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["NumeroMesesContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["NumeroMesesClausula"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NO MESES CLAUSULA:</b>
                                <div>
                                    <p class="panel-value">{{ $SectorRealExtinguidas["NumeroMesesClausula"] }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="panel-heading"></div>
                    @endforeach
                </div>
            </div>
            @endif


            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]) and count($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"])>0 and count( $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]) == count($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"], 1))
            <div class="col-md-12">
                <div class="panel panel-primary mb-3">
                    <div class="panel-heading"><b>SECTOR REAL EXTINGUIDAS</b></div>
                    <div class="panel-body">
                        <div class="row">
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["PaqueteInformacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PAQUETE INFORMACIÓN:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["PaqueteInformacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["IdentificadorLinea"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">IDENTIFICADOR LINEA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["IdentificadorLinea"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["TipoContrato"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO CONTRATO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["TipoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["EstadoContrato"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ESTADO CONTRATO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["EstadoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["TipoEntidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["TipoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["NombreEntidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["NombreEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Ciudad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CIUDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Ciudad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Sucursal"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">SUCURSAL:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Sucursal"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["NumeroObligacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NUMERO OBLIGACION:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["NumeroObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Calidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CALIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Calidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["EstadoObligacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ESTADO OBLIGACION:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["EstadoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["LineaCredito"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">LINEA CREDITO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["LineaCredito"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Periodicidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PERIODICIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Periodicidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["FechaApertura"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA APERTURA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["FechaApertura"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["FechaTerminacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA TERMINACION:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["FechaTerminacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ValorInicial"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR INICIAL:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ValorInicial"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["SaldoObligacion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">SALDO OBLIGATORIO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["SaldoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ValorMora"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR MORAL:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ValorMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ValorCuota"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR CUOTA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ValorCuota"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["CuotasCanceladas"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">COUTAS CANCELADAS:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["CuotasCanceladas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["TipoGarantia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO GARANTIA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["TipoGarantia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["MoraMaxima"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">MORA MAXIMA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["MoraMaxima"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Comportamientos"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">COMPORTAMIENTOS:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Comportamientos"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ParticipacionDeuda"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">PARTICIPACION DEUDA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ParticipacionDeuda"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["FechaCorte"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA CORTE:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["FechaCorte"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ModoExtincion"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">MODO EXTINCION:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ModoExtincion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["FechaPago"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA PAGO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["FechaPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["FechaPermanencia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA PERMANENCIA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["FechaPermanencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ChequesDevueltos"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CHEQUES DEVUELTOS:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ChequesDevueltos"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["TipoPago"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">TIPO PAGO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["TipoPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["EstadoTitular"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">ESTADO TITULAR:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["EstadoTitular"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["NumeroCuotasPactadas"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NO CUOTAS PACTADAS:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["NumeroCuotasPactadas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["NumeroCuotasMora"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NO COUTAS MORA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["NumeroCuotasMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ValorCargoFijo"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VALOR CARGO FIJOA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ValorCargoFijo"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ClausulaPermanencia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CLAUSULA PERMANENCIA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ClausulaPermanencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Reestructurado"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">REESTRUCTADO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Reestructurado"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Vigencia"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">VIGENCIA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Vigencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["NumeroMesesContrato"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">N0 MESES CONTRATO:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["NumeroMesesContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["NumeroMesesClausula"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NO MESES CLAUSULA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["NumeroMesesClausula"] }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if (isset($dato["Tercero"]["HuellaConsulta"]["Consulta"]) and count($dato["Tercero"]["HuellaConsulta"]["Consulta"])>0 and count( $dato["Tercero"]["HuellaConsulta"]["Consulta"]) != count($dato["Tercero"]["HuellaConsulta"]["Consulta"], 1))
            <div class="col-md-12">
                <div class="panel panel-primary mb-3">
                    <div class="panel-heading"><b>HUELLAS CONSULTA</b></div>
                    @foreach($dato["Tercero"]["HuellaConsulta"]["Consulta"] as $HuellaConsulta)
                    <div class="panel-body">
                        <div class="row">
                            @if (isset($HuellaConsulta["FechaConsulta"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA CONSULTA:</b>
                                <div>
                                    <p class="panel-value">{{ $HuellaConsulta["FechaConsulta"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($HuellaConsulta["NombreTipoEntidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NOMBRE TIPO ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $HuellaConsulta["NombreTipoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($HuellaConsulta["NombreEntidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $HuellaConsulta["NombreEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($HuellaConsulta["Sucursal"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">SUCURSAL:</b>
                                <div>
                                    <p class="panel-value">{{ $HuellaConsulta["Sucursal"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($HuellaConsulta["Ciudad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CIUDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $HuellaConsulta["Ciudad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($HuellaConsulta["MotivoConsulta"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">MOTIVO CONSULTA:</b>
                                <div>
                                    <p class="panel-value">{{ $HuellaConsulta["MotivoConsulta"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($HuellaConsulta["CodigoTipoEntidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CODIGO TIPO ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $HuellaConsulta["CodigoTipoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($HuellaConsulta["CodigoEntidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CODIGO ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $HuellaConsulta["CodigoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="panel-heading"></div>
                    @endforeach
                </div>
            </div>
            @endif

            @if (isset($dato["Tercero"]["HuellaConsulta"]["Consulta"]) and count($dato["Tercero"]["HuellaConsulta"]["Consulta"])>0 and count( $dato["Tercero"]["HuellaConsulta"]["Consulta"]) == count($dato["Tercero"]["HuellaConsulta"]["Consulta"], 1))
            <div class="col-md-12">
                <div class="panel panel-primary mb-3">
                    <div class="panel-heading"><b>HUELLAS CONSULTA</b></div>
                    <div class="panel-body">
                        <div class="row">
                            @if (isset($dato["Tercero"]["HuellaConsulta"]["Consulta"]["FechaConsulta"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">FECHA CONSULTA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["HuellaConsulta"]["Consulta"]["FechaConsulta"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["HuellaConsulta"]["Consulta"]["NombreTipoEntidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NOMBRE TIPO ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["HuellaConsulta"]["Consulta"]["NombreTipoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["HuellaConsulta"]["Consulta"]["NombreEntidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["HuellaConsulta"]["Consulta"]["NombreEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["HuellaConsulta"]["Consulta"]["Sucursal"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">SUCURSAL:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["HuellaConsulta"]["Consulta"]["Sucursal"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["HuellaConsulta"]["Consulta"]["Ciudad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CIUDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["HuellaConsulta"]["Consulta"]["Ciudad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["HuellaConsulta"]["Consulta"]["MotivoConsulta"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">MOTIVO CONSULTA:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["HuellaConsulta"]["Consulta"]["MotivoConsulta"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["HuellaConsulta"]["Consulta"]["CodigoTipoEntidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CODIGO TIPO ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["HuellaConsulta"]["Consulta"]["CodigoTipoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["HuellaConsulta"]["Consulta"]["CodigoEntidad"]))
                            <div class="col-md-2">
                                <b class="panel-label table-text">CODIGO ENTIDAD:</b>
                                <div>
                                    <p class="panel-value">{{ $dato["Tercero"]["HuellaConsulta"]["Consulta"]["CodigoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

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
<li class="breadcrumb-item"><a href="{{ url('cifin') }}">Cifin</a></li>
<li class="breadcrumb-item active">Consulta</li>
@endsection