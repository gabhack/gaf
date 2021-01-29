
<!DOCTYPE html>
<html lang="en">
    <meta charset="utf-8">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>{{ config('app.name', 'Laravel') }}</title>
		
		<base href="{{url('/')}}">		

		<!-- Styles -->
		<link href="{{asset('css/all.css')}}" rel="stylesheet">
		<link href="{{asset('css/bootstrap-4.3.1/dist/css/bootstrap.min.css')}}" rel="stylesheet">
		<link href="{{asset('css/simple-sidebar.css')}}" rel="stylesheet">    
		<link href="{{asset('css/font-awesome-4.7.0/css/font-awesome.min.css')}}" rel="stylesheet">
		<link href="{{asset('css/styles.css')}}" rel="stylesheet">
		@yield('css')  
	</head>
	<body class="skin-blue sidebar-mini sidebar-collapse">
		<!-- Loader -->
		<div class="loader">
			<div class="text-center">
				<div class="spinner-border" role="status">				
				</div>			
			</div>
		</div>
		
		<!-- Container -->
		<div class="wrapper" id="wrapper">
						
			@include('layouts.header')		
			@include('layouts.sidebar')		
			
			<div class="content-wrapper">
				<div class="content">
					@yield('content')
				</div>			
			</div>		
			<footer class="main-footer">
				<div class="pull-right hidden-xs">
					<strong>Copyright © {{ date('Y') }}</strong>
				</div>
				<strong>Developed by <a href="">GAF</a></strong>
			</footer>			
		</div>
			
		<!-- Scripts -->
		<!-- Scripts para funcionalidad global -->
		<script src="{{asset('js/jquery-3.5.1.min.js')}}"></script>
		<script src="{{asset('css/bootstrap-4.3.1/dist/js/bootstrap.bundle.min.js')}}"></script>
		<!-- Scripts de aplicación -->
		@yield('js')
		<script src="{{asset('js/scripts.js')}}"></script>

	</body>
</html>
