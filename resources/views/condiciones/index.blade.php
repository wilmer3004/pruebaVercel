@extends('layouts.plantilla')

{{-- Estilos --}}
<link rel="stylesheet" href="{{ asset('css/roles.css') }}">
<link rel="stylesheet" href="{{ asset('css/validacion.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltip.css') }}">

@section('content')
    @include('condiciones.create')
    @include('condiciones.edit')
    {{-- Condiciones card --}}
    <div class="container d-flex flex-row-reverse mt-4">
        <div class="col-md-6 col-sm-8 col-lg-4 px-3 py-5">
            <div
                class="bg-white  d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalcondiciones }}</h3>
                    <p class="fs-5">Condiciones</p>
                </div>
                <i class="fas fa-check-square fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </div>
        <div class="col-md-6 col-sm-8 col-lg-4 px-3 py-5">
            <div
                class="bg-white d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalcondicionesD }}</h3>
                    <p class="fs-5">{{ $totalcondicionesD <= 1 && $totalcondicionesD > 0? "Deshabilitada" : "Deshabilitadas" }}</p>
                </div>
                <i class="fa-solid fa-xmark  fs-1 third-text-color border rounded-full  third-bg-color p-3"></i>
            </div>
        </div>
        <div class="col-md-6 col-sm-8 col-lg-4 px-3 py-5">
            <div
                class="bg-white d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalcondicionesH }}</h3>
                    <p class="fs-5">{{ $totalcondicionesH <= 1 && $totalcondicionesH > 0 ? "Habilitada" : "Habilitadas" }}</p>
                </div>
                <i class="fa-solid fa-check  fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </div>
    </div>
    {{-- End condciones card --}}

    {{-- Content --}}
    <div class="container">

        <h1 class="mb-3 text-center">CONDICIONES</h1>

        <div class="container d-flex flex-row-reverse">
            <a href="" type="button" class="btn btn-large my-3 mx-2 boton" data-bs-toggle="modal"
                data-bs-target="#CreateConModal"><i class="fas fa-plus-square me-2"></i>Agregar Condición</a>
        </div>

        <div class="col container">
            <table class="table" id="condiciones">
                <thead>
                    <tr>
                        <th scope="col" width="80">#</th>
                        <th scope="col" width="">Condición</th>
                        <th scope="col" width="">Descripción</th>
                        <th scope="col" width="150">Estado</th>
                        <th scope="col" width="150">Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    @include('condiciones.js')
@endsection
