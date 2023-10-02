@extends('layouts.app2')

@section('title')
HEGO | Ingresar Recaudo
@endsection

@section('header-content')
Ingresar Recaudo
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ url('home') }}">
        <i class="fa fa-dashboard mr-2"></i>Inicio
    </a>
</li>
<li class="breadcrumb-item active">Ingresar Recaudo</li>
@endsection

@section('content')

<div class="container-fluid" style="display: flex; justify-content: center;">
    <div class="row">

        <div class="col-md-12">
            <div class="btn-group mr-2 float-right" role="group">
                <a type="button" class="btn btn-secondary" href="{{url('estudios')}}"><i class="fa fa-arrow-left"></i> Atrás</a>
            </div>
        </div>

        <form id="form_guardar" action="{{ url('estudios/recaudos/guardar') }}" method="POST" enctype="multipart/form-data">
            {!! Form::token() !!}
            <input type="hidden" value="{{ $id }}" name="estudio_id">
            <table border="0" cellspacing="1" cellpadding="2" >
            <tbody><tr>
                <td>
                    <h2>DATOS RECAUDO</h2>
                    <div class="box1 clearfix">
                      <form>  
                    <table border="0" cellspacing="1" cellpadding="2">
                    <tbody><tr>
                        <td>* Cuotas</td>
                        <td>
                            <select name="plan_id" style="" onchange="valorCuota(this)" required>
                                <option value="" disabled selected></option>
                                <option value="all">Cancelar Todo</option>
                                @foreach($planes as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->num_cuota }}</option>
                                @endforeach
                            </select>
                        </td>

                        <td>* T RECAUDO</td>
                        <td>
                            <select name="tipo_recaudo" style="">
                                <option value=""></option>

                            </select>
                        </td>
                        <td width="20">&nbsp;</td>
                        <td>* F RECAUDO</td>
                        <td><input type="text" readonly value="{{ date('Y-m-d') }}" name="fecha" size="10" style="text-align:center; " ></td>
                        <td width="20">&nbsp;</td>
                        <td>* VR TOTAL</td>
                        <td><input type="text" id="valor" name="valor" size="10" style="text-align:center; " >
                        </td>
                        <td width="20">&nbsp;</td>
                        <td>SOPORTE</td>
                        <td><input type="file" name="archivo" style="text-align:center; "></td>
                        <td></td>
                        <td><button type="submit" class="btn btn-primary" >Guardar</button></td>
                    </tr>
                    </tbody></table>
                    </div>
                </td>
            </tr>
            </tbody></table>
        </form>
            <br>
            <table border="0" class="table table-striped">
                <thead>
                    <tr>
                        <th width="10">No. Cuota</th>
                        <th width="90">F Cuota</th>
                        <th width="90">Vr Cuota</th>
                        <th width="90">Saldo Cuota</th>
                        <th width="90">Vr a Aplicar (*)</th>
                    </tr>
            </thead>
            <tbody>
            <tr>
                <td align="left">1</td> 
                <td align="left">2023-08-31</td> 
                <td align="left">1,539,363</td>
                <td align="left">1,539,363</td>
                <td align="left"><input type="hidden" name="saldo_cuota1" value=""><input type="text" name="valor_aplicar1" size="15" maxlength="11" style="text-align:right; "></td>
            </tr>
            </tbody></table>
            <br>
            <p align="center">
            </p>
        
    
    </div>
</div>
@endsection

@section('js')
    <script>

        var cuotas = {!! json_encode($planes, JSON_HEX_TAG) !!};

        var valorCuota = (e) => {
            cuotas.forEach(function(cuota) {
                if(cuota.id == $(e).val()) $("#valor").val(cuota.total_cuota);
            });

            if($(e).val() == 'all')  $("#valor").val(null);
        };
    </script>
@endsection
