<html>

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 0cm 0cm;
            font-family: Arial;
        }

        body {
            margin: 4cm 0 2cm;
        }

        header {
            position: fixed;
            top: 1cm;
            left: 0cm;
            right: 0cm;
            margin-bottom: 2cm;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
            background-color: #2a0927;
            color: white;
            text-align: center;
            line-height: 35px;
        }

        h3 {
            font-size: 25px;
            margin: 15px 0 0 0;
            width: 100%;
        }

        h4 {
            font-size: 17px;
            margin: 8px 0;
            width: 100%;
        }

        .Header {
            clear: both;
            height: 100px;
            width: 100%;
        }

        .Logo_empresa img {
            background-color: #021b1e !important;
            float: left;
        }

        .Logo_paynet {
            float: right;
            margin-top: 3px;
            margin-right: 1cm;
        }

        .Logo_paynet div {
            font-size: 20px;
            font-weight: lighter;
            display: block;
            float: left;
            margin: 10px 12px 0 0;
        }

        .Logo_paynet img {
            width: 130px;
            float: left;
        }

        .Data {
            width: 100%;
            max-width: 100%;
            clear: both;
            display: block;
            margin-bottom: 1cm;
            float: left;
        }

        .DT-margin {
            margin: 15px 0;
            display: block;
            float: left;
            width: 100%;
            clear: both;
        }

        .Big_Bullet {
            width: 40px;
            float: left;
            margin-right: 24px;
        }

        .Big_Bullet span,
        .col2 {
            background-color: #f9b317;
        }

        .Big_Bullet span {
            width: 100%;
            height: 55px;
            display: block;
        }

        .col1 {
            width: 35%;
            margin-left: 2cm;
            float: left;
        }

        .col1 span {
            font-size: 14px;
            clear: both;
            display: block;
            margin: 5px 0;
        }

        .col1 small {
            font-size: 12px;
            width: 320px;
            display: block;
        }

        .col2 {
            width: 40%;
            float: right;
            color: #FFF;
            padding: 40px 0 40px 40px;
        }

        .col2 h1 {
            margin: 0;
            padding: 0;
            ont-size: 60px;
        }

        .col2 h1 span {
            font-size: 45px;
        }

        .col2 h1 small {
            font-size: 20px;
        }

        .col2 h2 {
            margin: 0;
            font-size: 22px;
            font-weight: lighter;
        }

        .S-margin {
            padding-left: 80px;
        }

        .Table-Data {
            margin: 20px 0 0 0;
            clear: both;
            width: 100%;
            max-width: 100%;
            display: block;
            float: left;
        }

        .table-row {
            display: block;
            width: 100%;
            padding: 0 1cm;
            height: 40px;
        }

        .table-row div {
            float: left;
            width: 50%;
            padding: 15px 0;
            display: block;
        }

        .table-row span {
            float: right;
            width: 50%;
            border-left: 3px solid #FFF;
            padding: 15px 0 15px 40px;
        }

        .color1 {
            background-color: #F3F3F3;
        }

        .color2 {
            background-color: #EBEBEB;
        }

        .col1 ol,
        .col2 ol {
            font-size: 12px;
            width: 290px;
            padding-left: 20px;
        }

        .logos-tiendas {
            clear: both;
            float: left;
            width: 100%;
            padding: 10px 0 10px 8%;
            border-top: 1px solid #EDEDED;
            height: 50px;
            display: block;
        }

        .logos-tiendas .mastiendas {
            margin-right: 50px;
            width: 40%;
            display: block;
            height: 90px;
            float: right;
            margin-left: 1.5cm;
            margin-top: 30px;
        }

        .logos-tiendas small {
            font-size: 11px;
            margin-left: 20px;
            float: left;
        }

        .logos-tiendas .tiendas {
            margin: 0;
            list-style: none;
            padding: 0;
            width: 55%;
            display: block;
            float: left;
        }

        .logos-tiendas img {
            width: 80px;
            display: inline;
            margin: 10px 10px 0 10px;
        }

        .Powered {
            width: 100%;
            float: left;
            margin-top: 18px;
        }

        .Powered img {
            margin-left: 65px;
            margin-right: 290px;
        }

        .Powered a {
            border-radius: 6px;
            background-color: #0075F0;
            padding: 7px 13px;
            color: #FFF;
            font-size: 16px;
            font-weight: normal;
            text-decoration: none;
            margin: 10px;
        }

        .Powered a:hover {
            background-color: #009BFF;
        }

        .note {
            font-size: 12px;
        }

        .limpiar {
            clear: both;
        }

        .barra {
            float: left;
            margin: 0;
            height: 50px;
        }

        .fecha {
            height: 100px;
            display: block;
            float: left;
        }
    </style>
