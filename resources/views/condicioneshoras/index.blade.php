@extends('layouts.plantilla')

{{-- Estilos --}}
<link rel="stylesheet" href="{{ asset('css/validacion.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltip.css') }}">

@section('content')
    @include('condicioneshoras.create')
    {{-- CondicionHora card --}}
    <div class="container d-flex flex-row-reverse ">
        <div class="col-md-6 col-sm-5 col-lg-4 px-5 py-2">
            <div class="p-3 bg-white d-flex justify-content-around align-items-center around shadow-lg mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalcondicionhoras }}</h3>
                    <p class="fs-5">Condiciones Horas</p>
                </div>
                <i class="fas fa-business-time fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </div>
    </div>
    {{-- End CondicionHora card --}}

    <div class="container">

        <h1 class="mb-3 text-center text">CONDICIONES HORAS</h1>

        <div class="container d-flex flex-row-reverse">
            <a href="#" type="button" class="btn btn-large  my-3 mx-2 boton" data-bs-toggle="modal"
                data-bs-target="#CreateCondicionHoraModal" id="hola"><i class="fas fa-plus-circle me-2"></i>Agregar
                Condición Hora</a>
        </div>

        <div class="col container">
            <table class="table" id="condicioneshoras">
                <thead>
                    <tr>
                        <th scope="col" width="80">#</th>
                        <th scope="col" width="250">Contrato</th>
                        <th scope="col" width="250">Condición</th>
                        <th scope="col" width="250">Porcentaje</th>
                        <th scope="col" width="150">Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('condicioneshoras.edit')
    @include('condicioneshoras.js')
@endsection
