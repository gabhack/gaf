
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
	<body class="skin-blue sidebar-mini">
		<!-- Loader -->
		<div class="loader">
			<div class="text-center">
				<div class="spinner-border" role="status">				
				</div>			
			</div>
		</div>
		
		<!-- Container -->
        <div class="container login">
            @yield('content')
        </div>

        <footer class="login-footer">
            <div class="pull-right hidden-xs">
                <strong>Copyright © {{ date('Y') }}</strong>
            </div>
            <strong>Developed by <a href="">GAF</a></strong>
        </footer>
			
		<!-- Scripts -->
		<script src="{{asset('js/jquery-3.5.1.min.js')}}"></script>
		<script src="{{asset('css/bootstrap-4.3.1/dist/js/bootstrap.bundle.min.js')}}"></script>
		<script src="{{asset('js/scripts.js')}}"></script>		
				
	</body>
</html>
