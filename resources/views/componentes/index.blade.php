@extends('layouts.plantilla')

{{-- Estilos --}}
<link rel="stylesheet" href="{{ asset('css/componentes.css') }}">
<link rel="stylesheet" href="{{ asset('css/validacion.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltip.css') }}">

@section('content')
    @include('componentes.create')
    @include('componentes.edit')
    {{-- Navbar --}}
    <div class="container my-3">
        <ul class="nav nav-tabs">

            <li class="nav-item">
                <a class="navbutton button nav-link fs-5 {{ request()->routeIs('programas.index') ? 'active' : '' }}"
                    aria-current="page" href="{{ route('programas.index') }}"><i
                        class="fas fa-book me-2 active"></i>Programas</a>
            </li>

            <li class="nav-item">
                <a class="navbutton button nav-link fs-5 {{ request()->routeIs('componentes.index') ? 'active' : '' }}"
                    aria-current="page" href="{{ route('componentes.index') }}"><i
                        class="fas fa-book-open me-2 active"></i>Componentes</a>
            </li>

        </ul>
    </div>

    {{-- Componentes card --}}
    <div class="container d-flex flex-row-reverse">
        <div class="col-md-6 col-sm-6 col-lg-4 px-5 py-2">
            <div
                class="bg-white d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalcomponentes }}</h3>
                    <p class="fs-5">Componentes</p>
                </div>
                <i class="fas fa-book-open fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </div>
    </div>
    {{-- End Componentes card --}}

    <div class="container">

        <h1 class="mb-3 text-center text">COMPONENTES</h1>

        @role('superadmin|administrador')
        <div class="container d-flex flex-row-reverse">
            <a href="" type="button" class="btn btn-large  my-3 mx-2 boton" data-bs-toggle="modal"
                data-bs-target="#CreateComponenteModal"><i class="fas fa-plus-circle me-2"></i>Agregar Componente</a>
        </div>
        @endrole

        <div class="col container">
            <table class="table" id="componentes">
                <thead>
                    <tr>
                        <th scope="col" width="80">#</th>
                        <th scope="col" width="">Componente</th>
                        <th scope="col" width="">Tipo</th>
                        <th scope="col" width="">Trimestre</th>
                        <th scope="col" width="">Total Horas</th>
                        <th scope="col" width="">Descripción</th>
                        <th scope="col" width="">Estado</th>
                        <th scope="col" width="120">Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('componentes.js')
@endsection
