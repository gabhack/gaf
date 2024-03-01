<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Personalizado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #1e8271;
            /* Color verde oscuro */
            background-color: #1cc88a;
            /* Color verde */
            text-align: center;
            padding-top: 10%;
        }

        .error-container {
            display: inline-block;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px #1e8271;
        }

        .error-face {
            font-size: 100px;
            color: #1e8271;
        }

        h1 {
            color: #1cc88a;
        }

        p {
            color: black;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-face">:(</div>
        <h1>Ups...</h1>
        <p>Lo sentimos, ha ocurrido un error.</p>
        <p>URL solicitada: <?php echo $_SERVER['REQUEST_URI']; ?></p>
        @if (session('error'))
            <p>Error: {{ session('error') }}</p>
        @endif
    </div>
</body>

</html>
