@extends('layouts.plantilla')

{{-- Estilos --}}
<link rel="stylesheet" href="{{ asset('css/sedes.css') }}">
<link rel="stylesheet" href="{{ asset('css/validacion.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltip.css') }}">

@section('content')
@include('sedes.create')
@include('sedes.edit')
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

    {{-- Sede card --}}
    <div class="container d-flex flex-row-reverse">
        <div class="col-md-6 col-sm-8 col-lg-4 px-5 py-4">
            <div
                class="bg-white d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalsedes }}</h3>
                    <p class="fs-5">Sedes</p>
                </div>
                <i class="fas fa-building fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </div>
    </div>
    {{-- End Sede card --}}

    {{-- Content --}}
    <div class="container">

        <h1 class="mb-3 text-center">SEDES</h1>
        @role('superadmin|administrador')
        <div class="container d-flex flex-row-reverse">
            <a href="" type="button" class="btn btn-large my-3 mx-2 boton" data-bs-toggle="modal"
                data-bs-target="#CreateSedeModal"><i class="fas fa-plus-circle me-2"></i>Agregar Sede</a>
        </div>
        @endrole

        <div class="col container">
            <table class="table" id="sedes">
                <thead>
                    <tr>
                        <th scope="col" width="">#</th>
                        <th scope="col" width="">Nombre</th>
                        <th scope="col" width="">Dirección</th>
                        <th scope="col" width="">Num. Max Ambientes</th>
                        <th scope="col" width="">Num. Max Pisos</th>
                        <th scope="col" width="">Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('sedes.js')
    @endsection
