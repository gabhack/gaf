	<header class="main-header">
		
		<div class="sidebar-heading">
			<a class="logo" href="{{ url('/') }}">
				{{ config('app.name', 'Laravel') }}
			</a>
		</div>
	  
		<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
			<button class="btn sidebar-toggle" id="sidebar-toggle" type="button" data-toggle="collapse">
				<span class="navbar-toggler-icon"></span>
			</button>
			
			<div class="navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav ml-auto mt-2 mt-lg-0">				
					@if (Auth::guest())
						<li><a class="nav-link" href="{{ route('login') }}">Login</a></li>                            
					@else
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								{{ Auth::user()->name }}
							</a>

							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">		
								<a class="dropdown-item" href="{{url('profile')}}">Mi Perfil</a>
								
								<div class="dropdown-divider"></div>								
								<a  class="dropdown-item" href="{{ route('logout') }}">Salir</a>
							</div>
						</li>
					@endif
				</ul>
			</div>
		</nav>		
	</header>