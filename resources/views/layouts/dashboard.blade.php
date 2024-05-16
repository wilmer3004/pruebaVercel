{{-- side bar --}}
{{-- Estilos --}}
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@include('sweetalert::alert')
<div class="d-flex" id="wrapper">

    {{-- sidebar starts here --}}

    <div class="bar" id="sidebar-wrapper">

        <div class="sidebar-heading text-center primary-text fs-3 fw-bold text-uppercase">
            <a href="{{ route('index') }}"><img class="img" src="{{ asset('img/logosena_verde.png') }}"
                    alt=""></a>
            Horarios
        </div>

        <div class="list-group list-group-flush my-3">

            {{-- USUARIOS --}}
            @role('superadmin|administrador|programador')
                <a href="{{ route('personas.index') }}"
                    class="list-group-item list-group-item-action bg-transparent second-text fw-bold border-top
                {{ request()->routeIs('personas.index', 'instructores.index') ? 'activo' : '' }}">
                    <i class="fas fa-users me-2"></i> PERSONAS
                </a>
            @endrole

            {{-- PROGRAMAS --}}
            @role('superadmin|administrador|programador')
            <a href="{{ route('programas.index') }}"
                class="list-group-item list-group-item-action bg-transparent second-text fw-bold border-top
                {{ request()->routeIs('programas.index', 'componentes.index') ? 'activo' : '' }}">
                <i class="fas fa-book me-2"></i> PROGRAMAS
            </a>
            @endrole

            {{-- AMBIENTES --}}
            @role('superadmin|administrador|programador')
            <a href="{{ route('sedes.index') }}"
                class="list-group-item list-group-item-action bg-transparent second-text fw-bold border-top
                {{ request()->routeIs('sedes.index', 'ambientes.index') ? 'activo' : '' }}">
                <i class="fas fa-school me-2"></i> AMBIENTES
            </a>
            @endrole

            {{-- FICHAS --}}
            @role('superadmin|administrador|programador')
            <a href="{{ route('fichas.index') }}"
                class="list-group-item list-group-item-action bg-transparent second-text fw-bold border-top
                {{ request()->routeIs('fichas.index') ? 'activo' : '' }}">
                <i class="fas fa-graduation-cap me-2"></i> FICHAS
            </a>
            @endrole

            {{-- HORARIOS --}}
            @role('superadmin|administrador|programador')
            <div class="align-bottom border-top">
                <a class="btn list-group-item list-group-item-action bg-transparent second-text fw-bold
                    {{ request()->routeIs('horarios.create', 'horarios.index') ? 'activo' : '' }}"
                    id="dropdownButton1">
                    <i class="fas fa-calendar-alt me-2"></i> HORARIOS
                </a>

                <a href="{{ route('horarioInformation.datatable') }}"
                    class="list-group-item item1 list-group-item-action bg-transparent second-text fw-bold py-1 mx-4 fs-6
                    {{ request()->routeIs('horarioInformation.datatable') ? 'activo' : '' }}">
                    <i class="fa-regular fa-calendar-days me-2"></i>                    Eventos
                </a>


                <a href="{{ route('horarios.create') }}"
                    class="list-group-item item1 list-group-item-action bg-transparent second-text fw-bold py-1 mx-4 fs-6
                    {{ request()->routeIs('horarios.create') ? 'activo' : '' }}">
                    <i class="fas fa-calendar-plus me-2"></i> Programación
                </a>

                <a href="{{ route('horarios.index') }}"
                    class="list-group-item item1 list-group-item-action bg-transparent second-text fw-bold py-1 mx-4 fs-6
                    {{ request()->routeIs('horarios.index') ? 'activo' : '' }}">
                    <i class="fas fa-calendar-check me-2"></i> Horario Programado
                </a>
            </div>
            @endrole

            {{-- GRAFICAS --}}
            @role('superadmin|administrador|programador')
            <a href="{{ route('graficas.index') }}"
                class="list-group-item list-group-item-action bg-transparent second-text fw-bold border-top
                {{ request()->routeIs('graficas.index') ? 'activo' : '' }}">
                <i class="fa-solid fa-chart-simple me-2"></i> GRAFICAS
            </a>
            @endrole

            @role('superadmin')
                {{-- HERRAMIENTAS --}}
                <div class="align-bottom border-top">

                    <a class="btn list-group-item list-group-item-action bg-transparent second-text fw-bold
                 {{ request()->routeIs(
                     'roles.index',
                     'contratos.index',
                     'jornadas.index',
                     'condiciones.index',
                     'ofertas.index',
                     'tipos.index',
                     'tiposprograma.index',
                     'trimestres.index',
                     'coordinaciones.index',
                 )
                     ? 'activo'
                     : '' }}"
                        id="dropdownButton">
                        <i class="fas fa-tools me-2"></i> HERRAMIENTAS
                    </a>

                    <a href="{{ route('roles.index') }}"
                        class="list-group-item item list-group-item-action bg-transparent second-text fw-bold py-1 mx-4 fs-6
                    {{ request()->routeIs('roles.index') ? 'activo' : '' }}">
                        <i class="fas fa-user-tag me-2"></i> Roles
                    </a>

                    <a href="{{ route('bloques.index') }}"
                        class="list-group-item item list-group-item-action bg-transparent second-text fw-bold py-1 mx-4 fs-6
                    {{ request()->routeIs('bloques.index') ? 'activo' : '' }}">
                        <i class="fas fa-cubes me-2"></i> Bloques
                    </a>

                    <a href="{{ route('contratos.index') }}"
                        class="list-group-item item list-group-item-action bg-transparent second-text fw-bold py-1 mx-4 fs-6
                    {{ request()->routeIs('contratos.index') ? 'activo' : '' }}">
                        <i class="fas fa-book-reader me-2"></i> Tipos de Contrato
                    </a>

                    <a href="{{ route('jornadas.index') }}"
                        class="list-group-item item list-group-item-action bg-transparent second-text fw-bold py-1 mx-4 fs-6
                    {{ request()->routeIs('jornadas.index') ? 'activo' : '' }}">
                        <i class="fas fa-stopwatch me-2"></i> Jornadas
                    </a>

                    <a href="{{route('festivos.index')}}"
                       class="list-group-item item list-group-item-action bg-transparent second-text fw-bold py-1 mx-4 fs-6
                       {{request()->routeIs('festivos.index') ? 'activo' : ''}}" >
                        <i class="fa-regular fa-calendar-xmark me-2"></i> Festivos
                    </a>

                    <a href="{{ route('condiciones.index') }}"
                        class="list-group-item item list-group-item-action bg-transparent second-text fw-bold py-1 mx-4 fs-6
                    {{ request()->routeIs('condiciones.index') ? 'activo' : '' }}">
                        <i class="fas fa-check-square me-2"></i> Condiciones
                    </a>

                    <a href="{{ route('ofertas.index') }}"
                        class="list-group-item item list-group-item-action bg-transparent second-text fw-bold py-1 mx-4 fs-6
                    {{ request()->routeIs('ofertas.index') ? 'activo' : '' }}">
                        <i class="fas fa-file-signature me-2"></i> Ofertas
                    </a>

                    <a href="{{ route('coordinaciones.index') }}"
                        class="list-group-item item list-group-item-action bg-transparent second-text fw-bold py-1 mx-4 fs-6
                    {{ request()->routeIs('coordinaciones.index') ? 'activo' : '' }}">
                        <i class="fas fa-school me-2"></i> Coordinaciones
                    </a>

                    <a href="{{ route('tipos.index') }}"
                        class="list-group-item item list-group-item-action bg-transparent second-text fw-bold py-1 mx-4 fs-6
                    {{ request()->routeIs('tipos.index') ? 'activo' : '' }}">
                        <i class="fas fa-microchip me-2"></i> Tipo de Componente
                    </a>

                    <a href="{{ route('tiposprograma.index') }}"
                        class="list-group-item item list-group-item-action bg-transparent second-text fw-bold py-1 mx-4 fs-6
                    {{ request()->routeIs('tiposprograma.index') ? 'activo' : '' }}">
                        <i class="fas fa-pencil-ruler me-2"></i> Tipo de Programa
                    </a>

                    <a href="{{ route('trimestres.index') }}"
                        class="list-group-item item list-group-item-action bg-transparent second-text fw-bold py-1 mx-4 fs-6
                    {{ request()->routeIs('trimestres.index') ? 'activo' : '' }}">
                        <i class="fas fa-scroll me-2"></i> Trimestres
                    </a>

                    <a href="{{ route('fechasanio.index') }}"
                        class="list-group-item item list-group-item-action bg-transparent second-text fw-bold py-1 mx-4 fs-6
                    {{ request()->routeIs('fechasanio.index') ? 'activo' : '' }}">
                        <i class="fas fa-calendar-alt me-2"></i> Trimestres del Año
                    </a>

                    <a href="{{ route('condicioneshoras.index') }}"
                        class="list-group-item item list-group-item-action bg-transparent second-text fw-bold py-1 mx-4 fs-6
                    {{ request()->routeIs('condicioneshoras.index') ? 'activo' : '' }}">
                        <i class="fas fa-business-time me-2"></i> Condiciones Horas
                    </a>
                </div>
            @endrole

        </div>

    </div>

    {{-- sidebar ends here --}}

    {{-- nav bar --}}

    <div id="page-content-wrapper">

        <nav class="navbar navbar-expand-lg navbar-ligth bg-trasnparent py-4 px-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-align-left fs-4 me-3 text-white" id="menu-toggle"></i>
            </div>

            <div class="container">
                <h2 class="fs-2 text-white position-absolute top-0 start-50 translate-middle-x py-4">
                    PROGRAMACIÓN DE FICHAS

                </h2>
            </div>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle link fw-bold" id="navbarDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user me-2"></i>
                            @auth
                                {{ Auth::user()->persona->name }}
                                ({{ Auth::user()->roles->pluck('name')->implode(', ') }})
                            @endauth
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <a href="{{ route('logout') }}" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i>
                                    Cerrar Sesión
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>


        </nav>

        @yield('content')

    </div>

</div>

<script src="{{ asset('js/dashboard.js') }}"></script>
