@extends('layouts.plantilla')

{{-- Estilos --}}
<link rel="stylesheet" href="{{ asset('css/contratos.css') }}">
<link rel="stylesheet" href="{{ asset('css/validacion.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltip.css') }}">


@section('content')
    @include('contratos.create')
    @include('contratos.edit')

    {{-- Contratos card --}}
    <div class="container d-flex flex-row-reverse mt-4">
        <div class="col-md-6 col-sm-6 col-lg-4 px-5 py-2">
            <div class="bg-white d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalcontratos }}</h3>
                    <p class="fs-5">Contratos</p>
                </div>
                <i class="fas fa-book-reader fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-4 px-5 py-2">
            <div
                class="bg-white d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalcontratosD }}</h3>
                    <p class="fs-5">{{ $totalcontratosD <= 1 && $totalcontratosD > 0? "Deshabilitado" : "Deshabilitados" }}</p>
                </div>
                <i class="fa-solid fa-xmark  fs-1 third-text-color border rounded-full  third-bg-color p-3"></i>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-4 px-5 py-2">
            <div
                class="bg-white d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalcontratosH }}</h3>
                    <p class="fs-5">{{ $totalcontratosH <= 1 && $totalcontratosH > 0 ? "Habilitado" : "Habilitados" }}</p>
                </div>
                <i class="fa-solid fa-check  fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </div>
    </div>

    {{-- End contratos card --}}

    {{-- Content --}}
    <div class="container">

        <h1 class="mb-3 text-center">TIPOS DE CONTRATO</h1>

        <div class="container d-flex flex-row-reverse">
            <a href="" type="button" class="btn btn-large my-3 mx-2 boton" data-bs-toggle="modal"
                data-bs-target="#CreateContratoModal"><i class="fas fa-plus-circle me-2"></i>Agregar Contrato</a>
        </div>

        <div class="col container">
            <table class="table" id="contrato">
                <thead>
                    <tr>
                        <th scope="col" width="100">#</th>
                        <th scope="col" width="250">Contrato</th>
                        <th scope="col" width="250">Total Horas Mensuales</th>
                        <th scope="col" width="150">Estado</th>
                        <th scope="col" width="150">Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('contratos.js')
@endsection
