@extends('layouts.plantilla')

{{-- Estilos --}}
<link rel="stylesheet" href="{{ asset('css/instructores.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltip.css') }}">
<link rel="stylesheet" href="{{ asset('css/validacion.css') }}">


@section('content')
    <div class="body my-5">
        {{-- Primera Página --}}
        <div class="form form-step form-step-active" id="form1">
            @csrf
            <h4 class="tittle border-bottom fw-bold">INFORMACIÓN PERSONAL</h4>

            {{-- Formulario Datos del Instructor --}}

            <div class="container">

                <div class="row py-3">

                    {{-- Nombre --}}
                    <div class="col-md-6 input-grupo ">
                        <div class="border-bottom">
                            <label for="nombres" class="form-label fw-bold">Nombre</label>
                        </div>
                        <div class="my-2">
                            <p id="nombre"></p>
                        </div>
                    </div>

                    {{-- Apellido --}}
                    <div class="col-md-6 input-grupo ">
                        <div class="border-bottom">
                            <label for="nombres" class="form-label fw-bold">Apellido</label>
                        </div>
                        <div class="my-2">
                            <p id="apellido">Apellido</p>
                        </div>
                    </div>
                </div>

                <div class="row py-3">

                    {{-- Tipo de documento --}}
                    <div class="col-md-6 input-grupo ">
                        <div class="border-bottom">
                            <label for="nombres" class="form-label fw-bold">Tipo de Documento</label>
                        </div>
                        <div class="my-2">
                            <p id="tipoDoc"></p>
                        </div>
                    </div>

                    {{-- Numero de documento --}}
                    <div class="col-md-6 input-grupo ">
                        <div class="border-bottom">
                            <label for="nombres" class="form-label fw-bold">Documento</label>
                        </div>
                        <div class="my-2">
                            <p id="doc"></p>
                        </div>
                    </div>
                </div>


                <div class="row py-3">

                    {{-- Email --}}
                    <div class="col-md-6 input-grupo ">
                        <div class="border-bottom">
                            <label for="nombres" class="form-label fw-bold">Email</label>
                        </div>
                        <div class="my-2">
                            <p id="email"></p>
                        </div>
                    </div>

                    {{-- Estado --}}
                    <div class="col-md-6 input-grupo ">
                        <div class="border-bottom">
                            <label for="nombres" class="form-label fw-bold">Estado</label>
                        </div>
                        <div class="my-2">
                            <p id="estado"></p>
                        </div>
                    </div>
                </div>

                <div class="row py-3">
                    {{-- Teléfono --}}
                   <div class="col-md-6 input-grupo ">
                        <div class="border-bottom">
                            <label for="nombres" class="form-label fw-bold">Telefono</label>
                        </div>
                        <div class="my-2">
                            <p id="tel"></p>
                        </div>
                    </div>

                    {{-- Roles --}}
                    <div class="col-md-6 input-grupo ">
                        <div class="border-bottom">
                            <label for="nombres" class="form-label fw-bold">Roles</label>
                        </div>
                        <div class="my-2">
                            <p id="roles"></p>
                        </div>
                    </div>
                </div>

                 <h4 class="tittle border-bottom fw-bold my-4">DETALLES DEL INSTRUCTOR</h4>

                 <div class="row py-3">

                    {{-- Coordinación --}}
                   <div class="col-md-6 input-grupo ">
                        <div class="border-bottom">
                            <label for="nombres" class="form-label fw-bold">Coordinación</label>
                        </div>
                        <div class="my-2">
                            <p id="coordinacion"></p>
                        </div>
                    </div>

                    {{-- Contrato --}}
                    <div class="col-md-6 input-grupo ">
                        <div class="border-bottom">
                            <label for="nombres" class="form-label fw-bold">Contrato</label>
                        </div>
                        <div class="my-2">
                            <p id="contrato"></p>
                        </div>
                    </div>
                </div>

                <div class="row py-3">
                    {{-- Competencias --}}
                   <div class="col-md-6 input-grupo ">
                        <div class="border-bottom">
                            <label for="nombres" class="form-label fw-bold">Componentes</label>
                        </div>
                        <div class="my-2">
                            <p id="componentes"></p>
                        </div>
                    </div>

                    {{-- Condiciones --}}
                    <div class="col-md-6 input-grupo ">
                        <div class="border-bottom">
                            <label for="nombres" class="form-label fw-bold">Condiciones</label>
                        </div>
                        <div class="my-2">
                            <p id="condiciones"></p>
                        </div>
                    </div>
                </div>

                <div class="row py-3">
                    {{-- Total Horas --}}
                   <div class="col-md-6 input-grupo ">
                        <div class="border-bottom">
                            <label for="nombres" class="form-label fw-bold">Total de Horas mensuales</label>
                        </div>
                        <div class="my-2">
                            <p id="horas"></p>
                        </div>
                    </div>

                    {{-- Tipo de component --}}
                   <div class="col-md-6 input-grupo ">
                        <div class="border-bottom">
                            <label for="tipo" class="form-label fw-bold">Tipo de componente que enseña</label>
                        </div>
                        <div class="my-2">
                            <p id="tipo"></p>
                        </div>
                    </div>
                </div>

                <div class="bts-group py-2">
                    <a href="{{route('instructores.index')}}" class="bt bt-next">Regresar</a>
                </div>
            </div>
        </div>


    </div>

    @include('instructores.jsShow')
@endsection
