@extends('layouts.plantilla')

{{-- Estilos --}}
<link rel="stylesheet" href="{{ asset('css/roles.css') }}">
<link rel="stylesheet" href="{{ asset('css/coordinaciones.css') }}">
<link rel="stylesheet" href="{{ asset('css/validacion.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltip.css') }}">

@section('content')
@include('coordinaciones.create')
@include('coordinaciones.edit')
    {{-- Coordinacion card --}}
    <div class="container d-flex flex-row-reverse mt-4">
        <div class="col-md-6 col-sm-6 col-lg-4 px-5 py-2">
            <div
                class="bg-white d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalcoordinaciones }}</h3>
                    <p class="fs-5">Total</p>
                </div>
                <i class="fas fa-school fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-4 px-5 py-2">
            <div
                class="bg-white d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalcoordinacionesD }}</h3>
                    <p class="fs-5">{{ $totalcoordinacionesD <= 1 && $totalcoordinacionesD > 0? "Deshabilitada" : "Deshabilitadas" }}</p>
                </div>
                <i class="fa-solid fa-xmark  fs-1 third-text-color border rounded-full  third-bg-color p-3"></i>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-4 px-5 py-2">
            <div
                class="bg-white d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalcoordinacionesH }}</h3>
                    <p class="fs-5">{{ $totalcoordinacionesH <= 1 && $totalcoordinacionesH > 0 ? "Habilitada" : "Habilitadas" }}</p>
                </div>
                <i class="fa-solid fa-check  fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </div>

    </div>
    {{-- End coordinacion card --}}

    {{-- Content --}}
    <div class="container">

        <h1 class="mb-3 text-center ">COORDINACIONES</h1>

        <div class="container d-flex flex-row-reverse">
            <a href="" type="button" class="btn btn-large my-3 mx-2 boton" data-bs-toggle="modal"
                data-bs-target="#CreateCoordinacionModal"><i class="fas fa-plus-circle me-2"></i>Agregar Coordinación</a>
        </div>

        <div class="col container">
            <table class="table" id="coordinaciones">
                <thead>
                    <tr>
                        <th scope="col" width="80">#</th>
                        <th scope="col" width="200">Nombre</th>
                        <th scope="col" width="80">Colores</th>
                        <th scope="col" width="150">Estado</th>
                        <th scope="col" width="80">Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('coordinaciones.js')
@endsection
