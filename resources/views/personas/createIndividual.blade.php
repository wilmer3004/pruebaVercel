@extends('layouts.plantilla')

{{-- Estilos --}}
<link rel="stylesheet" href="{{ asset('css/tooltip.css') }}">
<link rel="stylesheet" href="{{ asset('css/validacion.css') }}">
<link rel="stylesheet" href="{{ asset('css/personas.css') }}">

@section('content')
    <form class="form form-step form-step-active my-5" id="form1">
        @csrf
        <h4 class="tittle border-bottom fw-bold">Datos del Usuario</h4>

        {{-- Formulario Datos del Usuario --}}

        <div class="container">
            <div class="row py-3">

                {{-- Nombre --}}
                <div class="col-md-6 input-grupo">
                    <label for="nombres" class="form-label fw-bold">Nombre</label>
                    <input type="text" class="form-control input" id="nombres" name="nombre" onkeyup="validateName()"
                        onblur="validateName()">
                    <span id="name-error"></span>
                    @error('nombre')
                        <p style="color: red">*{{ $message }}</p>
                        <br>
                    @enderror
                </div>

                {{-- Apellido --}}
                <div class="col-md-6 input-grupo">
                    <label for="apellidos" class="form-label fw-bold">Apellido</label>
                    <input type="text" class="form-control input" id="apellidos" name="apellido"
                        onkeyup="validateApellido()" onblur="validateApellido()">
                    <span id="apellidos-error"></span>
                    @error('apellidos')
                        <p style="color: red">*{{ $message }}</p>
                        <br>
                    @enderror
                </div>
            </div>

            <div class="row py-3">

                {{-- Tipo de documento --}}
                <div class="col-md-6 input-grupo">
                    <label for="documentsType" class="form-label fw-bold">Tipo de Documento</label>
                    <select class="form-select" id="documentsType" name="documentsType" style="width: 100%">
                        <option selected disabled>Seleccione un tipo de documento</option>
                        @foreach ($documentsType as $documentType)
                            <option value="{{ $documentType->id }}">{{ $documentType->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Numero de documento --}}
                <div class="col-md-6 input-grupo">
                    <label for="numeroDocumento" class="form-label fw-bold">Número de Documento</label>
                    <input type="number" class="form-control input" id="numeroDocumento" name="documento"
                        onkeyup="validateNumDoc()" onblur="validateNumDoc()">
                    <span id="numeroDocumento-error"></span>
                    @error('numeroDocumento')
                        <p style="color: red">*{{ $message }}</p>
                        <br>
                    @enderror
                </div>
            </div>


            <div class="row py-3">

                {{-- Email --}}
                <div class="col-md-6 input-grupo">
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control input" id="email" name="email"
                            onkeyup="validateEmail()" onblur="validateEmail()">
                        <span id="email-error"></span>
                        @error('email')
                            <p style="color: red">*{{ $message }}</p>
                            <br>
                        @enderror
                    </div>
                </div>

                {{-- Teléfono --}}
                <div class="col-md-6 input-grupo">
                    <label for="telefono" class="form-label fw-bold">Teléfono</label>
                    <input type="number" class="form-control input" id="telefono" name="telefono" onkeyup="validateTel()"
                        onblur="validateTel()">
                    <span id="telefono-error"></span>
                    @error('telefono')
                        <p style="color: red">*{{ $message }}</p>
                        <br>
                    @enderror
                </div>
            </div>

            <div class="row py-3">

                {{-- Roles --}}
                <div class="col-md-6 input-grupo">
                    <div class="mb-3">
                        <label for="roles" class="form-label fw-bold">Roles</label><br>
                        <select class="" id="roles" name="roles[]" multiple style="width: 100%">
                            <option disabled>Seleccione un rol</option>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol->id }}">{{ $rol->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Estado --}}
                <div class="col-md-6 input-grupo">
                    <div class="mb-3">
                        <label for="estado" class="form-label fw-bold">Estado</label><br>
                        <select class="form-select" id="estado" name="estado" style="width: 100%">
                            <option selected disabled>Seleccione un estado</option>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>

                </div>

            </div>

            {{-- Tipos de documentos --}}
            {{-- <div class="col-md-6 input-grupo">

            </div> --}}


            <div class="input-grupo my-4">
                <span id="error"></span>
            </div>

            <div class="bts-group py-2">
                <a href="{{route('personas.index')}}" class="bt bt-next">Regresar</a>
                <input class="bt py-3" value="finalizar" id="btnGuardar1" type="submit">
            </div>
        </div>
    </form>
    @include('personas.jsCreate')
@endsection
