<!-- Sidebar -->
<ul class="navbar-nav bg-black-pearl sidebar sidebar-dark accordion" id="accordionSidebar" style="height: 100%">
    <!-- Sidebar - Brand -->
    <div class="pt-4 pl-3 pb-2 text-left">
        <a class="" href="{{ url('home') }}">
            <img src="/img/GAFlogosidebar.png" alt="" class="img-fluid w-60 mx-auto">
        </a>
    </div>
    <!-- Nav Items-->
    <li class="nav-item">
        <a class="nav-link" href="{{ url('home') }}">
            <dash-icon></dash-icon>
            <span class="pl-2">Dashboard</span>
        </a>
    </li>
    <div class="borders-space">
        @if(IsCompany())
        <div class="pl-2 mb-4">
            <span class="sidebar-titles1">AMI®</span></br>
            <span class="sidebar-titles2">Análisis de mercado inteligente</span>
        </div>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuVisados" aria-expanded="false" aria-controls="menuVisados">
                <visado-icon></visado-icon>    
                <span class="pl-2">Visado</span>
            </a>
            <div id="menuVisados" class="collapse" aria-labelledby="menuVisados" data-parent="#accordionSidebar">
                <div class="bg-green-side py-2 collapse-inner">
                    <a class="collapse-item" href="{{ url('dataClientDraft') }}">Nueva consulta ></a>
                    <a class="collapse-item" href="{{ url('historyClient' )}}">Listado de consultas ></a>
                </div>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuProspeccion" aria-expanded="false" aria-controls="menuProspeccion">
                <mercado-icon></mercado-icon>    
                <span class="pl-2">Prospección de mercado</span>
            </a>
            <div id="menuProspeccion" class="collapse" aria-labelledby="menuProspeccion" data-parent="#accordionSidebar">
                <div class="bg-green-side py-2 collapse-inner">
                    <a class="collapse-item" href="/parametros-comparativa">Parámetros de control</a>
                    <a class="collapse-item" href="/coupons-form">Prospección de cartera</a>
                    <a class="collapse-item" href="/parametros-comparativa/comparativa">Comparativas</a>
                </div>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuRecuperacion" aria-expanded="false" aria-controls="menuRecuperacion">
                <cartera-icon></cartera-icon>    
                <span class="pl-2">Recuperación de cartera</span>
            </a>
            <div id="menuRecuperacion" class="collapse" aria-labelledby="menuRecuperacion" data-parent="#accordionSidebar">
                <div class="bg-green-side py-2 collapse-inner">
                    <a class="collapse-item" href="#">Consulta Gold</a>
                    <a class="collapse-item" href="/coupons-form">Consulta Diamante</a>
                </div>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuInvestigacion" aria-expanded="false" aria-controls="menuInvestigacion">
                <investigacion-icon></investigacion-icon>    
                <span class="pl-2">Investigación</span>
            </a>
            <div id="menuInvestigacion" class="collapse" aria-labelledby="menuInvestigacion" data-parent="#accordionSidebar">
                <div class="bg-green-side py-2 collapse-inner">
                    <a class="collapse-item" href="#">Investigación de bienes</a>
                    <a class="collapse-item" href="/certificados">Certificado de nacimiento</a>
                    <a class="collapse-item" href="/demografico">Datos demograficos</a>
                    <a class="collapse-item" href="#">Datos personales</a>
                    <a class="collapse-item" href="#">Información financiera</a>
                </div>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuLocalizacion" aria-expanded="false" aria-controls="menuLocalizacion">
                <localizacion-icon></localizacion-icon>    
                <span class="pl-2">Localización de usuarios</span>
            </a>
            <div id="menuLocalizacion" class="collapse" aria-labelledby="menuLocalizacion" data-parent="#accordionSidebar">
                <div class="bg-green-side py-2 collapse-inner">
                    <a class="collapse-item" href="/colpensiones">Colpensiones</a>
                    <a class="collapse-item" href="/fiduprevisora">Fiduprevisora</a>
                    <a class="collapse-item" href="/joinpensiones">Localizar cédulas</a>
                </div>
            </div>
        </li>
    </div>
    <div class="pl-3 mt-4">
        <span class="sidebar-titles1">HEGO®</span></br>
        <span class="sidebar-titles2">Herramienta ejecutora</span>
    </div>
    
    <!-- <li class="nav-item">
        <a class="nav-link" href="{{ url('pagos') }}">
            <ami-icon></ami-icon>
            <span>Pagos</span>
        </a>
    </li> -->
    @endif

    <!-- @if(IsCompany())
        <li class="nav-item dropdown">
            <div>
                <button class="btn btn-success btn-block">Dashboard</button>
            </div>
            <div class="dropdown-divider"></div>
            <div>
                <h3 class="text-white">AMI</h3>
                <p style="color:gray;">Análisis de mercado inteligente</p>
            </div>
        </li>
        <li class="nav-item dropdown">
            <div class="col-md-6 dropdown text-white">
                <p class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Visado
                </p>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#">Nueva Consulta</a>
                    <a class="dropdown-item" href="#">Listado de Consultas</a>
                </div>
            </div>
        </li>
        <li class="nav-item dropdown">
            <div class="col-md-6 dropdown text-white">
                <p class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Prospección de mercado
                </p>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#">Parametros de control</a>
                    <a class="dropdown-item" href="#">Prospección de cartera</a>
                    <a class="dropdown-item" href="#">Comparativas</a>
                </div>
            </div>
        </li>
        <li class="nav-item dropdown">
            <div class="col-md-6 dropdown text-white">
                <p class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Recuperación de mercado
                </p>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#">Consulta Gold</a>
                    <a class="dropdown-item" href="#">Consulta Diamante</a>
                </div>
            </div>
        </li>
        <li class="nav-item dropdown">
            <div class="col-md-6 dropdown text-white">
                <p class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Investigación
                </p>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#">Investigación de bienes</a>
                    <a class="dropdown-item" href="#">Certificado de nacimiento</a>
                    <a class="dropdown-item" href="#">Datos demograficos</a>
                    <a class="dropdown-item" href="#">Datos personales</a>
                    <a class="dropdown-item" href="#">Información financiera</a>
                </div>
            </div>
        </li>
        <li class="nav-item dropdown">
            <div class="col-md-6 dropdown text-white">
                <p class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Localización de usuarios
                </p>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#">Colpensiones</a>
                    <a class="dropdown-item" href="#">Fiduprevisora</a>
                    <a class="dropdown-item" href="#">Localizar cdulas</a>
                </div>
            </div>
        </li>
        <div class="dropdown-divider"></div>
    @endif -->

    @if (HEGOAccess())
    <!-- <li class="nav-item">
        <a class="nav-link" href="{{ url('estudios') }}">
            <hego-icon></hego-icon>
            <span>HEGO®</span>
            <img src="/img/hego-sidebar.png" alt="HEGO" class="img-fluid mx-auto"/>
        </a>
    </li> -->

    <!-- <li class="nav-item">
        <a class="nav-link" href="/solicitud">
            <ami-icon></ami-icon>
            <span>Solicitud de Credito</span>
        </a>
    </li> -->

    <!-- <li class="nav-item">
        <a class="nav-link" href="{{ url('integration') }}">
            <hego-icon></hego-icon>
            <span>ID</span>
        </a>
    </li> -->
    @endif

    @if (IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin() || IsCompany() || IsUserCreator())
    <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuAdmon" aria-expanded="false" aria-controls="menuAdmon">
            <admin-settings-icon></admin-settings-icon>
            <span>Admon</span>
        </a>
        <div id="menuAdmon" class="collapse" aria-labelledby="menuAdmon" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner">
                <a class="collapse-item" href="{{ url('usuarios') }}">Usuarios</a>
                @if (IsSuperAdmin())
                <a class="collapse-item" href="{{ url('roles') }}">Roles</a>
                <a class="collapse-item" href="{{ url('parametros') }}">Parámetros</a>
                <a class="collapse-item" href="{{ url('factorxmillonkredit') }}">Factores X Millón Kredit</a>
                @endif
            </div>
        </div>
    </li> -->
    @endif

    @if (IsSuperAdmin())
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menuMaestros" aria-expanded="false" aria-controls="menuMaestros">
            <span>Maestros</span>
        </a>
        <div id="menuMaestros" class="collapse" aria-labelledby="menuMaestros" data-parent="#accordionSidebar">
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
                <a class="collapse-item" href="{{url('cuentasbancarias')}}">Cuentas Bancarias</a>
                <a class="collapse-item" href="{{url('entidadesdesembolso')}}">Entidades Desembolso</a>
                <a class="collapse-item" href="{{url('formapago')}}">Forma Pago</a>
                <a class="collapse-item" href="{{url('tipogiro')}}">Tipo Giro</a>
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
    <div class="text-center d-none d-md-inline pt-4">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->