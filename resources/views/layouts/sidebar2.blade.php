 <!-- Sidebar -->
<ul class="navbar-nav bg-black-pearl sidebar sidebar-dark accordion toggled" id="accordionSidebar">
	<!-- Sidebar - Brand -->
	<div class="py-3 text-center">
		<a class="" href="{{ url('home') }}">
			<img src="/img/logo-hego-sidebar.svg" alt="" class="img-fluid w-75 mx-auto">
		</a>
	</div>

	<!-- Nav Items-->
	<li class="nav-item">
		<a class="nav-link" href="{{ url('home') }}">
			<home-icon></home-icon>
			<span>Inicio</span>
		</a>
	</li>

	@if ((IsUser() || IsCompany()) && ( AMISilverHabilitado() || AMIGoldHabilitado() || AMIDiamondHabilitado() ) && !(IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin()))
	<li class="nav-item active">
		<a class="nav-link collapsed"
			href="#"
			data-toggle="collapse"
			data-target="#menuAMI"
			aria-expanded="false"
			aria-controls="menuAMI"
		>
			<ami-icon></ami-icon>
			<span>AMI®</span>
		</a>
		<div
			id="menuAMI"
			class="collapse"
			aria-labelledby="menuAMI"
			data-parent="#accordionSidebar"
		>
			<div class="bg-white py-2 collapse-inner">
				<a class="collapse-item btn-list" href="{{ url('historyClient' )}}">Listado de consultas</a>
				<!-- <a class="collapse-item btn-list" href="{{ url('consultas/list' )}}">Listado de consultas</a>		 -->
				<!-- <a class="collapse-item" href="/massiveCharge">Carga Masiva</a> -->
				<a class="collapse-item" href="{{ url('dataClientDraft') }}">Nueva consulta +</a>
				<!-- <a class="collapse-item" href="#">Consulta bloque</a> 				 -->
				<a class="collapse-item" href="{{ url('refundCartera') }}">Recuperacion de Cartera </a>
				<!-- <a class="collapse-item" href="#">Prospección de Mercado</a> -->
				<!-- <a class="collapse-item" href="{{ url('consultas') }}">Nueva Consulta</a> -->
			</div>
		</div>
	</li>
	@endif

	@if (HEGOAccess())
	<li class="nav-item">
		<a class="nav-link" href="{{ url('estudios') }}">
			<hego-icon></hego-icon>
			<span>HEGO®</span>
		</a>
	</li>

	<li class="nav-item">
		<a class="nav-link"href="/solicitud">
			<ami-icon></ami-icon>
			<span>Solicitud de Credito</span>
		</a>
	</li>

	<li class="nav-item">
		<a class="nav-link" href="{{ url('integration') }}">
			<hego-icon></hego-icon>
			<span>ID</span>
		</a>
	</li>
	@endif

	@if (IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin() || IsCompany() || IsUserCreator())
	<li class="nav-item">
		<a class="nav-link collapsed"
			href="#"
			data-toggle="collapse"
			data-target="#menuAdmon"
			aria-expanded="false"
			aria-controls="menuAdmon"
		>
			<admin-settings-icon></admin-settings-icon>
			<span>Admon</span>
		</a>
		<div
			id="menuAdmon"
			class="collapse"
			aria-labelledby="menuAdmon"
			data-parent="#accordionSidebar"
		>
			<div class="bg-white py-2 collapse-inner">
				<a class="collapse-item" href="{{ url('usuarios') }}">Usuarios</a>
				@if (IsSuperAdmin())
					<a class="collapse-item" href="{{ url('roles') }}">Roles</a>
					<a class="collapse-item" href="{{ url('parametros') }}">Parámetros</a>
					<a class="collapse-item" href="{{ url('factorxmillonkredit') }}">Factores X Millón Kredit</a>
				@endif
			</div>
		</div>
	</li>
	@endif

	@if (IsSuperAdmin())
	<li class="nav-item">
		<a class="nav-link collapsed"
			href="#"
			data-toggle="collapse"
			data-target="#menuMaestros"
			aria-expanded="false"
			aria-controls="menuMaestros"
		>
			<span>Maestros</span>
		</a>
		<div
			id="menuMaestros"
			class="collapse"
			aria-labelledby="menuMaestros"
			data-parent="#accordionSidebar"
		>
			<div class="bg-white py-2 collapse-inner">
				<a class="collapse-item" href="{{ url('departamentos') }}">Departamentos</a>
				<a class="collapse-item" href="{{ url('ciudades') }}">Ciudades</a>
				<a class="collapse-item" href="{{ url('cargos') }}">Cargos</a>
				<a class="collapse-item" href="{{ url('sectores') }}">Sectores</a>
				<a class="collapse-item" href="{{ url('estadoscartera') }}">Estados Cartera</a>
				<a class="collapse-item" href="{{ url('tiposembargo') }}">Tipos de Embargo</a>
				<a class="collapse-item" href="{{ url('pagadurias') }}">Pagadurías</a>
				<a class="collapse-item" href="{{ url('aliados') }}">Aliados</a>
				<a class="collapse-item" href="{{ url('entidades') }}">Entidades</a>
				<a class="collapse-item" href="{{ url('factores') }}">Factores</a>
			</div>
		</div>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="{{ url('planos') }}">
			<span>Carga de Archivos Manual</span>
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link" href="{{ url('planos/crear_gcp') }}">
			<span>Carga de Archivos Inteligente</span>
		</a>
	</li>
	@endif

	<!-- Sidebar Toggler (Sidebar) -->
	<div class="text-center d-none d-md-inline">
		<button class="rounded-circle border-0" id="sidebarToggle"></button>
	</div>
</ul>
<!-- End of Sidebar -->
