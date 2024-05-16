@extends('layouts.plantilla')

{{-- Estilos --}}
<link rel="stylesheet" href="{{ asset('css/instructores.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltip.css') }}">
<link rel="stylesheet" href="{{ asset('css/validacion.css') }}">


@section('content')
    <div class="body my-5">
        {{-- Barra de Progreso --}}
        <form class="form">
            <div class="progressbar">
                <div class="progress" style="height: 4px" id="progress"></div>
                <div class="progress-step progress-step-active" data-title="Datos"></div>
                <div class="progress-step" data-title="Detalles"></div>
            </div>
        </form>

        {{-- Primera Página --}}
        <form class="form form-step form-step-active" id="form1">
            @csrf
            <h4 class="tittle border-bottom fw-bold">Datos del Instructor</h4>

            {{-- Formulario Datos del Instructor --}}

            <div class="container">

                <div class="row py-3">

                    <input type="hidden" name="id" id="id" value="{{ $instructor->id }}">

                    {{-- Nombre --}}
                    <div class="col-md-6 input-grupo">
                        <label for="nombres" class="form-label fw-bold">Nombre</label>
                        <input type="text" class="form-control input" id="nombres" name="nombre"
                            onkeyup="validateName()" onblur="validateName()" value="{{ $instructor->user->persona->name }}">
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
                            value="{{ $instructor->user->persona->lastname }}">
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

                            <option selected value="{{ $instructor->user->persona->document_type_id }}">
                                {{ $instructor->user->persona->documentsType->name }}</option>
                            @foreach ($documentsType as $documentType)
                                @if($instructor->user->persona->document_type_id != $documentType->id)
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
                            value="{{ $instructor->user->persona->document }}">
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
                                value="{{ $instructor->user->persona->email }}">
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
                                <option value="{{ $instructor->user->state == "activo" ? "activo": "inactivo" }}" selected>{{ $instructor->user->state == "activo" ? "Activo": "Inactivo" }}</option>
                                <option value="{{ $instructor->user->state == "inactivo" ? "inactivo" : "activo" }}" selected>{{ $instructor->user->state == "inactivo" ? "Inactivo" : "Activo" }}</option>
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
                            value="{{ $instructor->user->persona->phone }}">
                        <span id="telefono-error"></span>
                        @error('telefono')
                            <p style="color: red">*{{ $message }}</p>
                            <br>
                        @enderror
                    </div>

                    {{-- Roles --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="estado" class="form-label fw-bold">Roles</label><br>
                            @foreach ($roles as $value)
                                <input type="checkbox" class="" name="roles[]" value="{{ $value->id }}"
                                    {{ $instructor->user->roles->pluck('id')->contains($value->id) ? 'checked' : '' }}>
                                {{ $value->name }}<br>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Coordinaciones --}}
                <div class="row py-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="coordinaciones" class="form-label fw-bold">Coordinaciones</label><br>
                            <select class="form-select" id="coordinaciones" style="width: 100%" multiple name="coordinaciones[]">
                                @foreach ($coordinaciones as $coordinacion)
                                    <option value="{{ $coordinacion->id }}"
                                        {{ in_array($coordinacion->id, $instructor->coordinaciones->pluck('id')->toArray()) ? 'selected' : '' }}>
                                        {{ $coordinacion->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="input-grupo my-4">
                    <span id="error"></span>
                </div>

                <div class="bts-group py-2">
                    <a href="{{ route('instructores.index') }}" class="bt bt-next">Regresar</a>
                    <input class="bt bt-next" value="Siguiente" id="btnGuardar1" type="submit">
                </div>
            </div>
        </form>

        {{-- Segunda Página --}}
        <form action="#" class="form form-step" id="form2">
            @csrf
            <h4 class="tittle fw-bold">Detalles del Instructor</h4>
            <h4 class="tittle fw-bold" id="fullname"></h4>
            <h4 class="border-bottom my-4 fw-bold" id="detalles"></h4>

            <input type="hidden" name="id" id="id" value="{{ $instructor->id }}">

            <div class="row py-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="estado" class="form-label fw-bold">Contrato</label><br>
                        <select class="form-select" id="contrato" style="width: 100%" name="contrato">
                            <option selected disabled>Seleccione un contrato</option>
                            @foreach ($contratos as $contrato)
                                <option value="{{ $contrato->id }}"
                                    {{ $instructor->contract_id == $contrato->id ? 'selected' : '' }}>
                                    {{ $contrato->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="estado" class="form-label fw-bold">Tipo de componente que enseña</label><br>
                        <select class="form-select" id="tipo" style="width: 100%" name="tipo">
                            <option selected disabled>Seleccione un tipo componente</option>
                            @foreach ($tipoComponentes as $tipo)
                                <option value="{{ $tipo->id }}"
                                    {{ $instructor->tipoComponente->pluck('id')[0] === $tipo->id ? 'selected' : '' }}>
                                    {{ $tipo->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>

            <div class="row py-3">
                {{-- <div class="col-md-6">
                    <div class="mb-3">
                        <label for="estado" class="form-label">Componentes</label><br>
                        @foreach ($componentes as $key => $value)
                            <input type="checkbox" class="" name="componentes[]" value="{{ $key }}"
                                {{ $instructor->componentes->pluck('id')->contains($key) ? 'checked' : '' }}>
                            {{ $value }}<br>
                        @endforeach
                    </div>
                </div> --}}

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="estado" class="form-label fw-bold">Condiciones</label><br>
                        @foreach ($condiciones as $key => $value)
                            <input type="checkbox" class="" name="condiciones[]" value="{{ $key }}"
                                {{ $instructor->condiciones->pluck('id')->contains($key) ? 'checked' : '' }}>
                            {{ $value }}<br>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="bts-group py-2">
                <input class="bt py-3" value="finalizar" id="btnGuardar2" type="submit">
            </div>
        </form>

    </div>

    @include('instructores.jsEdit')
@endsection
