<!-- Sidebar -->
	<div class="main-sidebar" id="sidebar-wrapper">
		<ul class="sidebar sidebar-menu">
			<li class="header">MENU PRINCIPAL</li>
			
			<li><a href="{{url('home')}}"><i class="fa fa-home"></i> <span>Inicio</span></a></li>
			
			<li class="treeview">
				<a class="arrow"><i class="fa fa-key"></i> <span>Administraci&oacute;n</span></a>
				<ul class="treeview-menu">						
					<li><a href="{{url('roles')}}"><i class="fa fa-users"></i> <span>Roles</span></a></li>						
					<li><a href="{{url('usuarios')}}"><i class="fa fa-user"></i> <span>Usuarios</span></a></li>											
					<li><a href="{{url('oficinas')}}"><i class="fa fa-building-o"></i> <span>Oficinas</span></a></li>					
					<li><a href="{{url('parametros')}}"><i class="fa fa-wrench"></i> <span>Par&aacute;metros</span></a></li>
				</ul>
			</li>
			
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
			
			<li><a href="{{url('planos')}}"><i class="fa fa-file-text-o"></i> <span>Carga de Archivos</span></a></li>			
			<li><a href="{{url('consultas')}}"><i class="fa fa-user"></i> <span>Consultar persona</span></a></li>			
			{{-- <li><a href="{{url('comerciales')}}"><i class="fa fa-user"></i> <span>Comerciales</span></a></li> --}}
			<li><a href="{{url('terecuperamos')}}"><i class="fa fa-handshake-o"></i> <span>T-Recuperamos</span></a></li>
			<li><a href="{{url('reportes')}}"><i class="fa fa-bar-chart-o"></i> <span>Reportes</span></a></li>
			<li><a href="{{url('logout')}}"><i class="fa fa-sign-out"></i> <span>Salir</span></a></li>			
		</ul>	 
	</div>
<!-- /#sidebar-wrapper -->