	<header class="main-header">
		
		<div class="sidebar-heading">
			<a class="logo" href="{{ url('/') }}">
				{{ Auth::user()->hego ? 'HEGO' : 'AMI' }}
			</a>
		</div>
	  
		<nav class="navbar navbar-expand-lg navbar-light">
			<button class="btn sidebar-toggle" id="sidebar-toggle" type="button" data-toggle="collapse">
				<span class="navbar-toggler-icon"></span>
			</button>
			
			<div class="navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav ml-auto mt-2 mt-lg-0">				
					@if (Auth::guest())
						<li><a class="nav-link" href="{{ route('login') }}">Login</a></li>                            
					@else
						{{-- <li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								{{ Auth::user()->name }}
							</a>

							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">		
								<a class="dropdown-item" href="{{url('profile')}}">Mi Perfil</a>
								
								<div class="dropdown-divider"></div>								
								<a  class="dropdown-item" href="{{ route('logout') }}">Salir</a>
							</div>
						</li> --}}
						<li class="dropdown notifications-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-bell-o"></i>
								<span class="label label-warning">10</span>
							</a>
							<ul class="dropdown-menu">
								<li class="header">You have 10 notifications</li>
								<li>
									<!-- inner menu: contains the actual data -->
									<ul class="menu">
									<li>
										<a href="#">
										<i class="fa fa-users text-aqua"></i> 5 new members joined today
										</a>
									</li>
									<li>
										<a href="#">
										<i class="fa fa-warning text-yellow"></i> Very long description here that may not fit into the
										page and may cause design problems
										</a>
									</li>
									<li>
										<a href="#">
										<i class="fa fa-users text-red"></i> 5 new members joined
										</a>
									</li>
									<li>
										<a href="#">
										<i class="fa fa-shopping-cart text-green"></i> 25 sales made
										</a>
									</li>
									<li>
										<a href="#">
										<i class="fa fa-user text-red"></i> You changed your username
										</a>
									</li>
									</ul>
								</li>
								<li class="footer"><a href="#">View all</a></li>
							</ul>
						</li>
						<li class="nav-item dropdown user user-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
								<img src="{{ IsCompany() ? 'img/unnamed.png' : 'img/avatar5.png' }}" class="user-image" alt="User Image">
								<span class="hidden-xs">{{ Auth::user()->name }}</span>
							</a>
							<ul class="dropdown-menu">
								<!-- User image -->
								<li class="user-header">
									<img src="{{ IsCompany() ? 'img/unnamed.png' : 'img/avatar5.png' }}" class="img-circle" alt="User Image">
									<p>
										{{ Auth::user()->name }}
										<small><b>{{ IsCompany() ? 'Compañía' : roles_label(Auth::user()->role->name) }}</b></small>
									</p>
							  	</li>
								<!-- Menu Footer-->
								<li class="user-footer">
									<div class="pull-left">
									<a href="{{url('profile')}}" class="btn btn-default btn-flat">Mi Perfil</a>
									</div>
									<div class="pull-right">
									<a href="{{ route('logout') }}" class="btn btn-default btn-flat">Salir</a>
									</div>
							  	</li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</nav>
	</header>