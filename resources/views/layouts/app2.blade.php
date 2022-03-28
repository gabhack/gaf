<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

		<title>{{ Auth::user()->rol->rol == 'ADMIN_HEGO' ? 'HEGO' : 'AMI' }} - @yield('title')</title>

		<!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

		<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800;900&display=swap" rel="stylesheet">

		<!-- Styles -->
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
		<link href="{{ asset('css/pages/dashboard.css') }}" rel="stylesheet">
		<link href="{{asset('css/font-awesome-4.7.0/css/font-awesome.min.css')}}" rel="stylesheet">

		<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
		<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script src="http://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.2/js/toastr.min.js"></script>    

		@yield('css')

	</head>
	<body id="page-top" class="sidebar-toggled">
		<div id="app">
			<!-- Loader -->
			<div class="loader">
				<div class="text-center">
					<div class="spinner-border" role="status"></div>
				</div>
			</div>
			<!-- Page Wrapper -->
			<div id="wrapper">
				<!-- Sidebar -->
				@include('layouts.sidebar2')
				<!-- End of Sidebar -->

				<!-- Content Wrapper -->
				<div id="content-wrapper" class="d-flex flex-column">
					<!-- Main Content -->
					<div id="content">
						<!-- Topbar -->
						@include('layouts.header2')
						<!-- End of Topbar -->

						<div class="container-fluid breadcrumb-wrap">
							<h1 class="h4 text-black-pearl font-weight-exbold mb-0">
								@yield('header-content')
							</h1>
							<ol class="breadcrumb bg-transparent mb-0">
								@yield('breadcrumb')
							</ol>
						</div>

						<!-- Begin Page Content -->
						@yield('content')
						<!-- End of Page Content -->
					</div>
					<!-- End of Main Content -->

					<!-- Footer -->
					<footer class="sticky-footer py-3">
						<div class="container-fluid">
							<div class="copyright">
								<p class="text-spring-green font-weight-light mb-0">Developed by <span class="font-weight-bold">GAF Solutions™</span></p>
							</div>
						</div>
					</footer>
					<!-- End of Footer -->
				</div>
				<!-- End of Content Wrapper -->
			</div>
			<!-- End of Page Wrapper -->

			<!-- Scroll to Top Button-->
			<a class="scroll-to-top rounded" href="#page-top">
				<i class="fas fa-angle-up"></i>
			</a>
		</div>

		<!-- Scripts -->
		<script src="{{asset('js/app.js')}}"></script>
		<script src="{{asset('js/sb-admin-2.js')}}"></script>

		@yield('js')
		<script src="{{asset('js/scripts.js')}}"></script>

	</body>
</html>
