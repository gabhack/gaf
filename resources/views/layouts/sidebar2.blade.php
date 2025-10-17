<ul class="navbar-nav bg-black-pearl sidebar sidebar-dark accordion" id="accordionSidebar" style="height: 100%">
    <div class="pt-4 pl-3 pb-2 text-left">
        <a class="" href="{{ url('home') }}">
            <img class="img-fluid w-60 mx-auto" src="/img/GAFlogosidebar.png" alt="">
        </a>
    </div>

    <li class="nav-item">
        <a class="nav-link" href="{{ url('home') }}" style="padding-left: 28px;">
            <dash-icon></dash-icon>
            <span class="pl-2">Dashboard</span>
        </a>
    </li>

    <div class="borders-space">
        <div class="pl-4 mb-4">
            <span class="sidebar-titles1">AMI®</span><br>
            <span class="sidebar-titles2">Análisis de mercado inteligente</span>
        </div>

        @can('permission', 'ver usuarios')
        <li class="nav-item">
            <a class="nav-link collapsed" data-toggle="collapse" data-target="#menuEmpresas" href="#" aria-expanded="false" aria-controls="menuEmpresas">
                <users-icon></users-icon>
                <span class="pl-2">Usuarios</span>
            </a>
            <div class="collapse" id="menuEmpresas" data-parent="#accordionSidebar" aria-labelledby="menuEmpresas">
                <div class="bg-green-side py-2 collapse-inner">
                    @can('permission', 'ver empresas')
                    <a class="collapse-item" href="/empresas">Empresas</a>
                    @endcan
                    @can('permission', 'ver area comercial')
                    <a class="collapse-item" href="/area-comerciales">Area comercial</a>
                    @endcan
                    @can('permission', 'ver sedes')
                    <a class="collapse-item" href="/sedes">Sedes</a>
                    @endcan
                </div>
            </div>
        </li>
        @endcan

        <li class="nav-item">
            <a class="nav-link collapsed" data-toggle="collapse" data-target="#menuSolicitudes" href="#" aria-expanded="false" aria-controls="menuSolicitudes">
                <visado-icon></visado-icon>
                <span class="pl-2">Consulta</span>
            </a>
            <div class="collapse" id="menuSolicitudes" data-parent="#accordionSidebar" aria-labelledby="menuSolicitudes">
                <div class="bg-green-side py-2 collapse-inner">
                    <a class="collapse-item" href="/credit-request">Consulta individual</a>
                    <a class="collapse-item" href="{{ route('credit-request.bulk') }}">Carga masiva de solicitudes</a>
                    <a class="collapse-item" href="/credit-requests">Historial de solicitudes</a>
                    <div class="bg-green-side py-2 collapse-inner">
                        <a class="collapse-item" href="/analisis-de-cartera">Análisis de Cartera</a>
                    </div>
                    @can('permission', 'hacer consultas sin visar')
                    <a class="collapse-item" href="{{ url('dataClientDraftwithoutvisa') }}">Consulta sin visar</a>
                    @endcan
                </div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/coupons-form" style="padding-left: 28px;">
                <mercado-icon></mercado-icon>
                <span class="pl-2">Prospección de cartera</span>
            </a>
        </li>

        @can('permission', 'ver visado')
        <li class="nav-item">
            <a class="nav-link collapsed" data-toggle="collapse" data-target="#menuVisados" href="#" aria-expanded="false" aria-controls="menuVisados">
                <visado-icon></visado-icon>
                <span class="pl-2">Visado</span>
            </a>
            <div class="collapse" id="menuVisados" data-parent="#accordionSidebar" aria-labelledby="menuVisados">
                <div class="bg-green-side py-2 collapse-inner">
                    <a class="collapse-item" href="{{ url('dataClientDraft') }}">Nueva consulta ></a>
                    <a class="collapse-item" href="{{ url('historyClient') }}">Listado de consultas ></a>
                </div>
            </div>
        </li>
        @endcan

        @can('permission', 'ver prospeccion mercado')
        <li class="nav-item">
            <a class="nav-link collapsed" data-toggle="collapse" data-target="#menuProspeccion" href="#" aria-expanded="false" aria-controls="menuProspeccion">
                <mercado-icon></mercado-icon>
                <span class="pl-2">Prospección de mercado</span>
            </a>
            <div class="collapse" id="menuProspeccion" data-parent="#accordionSidebar" aria-labelledby="menuProspeccion">
                <div class="bg-green-side py-2 collapse-inner">
                    <a class="collapse-item" href="/parametros-comparativa">Parámetros de control</a>
                    <a class="collapse-item" href="/parametros-comparativa/comparativa">Comparativas</a>
                    <a class="collapse-item" href="/parametros-comparativa/lineal">Serie Temporal</a>
                    <a class="collapse-item" href="/parametros-comparativa/sucursal">Por Sucursal</a>
                    <a class="collapse-item" href="/parametros-comparativa/sueldo-vs-descuentos">Sueldo vs Descuentos</a>
                    <a class="collapse-item" href="/parametros-comparativa/entidad">Por Entidad</a>
                    <a class="collapse-item" href="/parametros-comparativa/embargos">Embargos</a>
                    <a class="collapse-item" href="/parametros-comparativa/multiple">Múltiple</a>
                </div>
            </div>
        </li>
        @endcan

        @can('permission', 'ver reportes')
        <li class="nav-item">
            <a class="nav-link collapsed" data-toggle="collapse" data-target="#menuReportes" href="#" aria-expanded="false" aria-controls="menuReportes">
                <reportes-icon></reportes-icon>
                <span class="pl-2">Reportes</span>
            </a>
            <div class="collapse" id="menuReportes" data-parent="#accordionSidebar" aria-labelledby="menuReportes">
                <div class="bg-green-side py-2 collapse-inner">
                    <a class="collapse-item" href="/reportes/embargos">Embargos</a>
                    <a class="collapse-item" href="/reportes/embargos-monthly">Embargos x Mes</a>
                    <a class="collapse-item" href="/reportes/exp">EXP</a>
                    <a class="collapse-item" href="/reportes/ingresos-vs-descuentos">Ingresos vs Descuentos</a>
                    <a class="collapse-item" href="/reportes/ingresos-vs-egresos">Ingresos vs Egresos</a>
                    <a class="collapse-item" href="/reportes/cuatroxmil">4x1000</a>
                    <a class="collapse-item" href="/reportes/pagadurias">Pagadurías</a>
                    <a class="collapse-item" href="/reportes/embargos-pagadurias">Embargos x Pagadurías</a>
                    <a class="collapse-item" href="/reportes/pagadurias-ingresos-descuentos">Pagadurías Ingresos-Descuentos</a>
                    <a class="collapse-item" href="/reportes/anti-fraude">Anti-fraude</a>
                </div>
            </div>
        </li>
        @endcan

        @can('permission', 'ver join data')
        <li class="nav-item">
            <a class="nav-link collapsed" data-toggle="collapse" data-target="#menuJoinData" href="#" aria-expanded="false" aria-controls="menuJoinData">
                <join-icon></join-icon>
                <span class="pl-2">Join data</span>
            </a>
            <div class="collapse" id="menuJoinData" data-parent="#accordionSidebar" aria-labelledby="menuJoinData">
                <div class="bg-green-side py-2 collapse-inner">
                    <a class="collapse-item" href="/colpensiones">Colpensiones</a>
                    <a class="collapse-item" href="/fiduprevisora">Fiduprevisora</a>
                    <a class="collapse-item" href="/joinpensiones">Localizar cédulas</a>
                </div>
            </div>
        </li>
        @endcan
    </div>

    <div class="borders-space">
        <div class="pl-4 mb-4">
            <span class="sidebar-titles1">HEGO®</span><br>
            <span class="sidebar-titles2">Simulación y solicitud</span>
        </div>

        <li class="nav-item">
            <a class="nav-link" href="{{ url('estudios') }}" style="padding-left: 28px;">
                <span class="pl-2">Simulación HEGO</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ url('solicitud') }}" style="padding-left: 28px;">
                <span class="pl-2">Solicitud de crédito</span>
            </a>
        </li>
    </div>

    @if (IsSuperAdmin())
    <li class="nav-item">
        <a class="nav-link collapsed" data-toggle="collapse" data-target="#menuMaestros" href="#" aria-expanded="false" aria-controls="menuMaestros">
            <span>Maestros</span>
        </a>
        <div class="collapse" id="menuMaestros" data-parent="#accordionSidebar" aria-labelledby="menuMaestros">
            <div class="bg-green-side py-2 collapse-inner">
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
                <a class="collapse-item" href="{{ url('cuentasbancarias') }}">Cuentas Bancarias</a>
                <a class="collapse-item" href="{{ url('entidadesdesembolso') }}">Entidades Desembolso</a>
                <a class="collapse-item" href="{{ url('formapago') }}">Forma Pago</a>
                <a class="collapse-item" href="{{ url('tipogiro') }}">Tipo Giro</a>
                <a class="collapse-item" href="{{ url('motivos') }}">Motivos</a>
                <a class="collapse-item" href="{{ url('iibb') }}">IIBB</a>
                <a class="collapse-item" href="{{ url('cargar-neptuno') }}">Cargar Neptuno</a>
                <a class="collapse-item" href="{{ url('pagadurias-alias') }}">Alias Pagadurías</a>
                <a class="collapse-item" href="{{ url('estados-proceso') }}">Estados Proceso</a>
                <a class="collapse-item" href="{{ url('formas-desembolso') }}">Formas de Desembolso</a>
                <a class="collapse-item" href="{{ url('bancos') }}">Bancos</a>
                <a class="collapse-item" href="{{ url('actividades') }}">Actividades</a>
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
</ul>
