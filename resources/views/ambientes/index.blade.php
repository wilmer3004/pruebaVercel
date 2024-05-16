@extends('layouts.plantilla')

{{-- Estilos --}}
<link rel="stylesheet" href="{{ asset('css/programas.css') }}">
<link rel="stylesheet" href="{{ asset('css/validacion.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltip.css') }}">


@section('content')
    @include('ambientes.create')
    {{-- Navbar --}}
    <div class="container my-3">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="navbutton button nav-link fs-5 {{ request()->routeIs('sedes.index') ? 'active' : '' }}"
                    aria-current="page" href="{{ route('sedes.index') }}"><i
                        class="fas fa-building me-2 active"></i>Sedes</a>
            </li>
            <li class="nav-item">
                <a class="navbutton button nav-link fs-5 {{ request()->routeIs('ambientes.index') ? 'active' : '' }}"
                    aria-current="page" href="{{ route('ambientes.index') }}"><i
                        class="fas fa-school me-2 active"></i>Ambientes</a>
            </li>
        </ul>
    </div>

    {{-- Ambientes card --}}
    <div class="container d-flex flex-row-reverse ">
        <div class="col-md-6 col-sm-5 col-lg-4 px-5 py-2">
            <div
                class="p-3 bg-white d-flex justify-content-around align-items-center around shadow-lg mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalambientes }}</h3>
                    <p class="fs-5">Ambientes</p>
                </div>
                <i class="fas fa-school fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </div>
    </div>
    {{-- End Ambientes card --}}

    <div class="container">

        <h1 class="mb-3 text-center text">AMBIENTES</h1>

        @role('superadmin|administrador')
        <div class="container d-flex flex-row-reverse">
            <a href="" type="button" class="btn btn-large  my-3 mx-2 boton" data-bs-toggle="modal"
                data-bs-target="#CreateAmbienteModal"><i class="fas fa-plus-circle me-2"></i>Agregar Ambiente</a>
        </div>
        @endrole

        <div class="col container">
            <table class="table" id="ambientes">
                <thead>
                    <tr>
                        <th scope="col" width="80">#</th>
                        <th scope="col" width="250">Ambiente</th>
                        <th scope="col" width="250">Sede</th>
                        <th scope="col" width="250">Piso</th>
                        <th scope="col" width="250">Capacidad</th>
                        <th scope="col" width="250">Componente</th>
                        <th scope="col" width="250">Estado</th>
                        <th scope="col" width="150">Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('ambientes.edit')
    @include('ambientes.js')
@endsection
