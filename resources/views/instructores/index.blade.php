@extends('layouts.plantilla')

{{-- Estilos --}}
<link rel="stylesheet" href="{{ asset('css/users.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltip.css') }}">

@section('content')
    {{-- Navbar --}}
    <div class="container my-3">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="navbutton button nav-link fs-5 {{ request()->routeIs('personas.index') ? 'active' : '' }}"
                    aria-current="page" href="{{ route('personas.index') }}"><i class="fas fa-users me-2"></i>Personas</a>
            </li>
            <li class="nav-item">
                <a class="navbutton button nav-link fs-5 {{ request()->routeIs('instructores.index') ? 'active' : '' }}"
                    aria-current="page" href="{{ route('instructores.index') }}"><i
                        class="fas fa-network-wired me-2"></i>Instructores</a>
            </li>
        </ul>
    </div>
    {{-- End navbar --}}

    {{-- Users card --}}
    <div class="container d-flex flex-row-reverse">
        <div class="col-md-6 col-sm-8 col-lg-4 px-5 py-4">
            <div
                class="bg-white  d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalinstructores }}</h3>
                    <p class="fs-5">{{ $totalinstructores ==1 ? 'Instructor' : 'Instructores' }}</p>
                </div>
                <i class="fas fa-users fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </div>
        <div class="col-md-6 col-sm-8 col-lg-4 px-5 py-4">
            <div
                class="bg-white  d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalinstructoresDeshabilitados }}</h3>
                    <p class="fs-5">{{ $totalinstructoresDeshabilitados == 1? 'Deshabilitado' : 'Deshabilitados' }}</p>
                </div>
                <i class="fas fa-solid fa-xmark fs-1 third-text border rounded-full third-bg p-3"></i>
            </div>
        </div>
        <div class="col-md-6 col-sm-8 col-lg-4 px-5 py-4">
            <div
                class="bg-white  d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalinstructoresHabilitados }}</h3>
                    <p class="fs-5">{{ $totalinstructoresHabilitados == 1? 'Habilitado' : 'Habilitados' }}</p>
                </div>
                <i class="fas fa-solid fa-check fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </div>
    </div>
    {{-- End Users card --}}

    <div class="container">

        <h1 class="mb-3 text-center py-3">INSTRUCTORES</h1>

        @role('superadmin|administrador')
            <div class="container d-flex flex-row-reverse">
                <a href="{{ route('instructores.create') }}" type="button" class="btn btn-large my-3 mx-2 boton"><i
                        class="fas fa-plus-circle me-2"></i>Agregar Instructor</a>
            </div>
        @endrole

        <div class="col container">
            <table class="table" id="instructores">
                <thead>
                    <tr>
                        <th scope="col" width="50">#</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellidos</th>
                        <th scope="col">Tipo Doc</th>
                        <th scope="col">Documento</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Tipo Contrato</th>
                        <th scope="col">Estado</th>
                        <th scope="col" width="170" class="acciones-column">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    @include('instructores.jsTable')
@endsection
