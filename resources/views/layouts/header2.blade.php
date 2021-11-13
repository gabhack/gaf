<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
	<!-- Sidebar Toggle (Topbar) -->
	<button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
		<i class="fa fa-bars text-caribbean-green"></i>
	</button>

	<!-- Topbar Navbar -->
	<ul class="navbar-nav ml-auto">
		<!-- Nav Item - User Information -->
		<li class="nav-item dropdown no-arrow">
			<a class="nav-link dropdown-toggle text-black-pearl" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<span class="mr-2 d-none d-lg-inline small">{{ Auth::user()->name }}</span>
				<i class="fas fa-caret-down"></i>
			</a>
			<!-- Dropdown - User Information -->
			<div class="dropdown-menu dropdown-menu-right animated--grow-in" aria-labelledby="userDropdown">
				<div class="text-center p-3">
					<img src="/img/avatar-img.svg" alt="" class="img-fluid w-75 mb-3">
					<span class="user-name d-block text-uppercase text-black-pearl mb-1">{{ Auth::user()->name }}</span>
					<span class="user-position d-block text-uppercase">{{ IsCompany() ? 'Compañía' : roles_label(Auth::user()->rol->rol) }}</span>
					<small class="user-company d-block text-uppercase text-black-pearl">Ingeniería & Construcciones SAS</small>
					<div class="text-center mt-3">
						<a class="btn btn-black-pearl btn-sm btn-profile px-3 mr-3" href="{{ url('profile') }}">
							Mi Perfil
						</a>
						<a class="btn-logout text-black-pearl px-2" href="{{ route('logout') }}">
							<small>Salir</small>
						</a>
					</div>
				</div>
			</div>
		</li>
	</ul>
</nav>