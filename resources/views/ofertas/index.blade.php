@extends('layouts.plantilla')

{{-- Estilos --}}
<link rel="stylesheet" href="{{ asset('css/contratos.css') }}">
<link rel="stylesheet" href="{{ asset('css/validacion.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltip.css') }}">

@section('content')
    @include('ofertas.create')
    @include('ofertas.edit')

    {{-- Ofertas card --}}
    <div class="container d-flex flex-row-reverse">
        <div class="col-md-6 col-sm-6 col-lg-4 px-4 py-5">
            <div class="bg-white d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalofertas }}</h3>
                    <p class="fs-5">Ofertas</p>
                </div>
                <i class="fas fa-file-signature fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </div>
    </div>
    {{-- End Ofertas card --}}

    {{-- Content --}}
    <div class="container">

        <h1 class="mb-3 text-center">OFERTAS</h1>

        <div class="container d-flex flex-row-reverse">
            <a href="" type="button" class="btn btn-large  my-3 mx-2 boton" data-bs-toggle="modal"
                data-bs-target="#CreateOfertaModal"><i class="fas fa-plus-circle me-2"></i>Agregar Oferta</a>
        </div>

        <div class="col container">
            <table class="table" id="ofertas">
                <thead>
                    <tr>
                        <th scope="col" width="100">#</th>
                        <th scope="col" width="250">Nombre</th>
                        <th scope="col" width="250">Estado</th>
                        <th scope="col" width="150">Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('ofertas.js')
@endsection
