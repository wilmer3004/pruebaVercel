@extends('layouts.plantilla')

{{-- Estilos --}}
<link rel="stylesheet" href="{{ asset('css/personas.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltip.css') }}">
<link rel="stylesheet" href="{{ asset('css/validacion.css') }}">


@section('content')
    <div class="body my-5">

        {{-- Primera Página --}}
        <form class="form form-step form-step-active" id="form1">
            @csrf
            <h4 class="tittle border-bottom fw-bold">Datos del Usuario</h4>

            {{-- Formulario Datos del Usuario --}}

            <div class="container">

                <div class="row py-3">

                    <input type="hidden" name="id" id="id" value="{{ $user->id }}">

                    {{-- Nombre --}}
                    <div class="col-md-6 input-grupo">
                        <label for="nombres" class="form-label fw-bold">Nombre</label>
                        <input type="text" class="form-control input" id="nombres" name="nombre"
                            onkeyup="validateName()" onblur="validateName()" value="{{ $user->persona->name }}">
                        <span id="name-error"></span>
                        @error('nombres')
                            <p style="color: red">*{{ $message }}</p>
                            <br>
                        @enderror
                    </div>

                    {{-- Apellido --}}
                    <div class="col-md-6 input-grupo">
                        <label for="apellidos" class="form-label fw-bold">Apellido</label>
                        <input type="text" class="form-control input" id="apellidos" name="apellido"
                            onkeyup="validateApellido()" onblur="validateApellido()"
                            value="{{ $user->persona->lastname }}">
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
                        <label for="tipoDocumento" class="form-label fw-bold">Tipo de Documento</label>
                        <select class="form-select" id="tipoDoc" name="tipoDoc" style="width: 100%">
                            {{-- <option selected disabled>Seleccione un tipo de documento</option> --}}
                            <option selected value="{{ $user->persona->document_type_id }}">
                                {{ $user->persona->documentsType->name }}</option>
                            @foreach ($documentsType as $documentType)
                                @if($user->persona->document_type_id != $documentType->id)
                                    <option value="{{ $documentType->id }}">{{ $documentType->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    {{-- Numero de documento --}}
                    <div class="col-md-6 input-grupo">
                        <label for="numeroDocumento" class="form-label fw-bold">Número de Documento</label>
                        <input type="number" class="form-control input" id="numeroDocumento" name="documento"
                            onkeyup="validateNumDoc()" onblur="validateNumDoc()"
                            value="{{ $user->persona->document }}">
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
                                onkeyup="validateEmail()" onblur="validateEmail()"
                                value="{{ $user->persona->email }}">
                            <span id="email-error"></span>
                            @error('email')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>
                    </div>

                    {{-- Estado --}}
                    <div class="col-md-6 input-grupo">
                        <div class="mb-3">
                            <label for="estado" class="form-label fw-bold">Estado</label><br>
                            <select class="form-select" id="estado" name="estado" style="width: 100%">
                                <option value="{{ $user->state =="activo" ? "activo" : "inactivo" }}">{{ $user->state =="activo" ? "Activo" : "Inactivo" }}</option>
                                <option value="{{ $user->state =="activo" ? "inactivo" : "activo" }}">{{ $user->state =="activo" ? "Inactivo" : "Activo" }}</option>

                            </select>
                        </div>
                    </div>
                </div>

                <div class="row py-3">
                    {{-- Teléfono --}}
                    <div class="col-md-6 input-grupo">
                        <label for="telefono" class="form-label fw-bold">Teléfono</label>
                        <input type="number" class="form-control input" id="telefono" name="telefono"
                            onkeyup="validateTel()" onblur="validateTel()"
                            value="{{ $user->persona->phone }}">
                        <span id="telefono-error"></span>
                        @error('telefono')
                            <p style="color: red">*{{ $message }}</p>
                            <br>
                        @enderror
                    </div>

                    {{-- Roles --}}
                    <div class="col-md-6 input-grupo">
                        <div class="mb-3">
                            <label for="estado" class="form-label fw-bold">Roles</label><br>
                            @foreach ($roles as $value)
                                <input type="checkbox" class="" name="roles[]" value="{{ $value->id }}"
                                    {{ $user->roles->pluck('id')->contains($value->id) ? 'checked' : '' }}>
                                {{ $value->name }}<br>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="input-grupo my-4">
                    <span id="error"></span>
                </div>

                <div class="bts-group py-2">
                    <a href="{{ route('personas.index') }}" class="bt bt-next">Regresar</a>
                    <input class="bt py-3" value="finalizar" id="btnGuardar2" type="submit">
            </div>
        </form>
    </div>

    @include('personas.jsEdit')
@endsection
