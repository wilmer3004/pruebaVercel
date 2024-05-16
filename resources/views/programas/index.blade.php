@extends('layouts.plantilla')

{{-- Estilos --}}
<link rel="stylesheet" href="{{ asset('css/programas.css') }}">
<link rel="stylesheet" href="{{ asset('css/validacion.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltip.css') }}">

@section('content')
    @include('programas.create')
    @include('programas.edit')
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

    {{-- Programas card --}}
    <div class="container d-flex flex-row-reverse">
        <div class="col-md-6 col-sm-6 col-lg-4 px-5 py-2">
            <div
                class=" bg-white d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalprogramas }}</h3>
                    <p class="fs-5">Programas</p>
                </div>
                <i class="fas fa-book fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </div>
    </div>
    {{-- End Programas card --}}

    <div class="container">

        <h1 class="mb-3 text-center text">PROGRAMAS</h1>

        @role('superadmin|administrador')
        <div class="container d-flex flex-row-reverse">
            <a href="" type="button" class="btn btn-large my-3 mx-2 boton" data-bs-toggle="modal"
                data-bs-target="#CreateProgramaModal"><i class="fas fa-plus-circle me-2"></i>Agregar Programa</a>
        </div>
        @endrole

        <div class="col container">
            <table class="table" id="programas">
                <thead>
                    <tr>
                        <th scope="col" width="45">#</th>
                        <th scope="col" width="">Nombre</th>
                        <th scope="col" width="">Coordinación</th>
                        <th scope="col" width="">Tipo de Programa</th>
                        <th scope="col" width="">Duración</th>
                        <th scope="col" width="80">Estado</th>
                        <th scope="col" width="80">Colores</th>
                        <th scope="col" width="150">Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('programas.js')
@endsection
