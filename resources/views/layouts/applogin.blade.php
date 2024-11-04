
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

		<title>{{ config('app.name', 'AMI') }}</title>

		<base href="{{url('/')}}">
		
		<!-- Favicon -->
		<link rel="icon" type="image/png" href="/favicon.png">

		<!-- Styles -->
		<link href="{{ asset('css/all.css') }}" rel="stylesheet">
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/pages/login.css') }}" rel="stylesheet">

		<!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;900&display=swap" rel="stylesheet">

		<script type="text/javascript">
			function callbackThen(response){
				// read HTTP status
				console.log(response.status);

				// read Promise object
				response.json().then(function(data){
					console.log(data);
				});
			}
			function callbackCatch(error){
				console.error('Error:', error)
			}
		</script>

		{!! htmlScriptTagJsApi([
			'action' => 'login',
			'callback_then' => 'callbackThen',
			'callback_catch' => 'callbackCatch'
		]) !!}
	</head>
	<body>
		<!-- Loader -->
		<div class="loader">
			<div class="text-center">
				<div class="spinner-border" role="status">
				</div>
			</div>
		</div>

		<!-- Container -->
		<div class="container-fluid h-100">
			@yield('content')
		</div>

		<!-- Scripts -->
		<script src="{{asset('js/jquery-3.5.1.min.js')}}"></script>
		<script src="{{asset('css/bootstrap-4.3.1/dist/js/bootstrap.bundle.min.js')}}"></script>
		<script src="{{asset('js/scripts.js')}}"></script>

	</body>
</html>
