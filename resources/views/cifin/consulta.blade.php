@extends('layouts.app2')

@section('content')
<div class="container-fluid">
    <div tabindex="0" aria-label="Loading" class="vld-overlay is-active is-full-page" style="display: none;">
        <div class="vld-background"></div>
        <div class="vld-icon">
            <svg viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg" width="64" height="64" stroke="#0CEDB0">
                <g fill="none" fill-rule="evenodd">
                    <g transform="translate(1 1)" stroke-width="2">
                        <circle stroke-opacity=".25" cx="18" cy="18" r="18"></circle>
                        <path d="M36 18c0-9.94-8.06-18-18-18">
                            <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="0.8s" repeatCount="indefinite"></animateTransform>
                        </path>
                    </g>
                </g>
            </svg>
        </div>
    </div>
    <div>
        @if ($resultado)
        @foreach($resultado as $dato)
        <div id="consulta-container" class="row">
            <div data-v-1fb519a4="" class="col-6">
                <div data-v-1fb519a4="" class="panel mb-3">
                    <div data-v-1fb519a4="" class="panel-heading"><b data-v-1fb519a4="">RESULTADO DE LA CONSULTA</b></div>
                    <div data-v-1fb519a4="" class="panel-body">
                        <div data-v-1fb519a4="" class="row">
                            <!---->
                            @if ($dato["Tercero"]["TipoIdentificacion"])
                            <div data-v-1fb519a4="" class="col-6">
                                <b data-v-1fb519a4="" class="panel-label">TIPO IDENTIFICACIÓN:</b>
                                <div data-v-1fb519a4="">
                                    <p data-v-1fb519a4="" class="panel-value">{{ $dato["Tercero"]["TipoIdentificacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["Estado"]))
                            <div data-v-1fb519a4="" class="col-6">
                                <b data-v-1fb519a4="" class="panel-label">EST. DOCUMENTO:</b>
                                <div data-v-1fb519a4="">
                                    <p data-v-1fb519a4="" class="panel-value">{{ $dato["Tercero"]["Estado"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["Fecha"]))
                            <div data-v-1fb519a4="" class="col-6">
                                <b data-v-1fb519a4="" class="panel-label">FECHA:</b>
                                <div data-v-1fb519a4="">
                                    <p data-v-1fb519a4="" class="panel-value">{{ $dato["Tercero"]["Fecha"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["NumeroIdentificacion"]))
                            <div data-v-1fb519a4="" class="col-6">
                                <b data-v-1fb519a4="" class="panel-label">No. IDENTIFICACIÓN:</b>
                                <div data-v-1fb519a4="">
                                    <p data-v-1fb519a4="" class="panel-value">{{ $dato["Tercero"]["NumeroIdentificacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["FechaExpedicion"]))
                            <div data-v-1fb519a4="" class="col-6">
                                <b data-v-1fb519a4="" class="panel-label">FECHA EXPEDICIÓN:</b>
                                <div data-v-1fb519a4="">
                                    <p data-v-1fb519a4="" class="panel-value">{{ $dato["Tercero"]["FechaExpedicion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["Hora"]))
                            <div data-v-1fb519a4="" class="col-6">
                                <b data-v-1fb519a4="" class="panel-label">HORA:</b>
                                <div data-v-1fb519a4="">
                                    <p data-v-1fb519a4="" class="panel-value">{{ $dato["Tercero"]["Hora"] }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!---->
            <!---->
            <!---->
            <div data-v-f07e2d0a="" class="col-6">
                <div data-v-f07e2d0a="" class="panel mb-3">
                    <div data-v-f07e2d0a="" class="panel-heading"><b data-v-f07e2d0a="">RESULTADO DE LA CONSULTA</b></div>
                    <div data-v-f07e2d0a="" class="panel-body">
                        <div data-v-f07e2d0a="" class="row">
                            @if (isset($dato["Tercero"]["NombreTitular"]))
                            <div data-v-1fb519a4="" class="col-6">
                                <b data-v-1fb519a4="" class="panel-label">NOMBRES APELLIDOS - RAZÓN SOCIAL:</b>
                                <div data-v-1fb519a4="">
                                    <p data-v-1fb519a4="" class="panel-value">{{ $dato["Tercero"]["NombreTitular"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["LugarExpedicion"]))
                            <div data-v-1fb519a4="" class="col-md-6">
                                <b data-v-1fb519a4="" class="panel-label">LUGAR DE EXPEDICIÓN:</b>
                                <div data-v-1fb519a4="">
                                    <p data-v-1fb519a4="" class="panel-value">{{ $dato["Tercero"]["LugarExpedicion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["Entidad"]))
                            <div data-v-1fb519a4="" class="col-md-6">
                                <b data-v-1fb519a4="" class="panel-label">USUARIO:</b>
                                <div data-v-1fb519a4="">
                                    <p data-v-1fb519a4="" class="panel-value">{{ $dato["Tercero"]["Entidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["NombreCiiu"]))
                            <div data-v-1fb519a4="" class="col-md-6">
                                <b data-v-1fb519a4="" class="panel-label">ACTIVIDAD ECONÓMICA - CIIU:</b>
                                <div data-v-1fb519a4="">
                                    <p data-v-1fb519a4="" class="panel-value">{{ $dato["Tercero"]["NombreCiiu"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["RangoEdad"]))
                            <div data-v-1fb519a4="" class="col-md-6">
                                <b data-v-1fb519a4="" class="panel-label">RANGO EDAD PROBABLE:</b>
                                <div data-v-1fb519a4="">
                                    <p data-v-1fb519a4="" class="panel-value">{{ $dato["Tercero"]["RangoEdad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["NumeroInforme"]))
                            <div data-v-1fb519a4="" class="col-md-6">
                                <b data-v-1fb519a4="" class="panel-label">No INFORME:</b>
                                <div data-v-1fb519a4="">
                                    <p data-v-1fb519a4="" class="panel-value">{{ $dato["Tercero"]["NumeroInforme"] }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if (isset($dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]) and count($dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"])>0 and count( $dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]) != count($dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"], 1))
            <div data-v-8578e44c="" class="col-md-12">
                <div data-v-8578e44c="" class="panel panel-primary mb-3">
                    <div data-v-8578e44c="" class="panel-heading"><b data-v-8578e44c="">REGISTROS</b></div>
                    @foreach($dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"] as $Registro)
                    <div data-v-8578e44c="" class="panel-body">
                        <div data-v-8578e44c="" class="row">
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PAQUETE INFORMACIÓN:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $Registro["PaqueteInformacion"] }}</p>
                                </div>
                            </div>
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NUMERO OBLIGACIONES:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $Registro["NumeroObligaciones"] }}</p>
                                </div>
                            </div>
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TOTAL SALDO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $Registro["TotalSaldo"] }}</p>
                                </div>
                            </div>
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PARTICIPACIÓN DEUDA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $Registro["ParticipacionDeuda"] }}</p>
                                </div>
                            </div>
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NUMERO OBLIGACIONES DIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $Registro["NumeroObligacionesDia"] }}</p>
                                </div>
                            </div>
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">SALDO OBLIGACIONES DIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $Registro["SaldoObligacionesDia"] }}</p>
                                </div>
                            </div>
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CUOTA OBLIGACIONES DIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $Registro["CuotaObligacionesDia"] }}</p>
                                </div>
                            </div>


                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CANTIDAD OBLIGACIONES MORA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $Registro["CantidadObligacionesMora"] }}</p>
                                </div>
                            </div>
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">SALDO OBLIGACIONES MORA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $Registro["SaldoObligacionesMora"] }}</p>
                                </div>
                            </div>
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CUOTA OBLIGACIONES MORA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $Registro["CuotaObligacionesMora"] }}</p>
                                </div>
                            </div>
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR MORA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $Registro["ValorMora"] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div data-v-8578e44c="" class="panel-heading"></div>
                    @endforeach
                </div>
            </div>
            @endif

            @if (isset($dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]) and count($dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"])>0 and count( $dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]) == count($dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"], 1))
            <div data-v-8578e44c="" class="col-md-12">
                <div data-v-8578e44c="" class="panel panel-primary mb-3">
                    <div data-v-8578e44c="" class="panel-heading"><b data-v-8578e44c="">REGISTROS</b></div>
                    <div data-v-8578e44c="" class="panel-body">
                        <div data-v-8578e44c="" class="row">
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PAQUETE INFORMACIÓN:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]["PaqueteInformacion"] }}</p>
                                </div>
                            </div>
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NUMERO OBLIGACIONES:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]["NumeroObligaciones"] }}</p>
                                </div>
                            </div>
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TOTAL SALDO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]["TotalSaldo"] }}</p>
                                </div>
                            </div>
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PARTICIPACIÓN DEUDA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]["ParticipacionDeuda"] }}</p>
                                </div>
                            </div>
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NUMERO OBLIGACIONES DIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]["NumeroObligacionesDia"] }}</p>
                                </div>
                            </div>
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">SALDO OBLIGACIONES DIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]["SaldoObligacionesDia"] }}</p>
                                </div>
                            </div>
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CUOTA OBLIGACIONES DIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]["CuotaObligacionesDia"] }}</p>
                                </div>
                            </div>


                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CANTIDAD OBLIGACIONES MORA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]["CantidadObligacionesMora"] }}</p>
                                </div>
                            </div>
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">SALDO OBLIGACIONES MORA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]["SaldoObligacionesMora"] }}</p>
                                </div>
                            </div>
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CUOTA OBLIGACIONES MORA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]["CuotaObligacionesMora"] }}</p>
                                </div>
                            </div>
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR MORA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["Consolidado"]["ResumenPrincipal"]["Registro"]["ValorMora"] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif



            @if (isset($dato["Tercero"]["CuentasVigentes"]["Obligacion"]) and count($dato["Tercero"]["CuentasVigentes"]["Obligacion"])>0 and count( $dato["Tercero"]["CuentasVigentes"]["Obligacion"]) != count($dato["Tercero"]["CuentasVigentes"]["Obligacion"], 1))
            <div data-v-872c20c4="" class="col-md-12">
                <div data-v-872c20c4="" class="panel panel-primary mb-3">
                    <div data-v-872c20c4="" class="panel-heading"><b data-v-872c20c4="">CUENTAS VIGENTES</b></div>
                    @foreach($dato["Tercero"]["CuentasVigentes"]["Obligacion"] as $CuentasVigentes)
                    <div data-v-872c20c4="" class="panel-body">
                        <div data-v-872c20c4="" class="row">
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">PAQUETE INFORMACION:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $CuentasVigentes["PaqueteInformacion"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-md-2">
                                <b data-v-872c20c4="" class="panel-label table-text">IDENTIFICADOR LINEA:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $CuentasVigentes["IdentificadorLinea"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">TIPO CONTRATO:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $CuentasVigentes["TipoContrato"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">TIPO ENTIDAD:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $CuentasVigentes["TipoEntidad"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $CuentasVigentes["NombreEntidad"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">CIUDAD:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $CuentasVigentes["Ciudad"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">SUCURSAL:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $CuentasVigentes["Sucursal"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">NUMERO OBLIGACION:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $CuentasVigentes["NumeroObligacion"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">ESTADO OBLIGACION:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $CuentasVigentes["EstadoObligacion"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">FECHA APERTURA:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $CuentasVigentes["FechaApertura"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">FECHA TERMINACION:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $CuentasVigentes["FechaTerminacion"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">VALOR INICIAL:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $CuentasVigentes["ValorInicial"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">COMPORTAMIENTOS:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $CuentasVigentes["Comportamientos"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">FECHA DE CORTE:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $CuentasVigentes["FechaCorte"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">FECHA PERMANENCIA:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $CuentasVigentes["FechaPermanencia"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">CHEQUES DEVUELTOS:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $CuentasVigentes["ChequesDevueltos"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">DIAS CARTERA:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $CuentasVigentes["DiasCartera"] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if (isset($dato["Tercero"]["CuentasVigentes"]["Obligacion"]) and count($dato["Tercero"]["CuentasVigentes"]["Obligacion"])>0 and count( $dato["Tercero"]["CuentasVigentes"]["Obligacion"]) == count($dato["Tercero"]["CuentasVigentes"]["Obligacion"], 1))
            <div data-v-872c20c4="" class="col-md-12">
                <div data-v-872c20c4="" class="panel panel-primary mb-3">
                    <div data-v-872c20c4="" class="panel-heading"><b data-v-872c20c4="">CUENTAS VIGENTES</b></div>
                    <div data-v-872c20c4="" class="panel-body">
                        <div data-v-872c20c4="" class="row">
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">PAQUETE INFORMACION:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $dato["Tercero"]["CuentasVigentes"]["Obligacion"]["PaqueteInformacion"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-md-2">
                                <b data-v-872c20c4="" class="panel-label table-text">IDENTIFICADOR LINEA:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $dato["Tercero"]["CuentasVigentes"]["Obligacion"]["IdentificadorLinea"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">TIPO CONTRATO:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $dato["Tercero"]["CuentasVigentes"]["Obligacion"]["TipoContrato"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">TIPO ENTIDAD:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $dato["Tercero"]["CuentasVigentes"]["Obligacion"]["TipoEntidad"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $dato["Tercero"]["CuentasVigentes"]["Obligacion"]["NombreEntidad"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">CIUDAD:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $dato["Tercero"]["CuentasVigentes"]["Obligacion"]["Ciudad"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">SUCURSAL:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $dato["Tercero"]["CuentasVigentes"]["Obligacion"]["Sucursal"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">NUMERO OBLIGACION:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $dato["Tercero"]["CuentasVigentes"]["Obligacion"]["NumeroObligacion"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">ESTADO OBLIGACION:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $dato["Tercero"]["CuentasVigentes"]["Obligacion"]["EstadoObligacion"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">FECHA APERTURA:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $dato["Tercero"]["CuentasVigentes"]["Obligacion"]["FechaApertura"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">FECHA TERMINACION:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $dato["Tercero"]["CuentasVigentes"]["Obligacion"]["FechaTerminacion"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">VALOR INICIAL:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $dato["Tercero"]["CuentasVigentes"]["Obligacion"]["ValorInicial"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">COMPORTAMIENTOS:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $dato["Tercero"]["CuentasVigentes"]["Obligacion"]["Comportamientos"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">FECHA DE CORTE:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $dato["Tercero"]["CuentasVigentes"]["Obligacion"]["FechaCorte"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">FECHA PERMANENCIA:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $dato["Tercero"]["CuentasVigentes"]["Obligacion"]["FechaPermanencia"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">CHEQUES DEVUELTOS:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $dato["Tercero"]["CuentasVigentes"]["Obligacion"]["ChequesDevueltos"] }}</p>
                                </div>
                            </div>
                            <div data-v-872c20c4="" class="col-2">
                                <b data-v-872c20c4="" class="panel-label table-text">DIAS CARTERA:</b>
                                <div data-v-872c20c4="">
                                    <p data-v-872c20c4="" class="panel-value">{{ $dato["Tercero"]["CuentasVigentes"]["Obligacion"]["DiasCartera"] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif




            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]) and count($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"])>0 and count( $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]) != count($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"], 1))
            <div data-v-8578e44c="" class="col-md-12">
                <div data-v-8578e44c="" class="panel panel-primary mb-3">
                    <div data-v-8578e44c="" class="panel-heading"><b data-v-8578e44c="">SECTOR FINANCIOER AL DIA</b></div>
                    @foreach($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"] as $SectorFinancieroAlDia)
                    <div data-v-8578e44c="" class="panel-body">
                        <div data-v-8578e44c="" class="row">
                            @if (isset($SectorFinancieroAlDia["PaqueteInformacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PAQUETE INFORMACIÓN:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["PaqueteInformacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["IdentificadorLinea"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">IDENTIFICADOR LINEA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["IdentificadorLinea"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["TipoContrato"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO CONTRATO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["TipoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["EstadoContrato"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ESTADO CONTRATO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["EstadoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["TipoEntidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO ENTIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["TipoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["NombreEntidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["NombreEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["Ciudad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CIUDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["Ciudad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["Sucursal"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">SUCURSAL:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["Sucursal"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["NumeroObligacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NUMERO OBLIGACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["NumeroObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["Calidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CALIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["Calidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["EstadoObligacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ESTADO OBLIGACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["EstadoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["ModalidadCredito"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">MODALIDAD CREDITO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["ModalidadCredito"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["LineaCredito"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">LINEA CREDITO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["LineaCredito"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["Periodicidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PERIODICIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["Periodicidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["FechaApertura"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA APERTURA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["FechaApertura"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["FechaTerminacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA TERMINACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["FechaTerminacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["Calificacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CALIFICACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["Calificacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["ValorInicial"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR INICIAL:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["ValorInicial"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["SaldoObligacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">SALDO OBLIGATORIO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["SaldoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["ValorMora"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR MORAL:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["ValorMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["ValorCuota"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR CUOTA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["ValorCuota"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["TipoMoneda"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO MONEDA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["TipoMoneda"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["CuotasCanceladas"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">COUTAS CANCELADAS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["CuotasCanceladas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["TipoGarantia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO GARANTIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["TipoGarantia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["CubrimientoGarantia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CUBRIMIENTO GARANTIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["CubrimientoGarantia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["MoraMaxima"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">MORA MAXIMA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["MoraMaxima"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["Comportamientos"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">COMPORTAMIENTOS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["Comportamientos"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["ParticipacionDeuda"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PARTICIPACION DEUDA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["ParticipacionDeuda"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["ProbabilidadNoPago"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PROBABILIDAD NO PAGO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["ProbabilidadNoPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["FechaCorte"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA CORTE:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["FechaCorte"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["ModoExtincion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">MODO EXTINCION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["ModoExtincion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["FechaPago"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA PAGO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["FechaPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["FechaPermanencia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA PERMANENCIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["FechaPermanencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["NumeroReestructuraciones"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NO REESTRACTURACIONES:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["NumeroReestructuraciones"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["NaturalezaReestructuracion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NATURALEZA REESTRICTURACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["NaturalezaReestructuracion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["TipoEntidadOriginadoraCartera"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO ENTIDAD ORIDINADORA CARTERA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["TipoEntidadOriginadoraCartera"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["EntidadOriginadoraCartera"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ENTIDAD ORIDINADORA CARTERA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["EntidadOriginadoraCartera"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["TipoPago"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO PAGO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["TipoPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["EstadoTitular"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ESTADO TITULAR:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["EstadoTitular"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["NumeroCuotasPactadas"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NO CUOTAS PACTADAS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["NumeroCuotasPactadas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["NumeroCuotasMora"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NO COUTAS MORA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["NumeroCuotasMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroAlDia["Reestructurado"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">REESTRUCTADO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroAlDia["Reestructurado"] }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div data-v-8578e44c="" class="panel-heading"></div>
                    @endforeach
                </div>
            </div>
            @endif

            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]) and count($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"])>0 and count( $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]) == count($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"], 1))
            <div data-v-8578e44c="" class="col-md-12">
                <div data-v-8578e44c="" class="panel panel-primary mb-3">
                    <div data-v-8578e44c="" class="panel-heading"><b data-v-8578e44c="">SECTOR FINANCIOER AL DIA</b></div>
                    <div data-v-8578e44c="" class="panel-body">
                        <div data-v-8578e44c="" class="row">
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["PaqueteInformacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PAQUETE INFORMACIÓN:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["PaqueteInformacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["IdentificadorLinea"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">IDENTIFICADOR LINEA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["IdentificadorLinea"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["TipoContrato"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO CONTRATO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["TipoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["EstadoContrato"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ESTADO CONTRATO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["EstadoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["TipoEntidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO ENTIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["TipoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["NombreEntidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["NombreEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Ciudad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CIUDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Ciudad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Sucursal"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">SUCURSAL:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Sucursal"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["NumeroObligacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NUMERO OBLIGACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["NumeroObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Calidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CALIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Calidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["EstadoObligacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ESTADO OBLIGACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["EstadoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ModalidadCredito"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">MODALIDAD CREDITO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ModalidadCredito"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["LineaCredito"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">LINEA CREDITO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["LineaCredito"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Periodicidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PERIODICIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Periodicidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["FechaApertura"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA APERTURA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["FechaApertura"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["FechaTerminacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA TERMINACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["FechaTerminacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Calificacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CALIFICACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Calificacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ValorInicial"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR INICIAL:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ValorInicial"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["SaldoObligacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">SALDO OBLIGATORIO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["SaldoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ValorMora"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR MORAL:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ValorMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ValorCuota"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR CUOTA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ValorCuota"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["TipoMoneda"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO MONEDA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["TipoMoneda"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["CuotasCanceladas"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">COUTAS CANCELADAS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["CuotasCanceladas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["TipoGarantia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO GARANTIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["TipoGarantia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["CubrimientoGarantia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CUBRIMIENTO GARANTIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["CubrimientoGarantia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["MoraMaxima"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">MORA MAXIMA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["MoraMaxima"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Comportamientos"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">COMPORTAMIENTOS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Comportamientos"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ParticipacionDeuda"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PARTICIPACION DEUDA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ParticipacionDeuda"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ProbabilidadNoPago"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PROBABILIDAD NO PAGO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ProbabilidadNoPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["FechaCorte"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA CORTE:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["FechaCorte"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ModoExtincion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">MODO EXTINCION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["ModoExtincion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["FechaPago"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA PAGO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["FechaPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["FechaPermanencia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA PERMANENCIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["FechaPermanencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["NumeroReestructuraciones"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NO REESTRACTURACIONES:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["NumeroReestructuraciones"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["NaturalezaReestructuracion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NATURALEZA REESTRICTURACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["NaturalezaReestructuracion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["TipoEntidadOriginadoraCartera"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO ENTIDAD ORIDINADORA CARTERA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["TipoEntidadOriginadoraCartera"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["EntidadOriginadoraCartera"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ENTIDAD ORIDINADORA CARTERA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["EntidadOriginadoraCartera"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["TipoPago"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO PAGO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["TipoPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["EstadoTitular"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ESTADO TITULAR:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["EstadoTitular"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["NumeroCuotasPactadas"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NO CUOTAS PACTADAS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["NumeroCuotasPactadas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["NumeroCuotasMora"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NO COUTAS MORA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["NumeroCuotasMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Reestructurado"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">REESTRUCTADO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroAlDia"]["Obligacion"]["Reestructurado"] }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif




            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]) and count($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"])>0 and count( $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]) != count($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"], 1))
            <div data-v-8578e44c="" class="col-md-12">
                <div data-v-8578e44c="" class="panel panel-primary mb-3">
                    <div data-v-8578e44c="" class="panel-heading"><b data-v-8578e44c="">SECTOR FINANCIERO EXTINGUIDAS</b></div>
                    @foreach($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"] as $SectorFinancieroExtinguidas)
                    <div data-v-8578e44c="" class="panel-body">
                        <div data-v-8578e44c="" class="row">
                            @if (isset($SectorFinancieroExtinguidas["PaqueteInformacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PAQUETE INFORMACIÓN:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["PaqueteInformacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["IdentificadorLinea"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">IDENTIFICADOR LINEA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["IdentificadorLinea"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["TipoContrato"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO CONTRATO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["TipoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["EstadoContrato"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ESTADO CONTRATO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["EstadoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["TipoEntidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO ENTIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["TipoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["NombreEntidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["NombreEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["Ciudad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CIUDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["Ciudad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["Sucursal"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">SUCURSAL:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["Sucursal"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["NumeroObligacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NUMERO OBLIGACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["NumeroObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["Calidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CALIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["Calidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["EstadoObligacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ESTADO OBLIGACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["EstadoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["ModalidadCredito"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">MODALIDAD CREDITO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["ModalidadCredito"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["LineaCredito"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">LINEA CREDITO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["LineaCredito"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["Periodicidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PERIODICIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["Periodicidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["FechaApertura"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA APERTURA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["FechaApertura"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["FechaTerminacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA TERMINACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["FechaTerminacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["Calificacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CALIFICACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["Calificacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["ValorInicial"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR INICIAL:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["ValorInicial"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["SaldoObligacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">SALDO OBLIGATORIO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["SaldoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["ValorMora"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR MORAL:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["ValorMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["ValorCuota"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR CUOTA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["ValorCuota"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["TipoMoneda"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO MONEDA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["TipoMoneda"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["CuotasCanceladas"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">COUTAS CANCELADAS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["CuotasCanceladas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["TipoGarantia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO GARANTIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["TipoGarantia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["CubrimientoGarantia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CUBRIMIENTO GARANTIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["CubrimientoGarantia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["MoraMaxima"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">MORA MAXIMA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["MoraMaxima"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["Comportamientos"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">COMPORTAMIENTOS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["Comportamientos"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["ParticipacionDeuda"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PARTICIPACION DEUDA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["ParticipacionDeuda"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["ProbabilidadNoPago"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PROBABILIDAD NO PAGO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["ProbabilidadNoPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["FechaCorte"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA CORTE:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["FechaCorte"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["ModoExtincion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">MODO EXTINCION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["ModoExtincion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["FechaPago"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA PAGO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["FechaPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["FechaPermanencia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA PERMANENCIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["FechaPermanencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["NumeroReestructuraciones"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NO REESTRACTURACIONES:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["NumeroReestructuraciones"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["NaturalezaReestructuracion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NATURALEZA REESTRICTURACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["NaturalezaReestructuracion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["TipoEntidadOriginadoraCartera"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO ENTIDAD ORIDINADORA CARTERA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["TipoEntidadOriginadoraCartera"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["EntidadOriginadoraCartera"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ENTIDAD ORIDINADORA CARTERA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["EntidadOriginadoraCartera"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["TipoPago"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO PAGO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["TipoPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["EstadoTitular"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ESTADO TITULAR:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["EstadoTitular"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["NumeroCuotasPactadas"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NO CUOTAS PACTADAS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["NumeroCuotasPactadas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["NumeroCuotasMora"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NO COUTAS MORA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["NumeroCuotasMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorFinancieroExtinguidas["Reestructurado"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">REESTRUCTADO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorFinancieroExtinguidas["Reestructurado"] }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div data-v-8578e44c="" class="panel-heading"></div>
                    @endforeach
                </div>
            </div>
            @endif

            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]) and count($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"])>0 and count( $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]) == count($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"], 1))
            <div data-v-8578e44c="" class="col-md-12">
                <div data-v-8578e44c="" class="panel panel-primary mb-3">
                    <div data-v-8578e44c="" class="panel-heading"><b data-v-8578e44c="">SECTOR FINANCIERO EXTINGUIDAS</b></div>
                    <div data-v-8578e44c="" class="panel-body">
                        <div data-v-8578e44c="" class="row">
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["PaqueteInformacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PAQUETE INFORMACIÓN:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["PaqueteInformacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["IdentificadorLinea"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">IDENTIFICADOR LINEA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["IdentificadorLinea"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["TipoContrato"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO CONTRATO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["TipoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["EstadoContrato"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ESTADO CONTRATO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["EstadoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["TipoEntidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO ENTIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["TipoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["NombreEntidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["NombreEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Ciudad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CIUDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Ciudad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Sucursal"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">SUCURSAL:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Sucursal"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["NumeroObligacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NUMERO OBLIGACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["NumeroObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Calidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CALIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Calidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["EstadoObligacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ESTADO OBLIGACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["EstadoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ModalidadCredito"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">MODALIDAD CREDITO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ModalidadCredito"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["LineaCredito"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">LINEA CREDITO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["LineaCredito"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Periodicidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PERIODICIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Periodicidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["FechaApertura"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA APERTURA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["FechaApertura"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["FechaTerminacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA TERMINACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["FechaTerminacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Calificacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CALIFICACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Calificacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ValorInicial"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR INICIAL:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ValorInicial"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["SaldoObligacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">SALDO OBLIGATORIO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["SaldoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ValorMora"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR MORAL:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ValorMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ValorCuota"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR CUOTA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ValorCuota"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["TipoMoneda"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO MONEDA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["TipoMoneda"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["CuotasCanceladas"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">COUTAS CANCELADAS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["CuotasCanceladas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["TipoGarantia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO GARANTIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["TipoGarantia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["CubrimientoGarantia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CUBRIMIENTO GARANTIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["CubrimientoGarantia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["MoraMaxima"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">MORA MAXIMA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["MoraMaxima"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Comportamientos"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">COMPORTAMIENTOS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Comportamientos"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ParticipacionDeuda"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PARTICIPACION DEUDA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ParticipacionDeuda"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ProbabilidadNoPago"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PROBABILIDAD NO PAGO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ProbabilidadNoPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["FechaCorte"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA CORTE:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["FechaCorte"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ModoExtincion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">MODO EXTINCION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["ModoExtincion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["FechaPago"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA PAGO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["FechaPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["FechaPermanencia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA PERMANENCIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["FechaPermanencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["NumeroReestructuraciones"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NO REESTRACTURACIONES:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["NumeroReestructuraciones"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["NaturalezaReestructuracion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NATURALEZA REESTRICTURACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["NaturalezaReestructuracion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["TipoEntidadOriginadoraCartera"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO ENTIDAD ORIDINADORA CARTERA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["TipoEntidadOriginadoraCartera"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["EntidadOriginadoraCartera"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ENTIDAD ORIDINADORA CARTERA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["EntidadOriginadoraCartera"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["TipoPago"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO PAGO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["TipoPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["EstadoTitular"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ESTADO TITULAR:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["EstadoTitular"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["NumeroCuotasPactadas"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NO CUOTAS PACTADAS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["NumeroCuotasPactadas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["NumeroCuotasMora"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NO COUTAS MORA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["NumeroCuotasMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Reestructurado"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">REESTRUCTADO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorFinancieroExtinguidas"]["Obligacion"]["Reestructurado"] }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif




            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]) and count($dato["Tercero"]["SectorRealAlDia"]["Obligacion"])>0 and count( $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]) != count($dato["Tercero"]["SectorRealAlDia"]["Obligacion"], 1))
            <div data-v-8578e44c="" class="col-md-12">
                <div data-v-8578e44c="" class="panel panel-primary mb-3">
                    <div data-v-8578e44c="" class="panel-heading"><b data-v-8578e44c="">SECTOR REAL AL DIA</b></div>
                    @foreach($dato["Tercero"]["SectorRealAlDia"]["Obligacion"] as $SectorRealAlDia)
                    <div data-v-8578e44c="" class="panel-body">
                        <div data-v-8578e44c="" class="row">
                            @if (isset($SectorRealAlDia["PaqueteInformacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PAQUETE INFORMACIÓN:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["PaqueteInformacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["IdentificadorLinea"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">IDENTIFICADOR LINEA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["IdentificadorLinea"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["TipoContrato"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO CONTRATO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["TipoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["EstadoContrato"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ESTADO CONTRATO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["EstadoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["TipoEntidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO ENTIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["TipoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["NombreEntidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["NombreEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["Ciudad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CIUDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["Ciudad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["Sucursal"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">SUCURSAL:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["Sucursal"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["NumeroObligacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NUMERO OBLIGACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["NumeroObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["Calidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CALIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["Calidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["EstadoObligacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ESTADO OBLIGACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["EstadoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["LineaCredito"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">LINEA CREDITO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["LineaCredito"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["Periodicidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PERIODICIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["Periodicidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["FechaApertura"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA APERTURA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["FechaApertura"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["FechaTerminacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA TERMINACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["FechaTerminacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["ValorInicial"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR INICIAL:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["ValorInicial"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["SaldoObligacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">SALDO OBLIGATORIO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["SaldoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["ValorMora"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR MORAL:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["ValorMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["ValorCuota"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR CUOTA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["ValorCuota"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["CuotasCanceladas"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">COUTAS CANCELADAS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["CuotasCanceladas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["TipoGarantia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO GARANTIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["TipoGarantia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["MoraMaxima"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">MORA MAXIMA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["MoraMaxima"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["Comportamientos"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">COMPORTAMIENTOS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["Comportamientos"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["ParticipacionDeuda"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PARTICIPACION DEUDA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["ParticipacionDeuda"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["FechaCorte"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA CORTE:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["FechaCorte"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["ModoExtincion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">MODO EXTINCION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["ModoExtincion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["FechaPago"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA PAGO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["FechaPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["FechaPermanencia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA PERMANENCIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["FechaPermanencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["ChequesDevueltos"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CHEQUES DEVUELTOS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["ChequesDevueltos"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["TipoPago"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO PAGO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["TipoPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["EstadoTitular"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ESTADO TITULAR:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["EstadoTitular"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["NumeroCuotasPactadas"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NO CUOTAS PACTADAS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["NumeroCuotasPactadas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["NumeroCuotasMora"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NO COUTAS MORA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["NumeroCuotasMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["ValorCargoFijo"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR CARGO FIJOA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["ValorCargoFijo"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["ClausulaPermanencia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CLAUSULA PERMANENCIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["ClausulaPermanencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["Reestructurado"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">REESTRUCTADO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["Reestructurado"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["Vigencia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VIGENCIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["Vigencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["NumeroMesesContrato"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">N0 MESES CONTRATO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["NumeroMesesContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealAlDia["NumeroMesesClausula"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NO MESES CLAUSULA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealAlDia["NumeroMesesClausula"] }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div data-v-8578e44c="" class="panel-heading"></div>
                    @endforeach
                </div>
            </div>
            @endif

            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]) and count($dato["Tercero"]["SectorRealAlDia"]["Obligacion"])>0 and count( $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]) == count($dato["Tercero"]["SectorRealAlDia"]["Obligacion"], 1))
            <div data-v-8578e44c="" class="col-md-12">
                <div data-v-8578e44c="" class="panel panel-primary mb-3">
                    <div data-v-8578e44c="" class="panel-heading"><b data-v-8578e44c="">SECTOR REAL AL DIA</b></div>
                    <div data-v-8578e44c="" class="panel-body">
                        <div data-v-8578e44c="" class="row">
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["PaqueteInformacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PAQUETE INFORMACIÓN:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["PaqueteInformacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["IdentificadorLinea"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">IDENTIFICADOR LINEA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["IdentificadorLinea"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["TipoContrato"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO CONTRATO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["TipoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["EstadoContrato"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ESTADO CONTRATO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["EstadoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["TipoEntidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO ENTIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["TipoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["NombreEntidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["NombreEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Ciudad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CIUDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Ciudad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Sucursal"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">SUCURSAL:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Sucursal"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["NumeroObligacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NUMERO OBLIGACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["NumeroObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Calidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CALIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Calidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["EstadoObligacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ESTADO OBLIGACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["EstadoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["LineaCredito"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">LINEA CREDITO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["LineaCredito"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Periodicidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PERIODICIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Periodicidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["FechaApertura"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA APERTURA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["FechaApertura"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["FechaTerminacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA TERMINACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["FechaTerminacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ValorInicial"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR INICIAL:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ValorInicial"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["SaldoObligacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">SALDO OBLIGATORIO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["SaldoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ValorMora"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR MORAL:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ValorMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ValorCuota"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR CUOTA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ValorCuota"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["CuotasCanceladas"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">COUTAS CANCELADAS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["CuotasCanceladas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["TipoGarantia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO GARANTIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["TipoGarantia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["MoraMaxima"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">MORA MAXIMA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["MoraMaxima"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Comportamientos"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">COMPORTAMIENTOS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Comportamientos"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ParticipacionDeuda"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PARTICIPACION DEUDA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ParticipacionDeuda"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["FechaCorte"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA CORTE:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["FechaCorte"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ModoExtincion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">MODO EXTINCION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ModoExtincion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["FechaPago"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA PAGO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["FechaPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["FechaPermanencia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA PERMANENCIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["FechaPermanencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ChequesDevueltos"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CHEQUES DEVUELTOS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ChequesDevueltos"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["TipoPago"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO PAGO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["TipoPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["EstadoTitular"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ESTADO TITULAR:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["EstadoTitular"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["NumeroCuotasPactadas"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NO CUOTAS PACTADAS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["NumeroCuotasPactadas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["NumeroCuotasMora"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NO COUTAS MORA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["NumeroCuotasMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ValorCargoFijo"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR CARGO FIJOA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ValorCargoFijo"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ClausulaPermanencia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CLAUSULA PERMANENCIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["ClausulaPermanencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Reestructurado"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">REESTRUCTADO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Reestructurado"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Vigencia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VIGENCIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["Vigencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["NumeroMesesContrato"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">N0 MESES CONTRATO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["NumeroMesesContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["NumeroMesesClausula"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NO MESES CLAUSULA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealAlDia"]["Obligacion"]["NumeroMesesClausula"] }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif




            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]) and count($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"])>0 and count( $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]) != count($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"], 1))
            <div data-v-8578e44c="" class="col-md-12">
                <div data-v-8578e44c="" class="panel panel-primary mb-3">
                    <div data-v-8578e44c="" class="panel-heading"><b data-v-8578e44c="">SECTOR REAL EXTINGUIDAS</b></div>
                    @foreach($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"] as $SectorRealExtinguidas)
                    <div data-v-8578e44c="" class="panel-body">
                        <div data-v-8578e44c="" class="row">
                            @if (isset($SectorRealExtinguidas["PaqueteInformacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PAQUETE INFORMACIÓN:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["PaqueteInformacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["IdentificadorLinea"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">IDENTIFICADOR LINEA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["IdentificadorLinea"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["TipoContrato"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO CONTRATO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["TipoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["EstadoContrato"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ESTADO CONTRATO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["EstadoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["TipoEntidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO ENTIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["TipoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["NombreEntidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["NombreEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["Ciudad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CIUDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["Ciudad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["Sucursal"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">SUCURSAL:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["Sucursal"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["NumeroObligacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NUMERO OBLIGACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["NumeroObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["Calidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CALIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["Calidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["EstadoObligacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ESTADO OBLIGACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["EstadoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["LineaCredito"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">LINEA CREDITO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["LineaCredito"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["Periodicidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PERIODICIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["Periodicidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["FechaApertura"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA APERTURA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["FechaApertura"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["FechaTerminacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA TERMINACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["FechaTerminacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["ValorInicial"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR INICIAL:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["ValorInicial"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["SaldoObligacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">SALDO OBLIGATORIO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["SaldoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["ValorMora"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR MORAL:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["ValorMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["ValorCuota"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR CUOTA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["ValorCuota"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["CuotasCanceladas"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">COUTAS CANCELADAS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["CuotasCanceladas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["TipoGarantia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO GARANTIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["TipoGarantia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["MoraMaxima"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">MORA MAXIMA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["MoraMaxima"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["Comportamientos"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">COMPORTAMIENTOS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["Comportamientos"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["ParticipacionDeuda"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PARTICIPACION DEUDA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["ParticipacionDeuda"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["FechaCorte"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA CORTE:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["FechaCorte"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["ModoExtincion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">MODO EXTINCION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["ModoExtincion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["FechaPago"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA PAGO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["FechaPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["FechaPermanencia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA PERMANENCIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["FechaPermanencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["ChequesDevueltos"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CHEQUES DEVUELTOS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["ChequesDevueltos"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["TipoPago"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO PAGO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["TipoPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["EstadoTitular"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ESTADO TITULAR:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["EstadoTitular"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["NumeroCuotasPactadas"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NO CUOTAS PACTADAS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["NumeroCuotasPactadas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["NumeroCuotasMora"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NO COUTAS MORA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["NumeroCuotasMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["ValorCargoFijo"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR CARGO FIJOA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["ValorCargoFijo"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["ClausulaPermanencia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CLAUSULA PERMANENCIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["ClausulaPermanencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["Reestructurado"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">REESTRUCTADO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["Reestructurado"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["Vigencia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VIGENCIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["Vigencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["NumeroMesesContrato"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">N0 MESES CONTRATO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["NumeroMesesContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($SectorRealExtinguidas["NumeroMesesClausula"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NO MESES CLAUSULA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $SectorRealExtinguidas["NumeroMesesClausula"] }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div data-v-8578e44c="" class="panel-heading"></div>
                    @endforeach
                </div>
            </div>
            @endif


            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]) and count($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"])>0 and count( $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]) == count($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"], 1))
            <div data-v-8578e44c="" class="col-md-12">
                <div data-v-8578e44c="" class="panel panel-primary mb-3">
                    <div data-v-8578e44c="" class="panel-heading"><b data-v-8578e44c="">SECTOR REAL EXTINGUIDAS</b></div>
                    <div data-v-8578e44c="" class="panel-body">
                        <div data-v-8578e44c="" class="row">
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["PaqueteInformacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PAQUETE INFORMACIÓN:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["PaqueteInformacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["IdentificadorLinea"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">IDENTIFICADOR LINEA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["IdentificadorLinea"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["TipoContrato"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO CONTRATO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["TipoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["EstadoContrato"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ESTADO CONTRATO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["EstadoContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["TipoEntidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO ENTIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["TipoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["NombreEntidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["NombreEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Ciudad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CIUDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Ciudad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Sucursal"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">SUCURSAL:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Sucursal"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["NumeroObligacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NUMERO OBLIGACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["NumeroObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Calidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CALIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Calidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["EstadoObligacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ESTADO OBLIGACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["EstadoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["LineaCredito"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">LINEA CREDITO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["LineaCredito"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Periodicidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PERIODICIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Periodicidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["FechaApertura"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA APERTURA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["FechaApertura"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["FechaTerminacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA TERMINACION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["FechaTerminacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ValorInicial"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR INICIAL:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ValorInicial"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["SaldoObligacion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">SALDO OBLIGATORIO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["SaldoObligacion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ValorMora"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR MORAL:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ValorMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ValorCuota"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR CUOTA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ValorCuota"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["CuotasCanceladas"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">COUTAS CANCELADAS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["CuotasCanceladas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["TipoGarantia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO GARANTIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["TipoGarantia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["MoraMaxima"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">MORA MAXIMA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["MoraMaxima"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Comportamientos"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">COMPORTAMIENTOS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Comportamientos"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ParticipacionDeuda"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">PARTICIPACION DEUDA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ParticipacionDeuda"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["FechaCorte"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA CORTE:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["FechaCorte"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ModoExtincion"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">MODO EXTINCION:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ModoExtincion"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["FechaPago"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA PAGO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["FechaPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["FechaPermanencia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA PERMANENCIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["FechaPermanencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ChequesDevueltos"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CHEQUES DEVUELTOS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ChequesDevueltos"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["TipoPago"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">TIPO PAGO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["TipoPago"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["EstadoTitular"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">ESTADO TITULAR:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["EstadoTitular"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["NumeroCuotasPactadas"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NO CUOTAS PACTADAS:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["NumeroCuotasPactadas"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["NumeroCuotasMora"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NO COUTAS MORA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["NumeroCuotasMora"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ValorCargoFijo"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VALOR CARGO FIJOA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ValorCargoFijo"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ClausulaPermanencia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CLAUSULA PERMANENCIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["ClausulaPermanencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Reestructurado"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">REESTRUCTADO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Reestructurado"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Vigencia"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">VIGENCIA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["Vigencia"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["NumeroMesesContrato"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">N0 MESES CONTRATO:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["NumeroMesesContrato"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["NumeroMesesClausula"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NO MESES CLAUSULA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["SectorRealExtinguidas"]["Obligacion"]["NumeroMesesClausula"] }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif




            @if (isset($dato["Tercero"]["HuellaConsulta"]["Consulta"]) and count($dato["Tercero"]["HuellaConsulta"]["Consulta"])>0 and count( $dato["Tercero"]["HuellaConsulta"]["Consulta"]) != count($dato["Tercero"]["HuellaConsulta"]["Consulta"], 1))
            <div data-v-8578e44c="" class="col-md-12">
                <div data-v-8578e44c="" class="panel panel-primary mb-3">
                    <div data-v-8578e44c="" class="panel-heading"><b data-v-8578e44c="">HUELLAS CONSULTA</b></div>
                    @foreach($dato["Tercero"]["HuellaConsulta"]["Consulta"] as $HuellaConsulta)
                    <div data-v-8578e44c="" class="panel-body">
                        <div data-v-8578e44c="" class="row">
                            @if (isset($HuellaConsulta["FechaConsulta"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA CONSULTA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $HuellaConsulta["FechaConsulta"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($HuellaConsulta["NombreTipoEntidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NOMBRE TIPO ENTIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $HuellaConsulta["NombreTipoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($HuellaConsulta["NombreEntidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $HuellaConsulta["NombreEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($HuellaConsulta["Sucursal"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">SUCURSAL:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $HuellaConsulta["Sucursal"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($HuellaConsulta["Ciudad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CIUDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $HuellaConsulta["Ciudad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($HuellaConsulta["MotivoConsulta"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">MOTIVO CONSULTA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $HuellaConsulta["MotivoConsulta"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($HuellaConsulta["CodigoTipoEntidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CODIGO TIPO ENTIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $HuellaConsulta["CodigoTipoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($HuellaConsulta["CodigoEntidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CODIGO ENTIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $HuellaConsulta["CodigoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div data-v-8578e44c="" class="panel-heading"></div>
                    @endforeach
                </div>
            </div>
            @endif

            @if (isset($dato["Tercero"]["HuellaConsulta"]["Consulta"]) and count($dato["Tercero"]["HuellaConsulta"]["Consulta"])>0 and count( $dato["Tercero"]["HuellaConsulta"]["Consulta"]) == count($dato["Tercero"]["HuellaConsulta"]["Consulta"], 1))
            <div data-v-8578e44c="" class="col-md-12">
                <div data-v-8578e44c="" class="panel panel-primary mb-3">
                    <div data-v-8578e44c="" class="panel-heading"><b data-v-8578e44c="">HUELLAS CONSULTA</b></div>
                    <div data-v-8578e44c="" class="panel-body">
                        <div data-v-8578e44c="" class="row">
                            @if (isset($dato["Tercero"]["HuellaConsulta"]["Consulta"]["FechaConsulta"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">FECHA CONSULTA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["HuellaConsulta"]["Consulta"]["FechaConsulta"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["HuellaConsulta"]["Consulta"]["NombreTipoEntidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NOMBRE TIPO ENTIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["HuellaConsulta"]["Consulta"]["NombreTipoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["HuellaConsulta"]["Consulta"]["NombreEntidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">NOMBRE ENTIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["HuellaConsulta"]["Consulta"]["NombreEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["HuellaConsulta"]["Consulta"]["Sucursal"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">SUCURSAL:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["HuellaConsulta"]["Consulta"]["Sucursal"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["HuellaConsulta"]["Consulta"]["Ciudad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CIUDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["HuellaConsulta"]["Consulta"]["Ciudad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["HuellaConsulta"]["Consulta"]["MotivoConsulta"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">MOTIVO CONSULTA:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["HuellaConsulta"]["Consulta"]["MotivoConsulta"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["HuellaConsulta"]["Consulta"]["CodigoTipoEntidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CODIGO TIPO ENTIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["HuellaConsulta"]["Consulta"]["CodigoTipoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                            @if (isset($dato["Tercero"]["HuellaConsulta"]["Consulta"]["CodigoEntidad"]))
                            <div data-v-8578e44c="" class="col-md-2">
                                <b data-v-8578e44c="" class="panel-label table-text">CODIGO ENTIDAD:</b>
                                <div data-v-8578e44c="">
                                    <p data-v-8578e44c="" class="panel-value">{{ $dato["Tercero"]["HuellaConsulta"]["Consulta"]["CodigoEntidad"] }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
        @endforeach
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
<li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i>Inicio</a></li>
<li><a href="{{ url('cifin') }}">Cifin</a></li>
<li class="active">Consulta</li>
@endsection