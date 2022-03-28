
<!DOCTYPE html>
<html lang="en">
    <meta charset="utf-8">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>{{ Auth::user()->rol->rol == 'ADMIN_HEGO' ? 'HEGO' : 'AMI' }} - @yield('title')</title>

		<base href="{{url('/')}}">

		<!-- Styles -->
		<link href="{{asset('css/all.css')}}" rel="stylesheet">
		<link href="{{asset('css/bootstrap-4.3.1/dist/css/bootstrap.min.css')}}" rel="stylesheet">
		<link href="{{asset('css/simple-sidebar.css')}}" rel="stylesheet">
		<link href="{{asset('css/font-awesome-4.7.0/css/font-awesome.min.css')}}" rel="stylesheet">
		<link href="{{asset('css/styles.css')}}" rel="stylesheet">
		
		<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
		<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script src="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js"></script>    
		@yield('css')
	</head>
	<body class="skin-{{ Auth::user()->rol->rol == 'ADMIN_HEGO' ? 'red' : 'blue' }} sidebar-mini sidebar-open">
		<!-- Loader -->
		<div class="loader">
			<div class="text-center">
				<div class="spinner-border" role="status"></div>
			</div>
		</div>

		<!-- Container -->
		<div class="wrapper" id="wrapper">

			@include('layouts.header')
			@include('layouts.sidebar')

			<div class="content-wrapper">
				<section class="content-header">
					<h1>
						@yield('header-content')
					</h1>
					<ol class="breadcrumb">
						@yield('breadcrumb')
					</ol>
				</section>
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
