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
                        <a class="collapse-item" href="/analisis-de-cartera-avanzado">Análisis de Cartera Avanzado</a>
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
                </div>
            </div>
        </li>
        @endcan

        @can('permission', 'ver recuperacion cartera')
        <li class="nav-item">
            <a class="nav-link collapsed" data-toggle="collapse" data-target="#menuRecuperacion" href="#" aria-expanded="false" aria-controls="menuRecuperacion">
                <cartera-icon></cartera-icon>
                <span class="pl-2">Recuperación de cartera</span>
            </a>
            <div class="collapse" id="menuRecuperacion" data-parent="#accordionSidebar" aria-labelledby="menuRecuperacion">
                <div class="bg-green-side py-2 collapse-inner">
                    <a class="collapse-item d-flex align-items-center" href="/analisis-de-cartera">
                        <analisis-cartera-icon></analisis-cartera-icon>
                        <span class="pl-2">Análisis de Cartera</span>
                    </a>
                    <a class="collapse-item d-flex align-items-center" href="/analisis-de-cartera-avanzado">
                        <analisis-avanzado-icon></analisis-avanzado-icon>
                        <span class="pl-2">Análisis de Cartera Avanzado</span>
                    </a>
                    <a class="collapse-item d-flex align-items-center" href="/politicas-portafolio">
                        <settings-financial-icon></settings-financial-icon>
                        <span class="pl-2">Políticas</span>
                    </a>
                    <a class="collapse-item d-flex align-items-center" href="/historial-cartera">
                        <historial-icon></historial-icon>
                        <span class="pl-2">Historial</span>
                    </a>
                </div>
            </div>
        </li>
        @endcan

        @can('permission', 'ver investigacion')
        <li class="nav-item">
            <a class="nav-link collapsed" data-toggle="collapse" data-target="#menuInvestigacion" href="#" aria-expanded="false" aria-controls="menuInvestigacion">
                <investigacion-icon></investigacion-icon>
                <span class="pl-2">Investigación</span>
            </a>
            <div class="collapse" id="menuInvestigacion" data-parent="#accordionSidebar" aria-labelledby="menuInvestigacion">
                <div class="bg-green-side py-2 collapse-inner">
                    <a class="collapse-item" href="#">Investigación de bienes</a>
                    <a class="collapse-item" href="/certificados">Certificado de nacimiento</a>
                    <a class="collapse-item" href="/demografico">Datos demograficos</a>
                    <a class="collapse-item" href="#">Datos personales</a>
                    <a class="collapse-item" href="#">Información financiera</a>
                </div>
            </div>
        </li>
        @endcan

        @can('permission', 'ver localizacion usuarios')
        <li class="nav-item">
            <a class="nav-link collapsed" data-toggle="collapse" data-target="#menuLocalizacion" href="#" aria-expanded="false" aria-controls="menuLocalizacion">
                <localizacion-icon></localizacion-icon>
                <span class="pl-2">Localización de usuarios</span>
            </a>
            <div class="collapse" id="menuLocalizacion" data-parent="#accordionSidebar" aria-labelledby="menuLocalizacion">
                <div class="bg-green-side py-2 collapse-inner">
                    <a class="collapse-item" href="/colpensiones">Colpensiones</a>
                    <a class="collapse-item" href="/fiduprevisora">Fiduprevisora</a>
                    <a class="collapse-item" href="/joinpensiones">Localizar cédulas</a>
                </div>
            </div>
        </li>
        @endcan
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
