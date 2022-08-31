@extends('layouts.app2')

@section('content')
<div class="col-md-12">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link" href="{{ url('pagos') }}">Lista de Pagos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active">Realizar Pago</a>
        </li>
    </ul>
    <br />
    <div class="panel-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <a href="{{ url('pagos/pagar') }}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Pago con Tarjeta</a>
                <a href="{{ url('pagos/pagarpse') }}" class="btn btn-secondary btn-lg disabled" role="button" aria-disabled="true">Pagar con PSE</a>
                <a href="{{ url('pagos/pagarefectivo') }}" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Pago en Efectivo</a>
            </div>
            <img src="{{ asset('img/logo-openpay.png') }}" alt="" width="220">
        </div>
        <form method="POST" action="{{ url('pagos/payPSE') }}">
            {{ csrf_field() }}
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="nombre">Nombre</label>
                    <input type="hidden" name="device_session_id" id="device_session_id">
                    <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre" value="{{ old('nombre') }}" required>
                    <input type="hidden" value="{{ $source_id }}" name="" id="">
                </div>
                <div class="form-group col-md-4">
                    <label for="apellido">Apellidos</label>
                    <input type="text" class="form-control" name="apellido" id="apellido" placeholder="Apellidos" value="{{ old('apellido') }}" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Correo Electrónico" value="{{ old('email') }}" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="telefono">Teléfono</label>
                    <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Teléfono" value="{{ old('telefono') }}" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="concepto">Concepto</label>
                    <input type="text" class="form-control" name="concepto" id="concepto" placeholder="Concepto" value="{{ old('concepto') }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="monto">Total</label>
                    <input type="number" min="0.00" max="10000.00" step="0.01" class="form-control" name="monto" id="monto" value="{{ old('monto') }}" placeholder="Total" required>
                </div>
            </div>
            <button id="payPSE" type="submit" class="btn btn-primary">Pagar PSE</button>
        </form>
    </div>
</div>

<!-- small modal -->
<div class="modal fade" id="smallModal" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="smallBody">
                <div>
                    <iframe id="linkPago" src="" height="200" width="300" title="Iframe Example"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('title')
Usuario / Crear
@endsection

@section('header-content')
Realizar Pago
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ url('home') }}"><i class="fa fa-dashboard mr-2"></i>Inicio</a></li>
<li class="breadcrumb-item"><a href="{{ url('usuarios') }}">Pagos</a></li>
<li class="breadcrumb-item active">Realizar Pago</li>
@endsection


@section('js')
<script src="https://openpay.s3.amazonaws.com/openpay.v1.js"></script>
<script src="https://openpay.s3.amazonaws.com/openpay-data.v1.js"></script>
<script>
    $(document).ready(function() {
        OpenPay.setId('mbj7d0ylmxkrlg4m1tcu');
        OpenPay.setApiKey('sk_382ccfcb3356474082d575c4facfefb6');
        OpenPay.setSandboxMode(true);
        var deviceDataId = OpenPay.deviceData.setup();
        $('#device_session_id').val(deviceDataId);
    });

    $("#payPSE").click(function(e) {
        e.preventDefault();
        var device_session_id = $("#device_session_id").val();
        var nombre = $("#nombre").val();
        var apellido = $("#apellido").val();
        var email = $("#email").val();
        var telefono = $("#telefono").val();
        var concepto = $("#concepto").val();
        var monto = $("#monto").val();
        $("#page-top").fadeOut(1000);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/pagos/payPSE',
            data: {
                device_session_id: device_session_id,
                nombre: nombre,
                apellido: apellido,
                email: email,
                telefono: telefono,
                concepto: concepto,
                monto: monto
            },
            success: function(data) {
                //$('#smallModal').modal("show");
                //$('#smallBody').html(data).show();
                //$('#linkPago').attr('src',data);
                location.href = data;
            },
            //complete: function() {
            //$('#loader').hide();
            //},
            error: function(data) {
                $('.btn-submit').prop('disabled', false);
            }
        });
    });
</script>
@endsection