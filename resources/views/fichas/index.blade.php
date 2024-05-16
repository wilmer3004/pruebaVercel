@extends('layouts.plantilla')

{{-- Estilos --}}
<link rel="stylesheet" href="{{ asset('css/programas.css') }}">
<link rel="stylesheet" href="{{ asset('css/validacion.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltip.css') }}">

@section('content')
@include('fichas.create')
@include('fichas.edit')
@include('fichas.unionChips')

    {{-- Programas card --}}
    <div class="container d-flex flex-row-reverse">
        <div class="col-md-6 col-sm-8 col-lg-4 px-5 py-4">
            <div
                class=" bg-white d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalfichas }}</h3>
                    <p class="fs-5">{{ $totalfichas ==1 ? 'Ficha' : 'Fichas' }}</p>
                </div>
                <i class="fas fa-book fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </div>
        <div class="col-md-6 col-sm-8 col-lg-4 px-5 py-4">
            <div
                class=" bg-white d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalfichasDeshabilitadas }}</h3>
                    <p class="fs-5">{{ $totalfichasDeshabilitadas == 1? 'Deshabilitada' : 'Deshabilitadas' }}</p>
                </div>
                <i class="fas fa-solid fa-xmark fs-1 third-text border rounded-full third-bg p-3"></i>
            </div>
        </div>
        <div class="col-md-6 col-sm-8 col-lg-4 px-5 py-4">
            <div
                class=" bg-white d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalfichasHabilitadas }}</h3>
                    <p class="fs-5">{{ $totalfichasHabilitadas == 1? 'Habilitada' : 'Habilitadas' }}</p>
                </div>
                <i class="fas fa-solid fa-check fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </div>
    </div>
    {{-- End Programas card --}}

    <div class="container">

        <h1 class="mb-3 text-center text">FICHAS</h1>

        @role('superadmin|administrador')
        <div class="d-flex flex-row-reverse">
            <div class=" d-flex flex-row-reverse">
                <a href="" type="button" class="btn btn-large my-3 mx-2 boton" data-bs-toggle="modal"
                data-bs-target="#CreateFichaModal"><i class="fas fa-plus-circle me-2"></i>Agregar Ficha</a>
            </div>
            <div class=" d-flex flex-row-reverse">
                <a href="" type="button" class="btn btn-large my-3 mx-2 boton" data-bs-toggle="modal"
                    data-bs-target="#unionChips" id="unionChipsButton"><i class="fa-solid fa-infinity"></i> Unificar Fichas</a>
            </div>
        </div>
        @endrole

        <div class="col container">
            <table class="table" id="fichas">
                <thead>
                    <tr>
                        <th scope="col" width="80">#</th>
                        <th scope="col" width="">N. Ficha</th>
                        <th scope="col" width="">N. Aprendices</th>
                        <th scope="col" width="">Programa</th>
                        <th scope="col" width="">Jornada</th>
                        <th scope="col" width="">Oferta</th>
                        <th scope="col" width="">Trimestre</th>
                        <th scope="col" width="">Estado</th>
                        <th scope="col" width="150">Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('fichas.js')
@endsection
