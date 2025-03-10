<!-- Sidebar -->
<ul
    class="navbar-nav bg-black-pearl sidebar sidebar-dark accordion"
    id="accordionSidebar"
    style="height: 100%"
>
    <!-- Sidebar - Brand -->
    <div class="pt-4 pl-3 pb-2 text-left">
        <a
            class=""
            href="{{ url('home') }}"
        >
            <img
                class="img-fluid w-60 mx-auto"
                src="/img/GAFlogosidebar.png"
                alt=""
            >
        </a>
    </div>
    <!-- Nav Items-->
    <li class="nav-item">
        <a
            class="nav-link"
            href="{{ url('home') }}"
            style="padding-left: 28px;"
        >
            <dash-icon></dash-icon>
            <span class="pl-2">Dashboard</span>
        </a>
    </li>
    <div class="borders-space">
        <div class="pl-4 mb-4">
            <span class="sidebar-titles1">AMI®</span></br>
            <span class="sidebar-titles2">Análisis de mercado inteligente</span>
        </div>

        @can('permission', 'ver usuarios')
            <li class="nav-item">
                <a
                    class="nav-link collapsed"
                    data-toggle="collapse"
                    data-target="#menuEmpresas"
                    href="#"
                    aria-expanded="false"
                    aria-controls="menuEmpresas"
                >
                    <users-icon></users-icon>
                    <span class="pl-2">Usuarios</span>
                </a>
                <div
                    class="collapse"
                    id="menuEmpresas"
                    data-parent="#accordionSidebar"
                    aria-labelledby="menuEmpresas"
                >
                    <div class="bg-green-side py-2 collapse-inner">
                        @can('permission', 'ver empresas')
                            <a
                                class="collapse-item"
                                href="/empresas"
                            >
                                Empresas
                            </a>
                        @endcan
                        @can('permission', 'ver area comercial')
                            <a
                                class="collapse-item"
                                href="/area-comerciales"
                            >
                                Area comercial
                            </a>
                        @endcan
                        @can('permission', 'ver sedes')
                            <a
                                class="collapse-item"
                                href="/sedes"
                            >
                                Sedes
                            </a>
                        @endcan
                    </div>
                </div>
            </li>
        @endcan

        @can('permission', 'ver visado')
            <li class="nav-item">
                <a
                    class="nav-link collapsed"
                    data-toggle="collapse"
                    data-target="#menuVisados"
                    href="#"
                    aria-expanded="false"
                    aria-controls="menuVisados"
                >
                    <visado-icon></visado-icon>
                    <span class="pl-2">Visado</span>
                </a>
                <div
                    class="collapse"
                    id="menuVisados"
                    data-parent="#accordionSidebar"
                    aria-labelledby="menuVisados"
                >
                    <div class="bg-green-side py-2 collapse-inner">
                        <a
                            class="collapse-item"
                            href="{{ url('dataClientDraft') }}"
                        >
                            Nueva consulta >
                        </a>
                        <a
                            class="collapse-item"
                            href="{{ url('historyClient') }}"
                        >
                            Listado de consultas >
                        </a>
                    </div>
                </div>
            </li>
        @endcan

        @can('permission', 'ver prospeccion mercado')
            <li class="nav-item">
                <a
                    class="nav-link collapsed"
                    data-toggle="collapse"
                    data-target="#menuProspeccion"
                    href="#"
                    aria-expanded="false"
                    aria-controls="menuProspeccion"
                >
                    <mercado-icon></mercado-icon>
                    <span class="pl-2">Prospección de mercado</span>
                </a>
                <div
                    class="collapse"
                    id="menuProspeccion"
                    data-parent="#accordionSidebar"
                    aria-labelledby="menuProspeccion"
                >
                    <div class="bg-green-side py-2 collapse-inner">
                        <a
                            class="collapse-item"
                            href="/parametros-comparativa"
                        >
                            Parámetros de control
                        </a>
                        <a
                            class="collapse-item"
                            href="/coupons-form"
                        >
                            Prospección de cartera
                        </a>
                        <a
                            class="collapse-item"
                            href="/parametros-comparativa/comparativa"
                        >
                            Comparativas
                        </a>
                    </div>
                </div>
            </li>
        @endcan

        @can('permission', 'ver recuperacion cartera')
            <li class="nav-item">
                <a
                    class="nav-link collapsed"
                    data-toggle="collapse"
                    data-target="#menuRecuperacion"
                    href="#"
                    aria-expanded="false"
                    aria-controls="menuRecuperacion"
                >
                    <cartera-icon></cartera-icon>
                    <span class="pl-2">Recuperación de cartera</span>
                </a>
                <div
                    class="collapse"
                    id="menuRecuperacion"
                    data-parent="#accordionSidebar"
                    aria-labelledby="menuRecuperacion"
                >
                    <div class="bg-green-side py-2 collapse-inner">
                        <a
                            class="collapse-item"
                            href="/analisis-de-cartera"
                        >
                            Análisis de Cartera
                        </a>
                    </div>
                </div>
            </li>
        @endcan

        @can('permission', 'ver investigacion')
            <li class="nav-item">
                <a
                    class="nav-link collapsed"
                    data-toggle="collapse"
                    data-target="#menuInvestigacion"
                    href="#"
                    aria-expanded="false"
                    aria-controls="menuInvestigacion"
                >
                    <investigacion-icon></investigacion-icon>
                    <span class="pl-2">Investigación</span>
                </a>
                <div
                    class="collapse"
                    id="menuInvestigacion"
                    data-parent="#accordionSidebar"
                    aria-labelledby="menuInvestigacion"
                >
                    <div class="bg-green-side py-2 collapse-inner">
                        <a
                            class="collapse-item"
                            href="#"
                        >
                            Investigación de bienes
                        </a>
                        <a
                            class="collapse-item"
                            href="/certificados"
                        >
                            Certificado de nacimiento
                        </a>
                        <a
                            class="collapse-item"
                            href="/old/demografico"
                        >
                            Datos demograficos
                        </a>
                        <a
                            class="collapse-item"
                            href="#"
                        >
                            Datos personales
                        </a>
                        <a
                            class="collapse-item"
                            href="#"
                        >
                            Información financiera
                        </a>
                    </div>
                </div>
            </li>
        @endcan

        @can('permission', 'ver localizacion usuarios')
            <li class="nav-item">
                <a
                    class="nav-link collapsed"
                    data-toggle="collapse"
                    data-target="#menuLocalizacion"
                    href="#"
                    aria-expanded="false"
                    aria-controls="menuLocalizacion"
                >
                    <localizacion-icon></localizacion-icon>
                    <span class="pl-2">Localización de usuarios</span>
                </a>
                <div
                    class="collapse"
                    id="menuLocalizacion"
                    data-parent="#accordionSidebar"
                    aria-labelledby="menuLocalizacion"
                >
                    <div class="bg-green-side py-2 collapse-inner">
                        <a
                            class="collapse-item"
                            href="/colpensiones"
                        >
                            Colpensiones
                        </a>
                        <a
                            class="collapse-item"
                            href="/fiduprevisora"
                        >
                            Fiduprevisora
                        </a>
                        <a
                            class="collapse-item"
                            href="/joinpensiones"
                        >
                            Localizar cédulas
                        </a>
                    </div>
                </div>
            </li>
        @endcan
    </div>

    @if (HEGOAccess())
        <li class="nav-item">
            <a
                class="nav-link collapsed"
                href="/estudios"
                style="display: flex; flex-direction: column; align-items: start; justify-content:center; padding-left: 28px;"
            >
                <span style="font-size: 16px; font-weight: 700; line-height: 20.83px;">
                    HEGO®</br>
                    <span
                        style="font-size: 14px; font-weight: 400; line-height: 18.23px; text-align: left; color: #A1A7AF"
                    >
                        Herramienta ejecutora
                    </span>
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a
                class="nav-link collapsed"
                href="/solicitud"
                style="padding-left: 27px;"
            >
                <img
                    class="img-fluid mr-2"
                    src="/img/solicitudCredito.png"
                    alt=""
                    style="width: 30px; height: auto;"
                >
                <span class="pl-2">Solicitud de crédito</span>
            </a>
        </li>
        {{-- <li class="nav-item">
            <a
                class="nav-link"
                href="{{ url('pagos') }}"
            >
                <ami-icon></ami-icon>
                <span>Pagos</span>
            </a>
        </li> --}}
    @endif

    {{-- @if (IsCompany())
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
                <p
                    class="dropdown-toggle"
                    id="dropdownMenuButton"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                    Visado
                </p>
                <div
                    class="dropdown-menu"
                    aria-labelledby="dropdownMenuButton"
                >
                    <a
                        class="dropdown-item"
                        href="#"
                    >
                        Nueva Consulta
                    </a>
                    <a
                        class="dropdown-item"
                        href="#"
                    >
                        Listado de Consultas
                    </a>
                </div>
            </div>
        </li>
        <li class="nav-item dropdown">
            <div class="col-md-6 dropdown text-white">
                <p
                    class="dropdown-toggle"
                    id="dropdownMenuButton"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                    Prospección de mercado
                </p>
                <div
                    class="dropdown-menu"
                    aria-labelledby="dropdownMenuButton"
                >
                    <a
                        class="dropdown-item"
                        href="#"
                    >
                        Parametros de control
                    </a>
                    <a
                        class="dropdown-item"
                        href="#"
                    >
                        Prospección de cartera
                    </a>
                    <a
                        class="dropdown-item"
                        href="#"
                    >
                        Comparativas
                    </a>
                </div>
            </div>
        </li>
        <li class="nav-item dropdown">
            <div class="col-md-6 dropdown text-white">
                <p
                    class="dropdown-toggle"
                    id="dropdownMenuButton"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                    Recuperación de mercado
                </p>
                <div
                    class="dropdown-menu"
                    aria-labelledby="dropdownMenuButton"
                >
                    <a
                        class="dropdown-item"
                        href="#"
                    >
                        Consulta Gold
                    </a>
                    <a
                        class="dropdown-item"
                        href="#"
                    >
                        Consulta Diamante
                    </a>
                </div>
            </div>
        </li>
        <li class="nav-item dropdown">
            <div class="col-md-6 dropdown text-white">
                <p
                    class="dropdown-toggle"
                    id="dropdownMenuButton"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                    Investigación
                </p>
                <div
                    class="dropdown-menu"
                    aria-labelledby="dropdownMenuButton"
                >
                    <a
                        class="dropdown-item"
                        href="#"
                    >
                        Investigación de bienes
                    </a>
                    <a
                        class="dropdown-item"
                        href="#"
                    >
                        Certificado de nacimiento
                    </a>
                    <a
                        class="dropdown-item"
                        href="#"
                    >
                        Datos demograficos
                    </a>
                    <a
                        class="dropdown-item"
                        href="#"
                    >
                        Datos personales
                    </a>
                    <a
                        class="dropdown-item"
                        href="#"
                    >
                        Información financiera
                    </a>
                </div>
            </div>
        </li>
        <li class="nav-item dropdown">
            <div class="col-md-6 dropdown text-white">
                <p
                    class="dropdown-toggle"
                    id="dropdownMenuButton"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                    Localización de usuarios
                </p>
                <div
                    class="dropdown-menu"
                    aria-labelledby="dropdownMenuButton"
                >
                    <a
                        class="dropdown-item"
                        href="#"
                    >
                        Colpensiones
                    </a>
                    <a
                        class="dropdown-item"
                        href="#"
                    >
                        Fiduprevisora
                    </a>
                    <a
                        class="dropdown-item"
                        href="#"
                    >
                        Localizar cdulas
                    </a>
                </div>
            </div>
        </li>
        <div class="dropdown-divider"></div>
    @endif --}}

    {{-- @if (HEGOAccess())
        <li class="nav-item">
            <a
                class="nav-link"
                href="{{ url('estudios') }}"
            >
                <hego-icon></hego-icon>
                <span>HEGO®</span>
                <img
                    class="img-fluid mx-auto"
                    src="/img/hego-sidebar.png"
                    alt="HEGO"
                />
            </a>
        </li>
        <li class="nav-item">
            <a
                class="nav-link"
                href="/solicitud"
            >
                <ami-icon></ami-icon>
                <span>Solicitud de Credito</span>
            </a>
        </li>
        <li class="nav-item">
            <a
                class="nav-link"
                href="{{ url('integration') }}"
            >
                <hego-icon></hego-icon>
                <span>ID</span>
            </a>
        </li>
    @endif --}}

    {{-- @if (IsSuperAdmin() || IsAMIAdmin() || IsHEGOAdmin() || IsCompany() || IsUserCreator())
        <li class="nav-item">
            <a
                class="nav-link collapsed"
                data-toggle="collapse"
                data-target="#menuAdmon"
                href="#"
                aria-expanded="false"
                aria-controls="menuAdmon"
            >
                <admin-settings-icon></admin-settings-icon>
                <span>Admon</span>
            </a>
            <div
                class="collapse"
                id="menuAdmon"
                data-parent="#accordionSidebar"
                aria-labelledby="menuAdmon"
            >
                <div class="bg-white py-2 collapse-inner">
                    <a
                        class="collapse-item"
                        href="{{ url('usuarios') }}"
                    >
                        Usuarios
                    </a>

                    @if (IsSuperAdmin())
                        <a
                            class="collapse-item"
                            href="{{ url('roles') }}"
                        >
                            Roles
                        </a>
                        <a
                            class="collapse-item"
                            href="{{ url('parametros') }}"
                        >
                            Parámetros
                        </a>
                        <a
                            class="collapse-item"
                            href="{{ url('factorxmillonkredit') }}"
                        >
                            Factores X Millón Kredit
                        </a>
                    @endif
                </div>
            </div>
        </li>
    @endif --}}

    @if (IsSuperAdmin())
        <li class="nav-item">
            <a
                class="nav-link collapsed"
                data-toggle="collapse"
                data-target="#menuMaestros"
                href="#"
                aria-expanded="false"
                aria-controls="menuMaestros"
            >
                <span>Maestros</span>
            </a>
            <div
                class="collapse"
                id="menuMaestros"
                data-parent="#accordionSidebar"
                aria-labelledby="menuMaestros"
            >
                <div class="bg-green-side py-2 collapse-inner">
                    <a
                        class="collapse-item"
                        href="{{ url('departamentos') }}"
                    >
                        Departamentos
                    </a>
                    <a
                        class="collapse-item"
                        href="{{ url('ciudades') }}"
                    >
                        Ciudades
                    </a>
                    <a
                        class="collapse-item"
                        href="{{ url('cargos') }}"
                    >
                        Cargos
                    </a>
                    <a
                        class="collapse-item"
                        href="{{ url('sectores') }}"
                    >
                        Sectores
                    </a>
                    <a
                        class="collapse-item"
                        href="{{ url('estadoscartera') }}"
                    >
                        Estados Cartera
                    </a>
                    <a
                        class="collapse-item"
                        href="{{ url('tiposembargo') }}"
                    >
                        Tipos de Embargo
                    </a>
                    <a
                        class="collapse-item"
                        href="{{ url('pagadurias') }}"
                    >
                        Pagadurías
                    </a>
                    <a
                        class="collapse-item"
                        href="{{ url('aliados') }}"
                    >
                        Aliados
                    </a>
                    <a
                        class="collapse-item"
                        href="{{ url('entidades') }}"
                    >
                        Entidades
                    </a>
                    <a
                        class="collapse-item"
                        href="{{ url('factores') }}"
                    >
                        Factores
                    </a>
                    <a
                        class="collapse-item"
                        href="{{ url('cuentasbancarias') }}"
                    >
                        Cuentas Bancarias
                    </a>
                    <a
                        class="collapse-item"
                        href="{{ url('entidadesdesembolso') }}"
                    >
                        Entidades Desembolso
                    </a>
                    <a
                        class="collapse-item"
                        href="{{ url('formapago') }}"
                    >
                        Forma Pago
                    </a>
                    <a
                        class="collapse-item"
                        href="{{ url('tipogiro') }}"
                    >
                        Tipo Giro
                    </a>
                </div>
            </div>
        </li>
        <li class="nav-item">
            <a
                class="nav-link"
                href="{{ url('planos') }}"
            >
                <span>Carga de Archivos Manual</span>
            </a>
        </li>
        <li class="nav-item">
            <a
                class="nav-link"
                href="{{ url('planos/crear_gcp') }}"
            >
                <span>Carga de Archivos Inteligente</span>
            </a>
        </li>
    @endif

    <!-- Sidebar Toggler (Sidebar) -->
    {{-- <div class="text-center d-none d-md-inline pt-4">
        <button
            class="rounded-circle border-0"
            id="sidebarToggle"
        ></button>
    </div> --}}
</ul>
<!-- End of Sidebar -->
