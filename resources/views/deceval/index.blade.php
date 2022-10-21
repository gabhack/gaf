@extends('layouts.app2')

@section('content')

@if (isset($message))
<div id="toast-message" class="col-md-12">
    <div class="row">
        <div class="col-md-4">
            <div class="alert alert-{{ $message['tipo'] }} alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-exclamation"></i> {{ $message['titulo'] }}</h4>
                {{ $message['mensaje'] }}
            </div>
        </div>
    </div>
</div>
@endif

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
        <!---->
        <div id="consulta-container" class="row">
            <div class="panel mb-3 col-md-12">
                <div class="panel-heading">
                    <b>REALIZAR PAGARE</b>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <form action="{{ url('deceval/consultar') }}" method="get" class="form-group col-md-12">
                            {{ csrf_field() }}
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <b class="panel-label">NIT EMISOR:</b>
                                    <input required="required" name="nitEmisor" type="number" placeholder="9004326290" value="9004326290" class="form-control text-center">
                                </div>
                                <div class="form-group col-md-4">
                                    <b class="panel-label">CLASE DEFINICION DOCUMENTO:</b>
                                    <input required="required" name="idClaseDefinicionDocumento" type="text" placeholder="4115" value="4115" class="form-control text-center">
                                </div>
                                <div class="form-group col-md-4">
                                    <b class="panel-label">FECHA GRABACION PAGARE:</b>
                                    <input required="required" name="fechaGrabacionPagare" type="text" placeholder="yyyy-mm-dd" value="<?php echo date('Y-m-d'); ?>" class="form-control text-center">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <b class="panel-label">NUMERO DE PAGARE:</b>
                                    <input required="required" name="numPagareEntidad" type="text" value="<?php echo date('Y-m-d-h:i') . '_PAG' ?>" class="form-control text-center">
                                </div>
                                <div class="form-group col-md-4">
                                    <b class="panel-label">FECHA DESEMBOLSO PAGARE:</b>
                                    <input required="required" name="fechaDesembolso" type="text" placeholder="yyyy-mm-dd" value="<?php echo date('Y-m-d'); ?>" class="form-control text-center">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <b class="panel-label">OTORGANTE TIPO ID:</b>
                                    <input required="required" name="otorganteTipoId" type="number" placeholder="1" value="1" class="form-control text-center">
                                </div>
                                <div class="form-group col-md-4">
                                    <b class="panel-label">OTORGANTE NUMERO ID:</b>
                                    <input required="required" name="otorganteNumId" type="text" placeholder="800941000" value="800941000" class="form-control text-center">
                                </div>
                                <div class="form-group col-md-4">
                                    <b class="panel-label">OTORGANTE CUENTA</b>
                                    <input required="required" name="otorganteCuenta" type="text" placeholder="yyyy-mm-dd" value="103869" class="form-control text-center">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <b class="panel-label">FECHA EXPEDICION ID OTORGANTE:</b>
                                    <input required="required" name="expeditionDate" type="text" placeholder="dd/MM/yyyy" value="27/09/2011" class="form-control text-center">
                                </div>

                                <div class="form-group col-md-4">
                                    <b class="panel-label">TELEFONO:</b>
                                    <input required="required" name="phone" type="number" placeholder="3115879658" value="3115879658" class="form-control text-center">
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-primary float-right">Generar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('title')
Consulta Deceval
@endsection

@section('header-content')
Consulta Deceval
@endsection

@section('breadcrumb')
<li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i>Inicio</a></li>
<li class="active">Deceval</li>
@endsection

@section('js')
<script>
    function mayus(e) {
        e.value = e.value.toUpperCase();
    }
</script>
@endsection