@extends('layouts.plantilla')

{{-- Estilos --}}
<link rel="stylesheet" href="{{ asset('css/trimestres.css') }}">
<link rel="stylesheet" href="{{ asset('css/validacion.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltip.css') }}">

@section('content')
@include('tipocomponentes.create')
@include('tipocomponentes.edit')
    {{-- Tipocomponente card --}}
    <div class="container d-flex flex-row-reverse">
        <div class="col-md-6 col-sm-8 col-lg-4 px-5 py-4">
            <div
                class="bg-white d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totaltipos }}</h3>
                    <p class="fs-5">Tipo de Componentes</p>
                </div>
                <i class="fas fa-clipboard-list fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </div>
    </div>
    {{-- End Tipocomponente card --}}

    {{-- Content --}}
    <div class="container">

        <h1 class="mb-3 text-center">TIPOS DE COMPONENTES</h1>

        <div class="container d-flex flex-row-reverse">
            <a href="" type="button" class="btn btn-large my-3 mx-2 boton" data-bs-toggle="modal"
                data-bs-target="#CreateTipolModal"><i class="fas fa-plus-circle me-2"></i>Agregar Tipo</a>
        </div>

        <div class="col container">
            <table class="table" id="tipocomponente">
                <thead>
                    <tr>
                        <th scope="col" width="80">#</th>
                        <th scope="col" width="">Nombre</th>
                        <th scope="col" width="">Estado</th>
                        <th scope="col" width="">Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('tipocomponentes.js')
@endsection
