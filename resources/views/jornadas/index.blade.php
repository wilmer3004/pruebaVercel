@extends('layouts.plantilla')

{{-- Estilos --}}
<link rel="stylesheet" href="{{ asset('css/sedes.css') }}">
<link rel="stylesheet" href="{{ asset('css/validacion.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltip.css') }}">
<link rel="stylesheet" href="{{ asset('css/jornada.css') }}">

@section('content')
@include('jornadas.create')
@include('jornadas.edit')

    {{-- Jornada card --}}
    <div class="container d-flex flex-row-reverse">
        <div class="col-md-6 col-sm-8 col-lg-4 px-5 py-4">
            <div
                class="bg-white d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totaljornadas }}</h3>
                    <p class="fs-5">{{ $totaljornadas ==1 ? 'Jornada' : 'Jornadas' }}</p>
                </div>
                <i class="fas fa-stopwatch fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </div>
        <div class="col-md-6 col-sm-8 col-lg-4 px-5 py-4">
            <div
                class="bg-white d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totaljornadasDeshabilitadas }}</h3>
                    <p class="fs-5">{{ $totaljornadasDeshabilitadas == 1? 'Deshabilitada' : 'Deshabilitadas' }}</p>
                </div>
                <i class="fas fa-solid fa-xmark fs-1 third-text border rounded-full third-bg p-3"></i>
            </div>
        </div>
        <div class="col-md-6 col-sm-8 col-lg-4 px-5 py-4">
            <div
                class="bg-white d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totaljornadasHabilitadas }}</h3>
                    <p class="fs-5">{{ $totaljornadasHabilitadas == 1? 'Habilitada' : 'Habilitadas' }}</p>
                </div>
                <i class="fas fa-solid fa-check fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </div>
    </div>
    {{-- End Jornada card --}}

    {{-- Content --}}
    <div class="container">

        <h1 class="mb-3 text-center">JORNADAS</h1>

        <div class="container d-flex flex-row-reverse">
            <a href="" type="button" class="btn btn-large  my-3 mx-2 boton" data-bs-toggle="modal"
                data-bs-target="#CreateJornadaModal"><i class="fas fa-plus-circle me-2"></i>Agregar Jornada</a>
        </div>

        <div class="col container">
            <table class="table" id="jornadas">
                <thead>
                    <tr>
                        <th scope="col" width="80">#</th>
                        <th scope="col" width="">Nombre</th>
                        <th scope="col" width="">Color</th>
                        <th scope="col" width="">Estado</th>
                        <th scope="col" width="150">Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('jornadas.js')
@endsection