</head>

<body>
    <header>
        <div class="Header">
            <div class="Logo_empresa">
                <img src="../public/img/tiendas/logo-gaf.svg" width="100" alt="Logo">
            </div>
            <div class="Logo_paynet">
                <div>Servicio a pagar</div><br><br>
                <img src="../public/img/tiendas/openpay-color.png" alt="Logo Openpay">
            </div>
        </div>
    </header>
    <main>
        <div class="Data">
            <div class="Big_Bullet">
                <span></span>
            </div>
            <div class="col1">
                <div class="fecha">
                    <h3>Fecha límite de pago</h3>
                    <h4>{{ $due_date }}</h4>
                </div>
                <div class="limpiar"></div>
                <div class="barra">
                    <img height="50" src="{{ $payment_method['barcode_url'] }}" alt="Código de Barras">
                </div>

                <br>
                <div class="limpiar"></div>
                <span>{{ $payment_method['reference'] }}</span>
                <small>En caso de que el escáner no sea capaz de leer el código de barras, escribir la referencia tal como se muestra.</small>
            </div>
            <div class="col2">
                <h2>Total a pagar</h2>
                <h1>$ <span>{{ $amount }}</span>.00 <small> {{ $currency }}</small></h1>
                <span class="note">La comisión por recepción del pago varía de acuerdo a los términos y condiciones que cada cadena comercial establece.</span>
            </div>
        </div>
        <div class="limpiar"></div>
        <div class="DT-margin"></div><br><br>
        <div class="Data">
            <div class="Big_Bullet">
                <span></span>
            </div>
            <div class="col1">
                <h3>Detalles de la compra</h3>
            </div>
        </div>
        <div class="limpiar"></div>
        <div class="Table-Data">
            <div class="table-row color1">
                <div>Descripción</div>
                <span>{{ $description }}</span>
            </div>
            <div class="table-row color2">
                <div>Fecha y hora</div>
                <span>{{ $operation_date }}</span>
            </div>
            <div class="table-row color1">
                <div>ID de Transferencia</div>
                <span>{{ $id }}</span>
            </div>
            <div class="table-row color2" style="display:none">
                <div>&nbsp;</div>
                <span>&nbsp;</span>
            </div>
            <div class="table-row color1" style="display:none">
                <div>&nbsp;</div>
                <span>&nbsp;</span>
            </div>
        </div>
        <div class="limpiar"></div>
        <div class="DT-margin"></div><br><br>
        <div class="Data">
            <div class="Big_Bullet">
                <span></span>
            </div>
            <div class="col1">
                <h3>Como realizar el pago</h3>
                <ol>
                    <li>Acude a cualquier tienda afiliada</li>
                    <li>Entrega al cajero el código de barras y menciona que realizarás un pago de servicio Paynet</li>
                    <li>Realizar el pago en efectivo por $9,000.00 MXN </li>
                    <li>Conserva el ticket para cualquier aclaración</li>
                </ol>
                <small>Si tienes dudas comunícate a NOMBRE TIENDA al teléfono TELEFONO TIENDA o al correo CORREO SOPORTE TIENDA</small>
            </div>
            <div class="col1" style="float:right; width:45%">
                <h3>Instrucciones para el cajero</h3>
                <ol>
                    <li>Ingresar al menú de Pago de Servicios</li>
                    <li>Seleccionar Paynet</li>
                    <li>Escanear el código de barras o ingresar el núm. de referencia</li>
                    <li>Ingresa la cantidad total a pagar</li>
                    <li>Cobrar al cliente el monto total más la comisión</li>
                    <li>Confirmar la transacción y entregar el ticket al cliente</li>
                </ol>
                <small>Para cualquier duda sobre como cobrar, por favor llamar al teléfono +52 (55) 5351 7371 en un horario de 8am a 9pm de lunes a domingo</small>
            </div>
        </div>
        <div class="limpiar"></div><br><br>
        <div class="logos-tiendas">
            <div class="tiendas">
                <img src="../public/img/tiendas/1.png" width="50">
                <img src="../public/img/tiendas/2.png" width="50">
                <img src="../public/img/tiendas/3.png" width="50">
                <img src="../public/img/tiendas/4.png" width="50">
                <img src="../public/img/tiendas/5.png" width="50">
                <img src="../public/img/tiendas/6.png" width="50">
                <img src="../public/img/tiendas/7.png" width="50">
                <img src="../public/img/tiendas/8.png" width="50">
            </div>
            <div class="mastiendas">
                ¿Quieres pagar en otras tiendas?<br>
                visítanos en: www.openpay.co/efectivo
            </div>
        </div>

    </main>

</body>

</html>