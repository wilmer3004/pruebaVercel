@extends('layouts.plantilla')

{{-- Coordinaciones --}}
<link rel="stylesheet" href="{{ asset('css/tooltip.css') }}">
<link rel="stylesheet" href="{{ asset('css/validacion.css') }}">


@section('content')
@include('festivos.create')
    {{-- Holidays Card --}}
    <div class="container d-flex flex-row-reverse">
        <div class="col-md-6 col-sm-6 col-lg-4 px-4 py-5">
            <div class="bg-white d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalHolidays }}</h3>
                    <p class="fs-5">Festivos</p>
                </div>
                <i class="fa-regular fa-calendar-xmark fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </div>
    </div>
    {{-- End holidays Card --}}

    {{-- Content --}}
    <div class="container">

        <h1 class="mb-3 text-center">Dias Festivos</h1>

        <div class="container d-flex flex-row-reverse">
            <a href="" type="button" class="btn btn-large  my-3 mx-2 boton" data-bs-toggle="modal"
               data-bs-target="#CreateFestivoModal"><i class="fas fa-plus-circle me-2"></i>Agregar Festivo</a>
        </div>

        <div class="col container">
            <table class="table" id="holidays">
                <thead>
                <tr>
                    <th scope="col" width="100">#</th>
                    <th scope="col" width="250">Fecha</th>
                    <th scope="col" width="150">Acciones</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@include('festivos.js')
@endsection
