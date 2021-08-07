<!-- Sidebar -->
	<div class="main-sidebar" id="sidebar-wrapper">
		<ul class="sidebar sidebar-menu">
			<div class="user-panel">
				<div class="pull-left image">
				  <img src="{{ IsCompany() ? 'img/unnamed.png' : 'img/avatar5.png' }}" class="img-circle" alt="User Image">
				</div>
				<div class="pull-left info">
				  <p>{{ Auth::user()->name }}</p>
				  <small><i class="fa fa-circle text-success"></i> Online</small>
				</div>
			  </div>
			<li class="header">MENÚ</li>
			
			<li><a href="{{url('home')}}"><i class="fa fa-home"></i> <span>Inicio</span></a></li>

			@if ((IsUser() || IsCompany()) && ( AMISilverHabilitado() || AMIGoldHabilitado() || AMIDiamondHabilitado() ) && !(IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin()))
			<li class="treeview">
				<a class="arrow"><i class="fa fa-key"></i> <span>AMI®</span></a>
				<ul class="treeview-menu">
					<li><a href="{{url('consultas')}}"><i class="fa fa-plus"></i> <span>Nueva Consulta</span></a></li>
					<li><a href="{{url('consultas/list')}}"><i class="fa fa-list"></i> <span>Listado de consultas</span></a></li>
				</ul>
			</li>
			@endif

			@if (HEGOAccess())
				<li><a href="{{url('estudios')}}"><i class="fa fa-handshake-o"></i> <span>Simulación HEGO®</span></a></li>
			@endif
			
			@if (IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin() || IsCompany() || IsUserCreator())
				<li class="treeview">
					<a class="arrow"><i class="fa fa-key"></i> <span>Administraci&oacute;n</span></a>
					<ul class="treeview-menu">
						<li><a href="{{url('usuarios')}}"><i class="fa fa-user"></i> <span>Usuarios</span></a></li>	
						@if (IsSuperAdmin())
							<li><a href="{{url('roles')}}"><i class="fa fa-users"></i> <span>Roles</span></a></li>						
							<li><a href="{{url('oficinas')}}"><i class="fa fa-building-o"></i> <span>Oficinas</span></a></li>
							<li><a href="{{url('parametros')}}"><i class="fa fa-wrench"></i> <span>Par&aacute;metros</span></a></li>
							<li><a href="{{url('factorxmillonkredit')}}"><i class="fa fa-wrench"></i> <span>Factores X Millón Kredit</span></a></li>
						@endif
					</ul>
				</li>
			@endif
			@if (IsSuperAdmin())
				<li class="treeview">
					<a class="arrow"><i class="fa fa-cog"></i> <span>Maestros</span></a>
					<ul class="treeview-menu">
						<li><a href="{{url('departamentos')}}"><i class="fa fa-map-marker"></i> <span>Departamentos</span></a></li>
						<li><a href="{{url('ciudades')}}"><i class="fa fa-map-marker"></i> <span>Ciudades</span></a></li>
						<li><a href="{{url('cargos')}}"><i class="fa fa-sitemap"></i> <span>Cargos</span></a></li>
						<li><a href="{{url('sectores')}}"><i class="fa fa-pie-chart"></i> <span>Sectores</span></a></li>
						<li><a href="{{url('estadoscartera')}}"><i class="fa fa-check-circle-o"></i> <span>Estados Cartera</span></a></li>
						<li><a href="{{url('demandantes')}}"><i class="fa fa-gavel"></i> <span>Demandantes</span></a></li>
						<li><a href="{{url('tiposembargo')}}"><i class="fa fa-tags"></i> <span>Tipos de Embargo</span></a></li>
						<li><a href="{{url('pagadurias')}}"><i class="fa fa-bank"></i> <span>Pagadur&iacute;as</span></a></li>
						<li><a href="{{url('aliados')}}"><i class="fa fa-building-o"></i> <span>Aliados</span></a></li>
						<li><a href="{{url('entidades')}}"><i class="fa fa-suitcase"></i> <span>Entidades</span></a></li>
						<li><a href="{{url('factores')}}"><i class="fa fa-percent"></i> <span>Factores</span></a></li>
					</ul>
				</li>
				<li><a href="{{url('planos')}}"><i class="fa fa-file-text-o"></i> <span>Carga de Archivos Manual</span></a></li>			
				<li><a href="{{url('planos/crear_gcp')}}"><i class="fa fa-file-text-o"></i> <span>Carga de Archivos Inteligente</span></a></li>
			@endif
		</ul>	 
	</div>
<!-- /#sidebar-wrapper -->