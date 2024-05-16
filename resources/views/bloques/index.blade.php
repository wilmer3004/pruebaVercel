@extends('layouts.plantilla')

{{-- Estilos --}}
<link rel="stylesheet" href="{{ asset('css/programas.css') }}">
<link rel="stylesheet" href="{{ asset('css/validacion.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltip.css') }}">

@section('content')
    @include('bloques.create')

    {{-- Bloques card --}}
    <div class="container d-flex flex-row-reverse ">
        <div class="col-md-6 col-sm-8 col-lg-4 px-5 py-4">
            <div class="p-3 bg-white d-flex justify-content-around align-items-center around shadow-lg mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalbloques }}</h3>
                    <p class="fs-5">{{ $totalbloques ==1 ? 'Bloque' : 'Bloques' }}</p>
                </div>
                <i class="fas fa-cubes fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </div>
        <div class="col-md-6 col-sm-8 col-lg-4 px-5 py-4">
            <div class="p-3 bg-white d-flex justify-content-around align-items-center around shadow-lg mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalbloquesDeshabilitados }}</h3>
                    <p class="fs-5">{{ $totalbloquesDeshabilitados == 1? 'Deshabilitado' : 'Deshabilitados' }}</p>
                </div>
                <i class="fas fa-solid fa-xmark fs-1 third-text border rounded-full third-bg p-3"></i>
            </div>
        </div>
        <div class="col-md-6 col-sm-8 col-lg-4 px-5 py-4">
            <div class="p-3 bg-white d-flex justify-content-around align-items-center around shadow-lg mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalbloquesHabilitados }}</h3>
                    <p class="fs-5">{{ $totalbloquesHabilitados == 1? 'Habilitado' : 'Habilitados' }}</p>
                </div>
                <i class="fas fa-solid fa-check fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </div>
    </div>
    {{-- End Bloques card --}}

    <div class="container">

        <h1 class="mb-3 text-center text">BLOQUES</h1>

        @role('superadmin|administrador')
            <div class="container d-flex flex-row-reverse">
                <a href="" type="button" class="btn btn-large  my-3 mx-2 boton" data-bs-toggle="modal"
                    data-bs-target="#CreateBloquesModal"><i class="fas fa-plus-circle me-2"></i>Agregar Bloque</a>
            </div>
        @endrole

        <div class="col container">
            <table class="table" id="bloques">
                <thead>
                    <tr>
                        <th scope="col" width="80">#</th>
                        <th scope="col" width="250">Jornada</th>
                        <th scope="col" width="250">Hora Inicio</th>
                        <th scope="col" width="250">Hora Fin</th>
                        <th scope="col" width="150">Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    
    @include('bloques.edit')
    @include('bloques.js')
@endsection
