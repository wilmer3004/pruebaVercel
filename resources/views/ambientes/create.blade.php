<div class="modal fade" id="CreateAmbienteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <!-- Titulo -->
                <h1 class="modal-title fs-5" id="exampleModalLabel">Registrar Ambiente</h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>

            <!-- Cuerpo del modal -->
            <div class="modal-body">

                <form method="POST" id="formulario">

                    @csrf

                    {{-- Grupo nombre --}}
                    <div class="row">

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="name" class="col-form-label fw-bold">Nombre del Ambiente</label>
                            <input name="nombre" type="text" class="form-control formulario__input" id="nombre"
                                value="{{ old('name') }}" onkeyup="validateName()" onblur="validateName()">
                            <span id="name-error"></span>
                            @error('name')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                        <!-- Sede -->

                        <div class="col-6 col-md-6 py-2">
                            <label for="sede" class="col-form-label fw-bold">Sede</label>
                            <select name="sede" id="" class="form-select"
                                aria-label="form-select-sm example" value="">
                                <option disabled selected>Seleccione una sede...</option>
                                <span id="sede-error"></span>
                                @foreach ($sedes as $sede)
                                    <option value="{{ old('sede', $sede->id) }}">{{ $sede->name }}</option>
                                @endforeach
                            </select>
                            @error('sede')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                    </div>

                    <div class="row">

                        <!-- Piso del ambiente -->

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="piso" class="col-form-label fw-bold">Piso</label>
                            <input name="piso" type="number" class="form-control formulario__input" id="piso"
                                value="{{ old('piso') }}" onkeyup="validatePiso()" onblur="validatePiso()">
                            <span id="piso-error"></span>
                            @error('piso')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                        <!-- Capacidad del ambiente -->

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="capacidad" class="col-form-label fw-bold">Capacidad</label>
                            <input name="capacidad" type="number" class="form-control formulario__input"
                                id="capacidad" value="{{ old('capacidad') }}" onkeyup="validateCapacidad()"
                                onblur="validateCapacidad()">
                            <span id="capacidad-error"></span>
                            @error('capacidad')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>
                    </div>

                    <div class="row">

                        <!-- Coordinations -->
                        <div class="py-2 col-mb-6 col-6 input-grupo">
                            <label for="coordinations" class="col-form-label fw-bold">Coordinaciones</label><br>
                            <select name="coordinations" id="coordinations" style="width: 100%" multiple required>
                                <option disabled>Seleccione una coordinacion...</option>
                                @foreach ($coordinations as $coordination)
                                <option value="{{ $coordination->id }}">{{ $coordination->name }}</option>
                                @endforeach
                            </select>
                            <span id="coordinations-error"></span>
                            @error('coordinations')
                            <p style="color: red">*{{ $message }}</p>
                            <br>
                            @enderror
                        </div>

                        <!-- Tipo componente -->
                        <div class="py-2 col-mb-6 col-6 input-grupo">
                            <label for="tipoComponentesAmbiente" class="col-form-label fw-bold">Tipo componente</label>
                            <br>
                            @foreach ($tipoComponentes as $tipoComponente)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $tipoComponente->id }}" id="tipoComponente{{ $tipoComponente->id }}" name="componentes[]">
                                    <label class="form-check-label" for="tipoComponente{{ $tipoComponente->id }}">
                                        {{ $tipoComponente->name }}
                                    </label>
                                </div>
                            @endforeach
                            <span id="components-error"></span>
                            @error('componentes')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>
                    </div>

                    <!-- Input error -->
                    <div class="input-grupo my-4">
                        <span id="error"></span>
                    </div>

                    <!-- Botones -->
                    <div class="modal-footer">

                        <!-- Cerrar -->
                        <button type="button" class="btn btn-danger tooltipA" data-bs-dismiss="modal"
                            data-tooltip="Cerrar"><i class="fas fa-times-circle fs-5"></i></button>

                        <!-- Guardar -->
                        <button type="submit" id="btn" class="btn btn-success tooltipA" id="guardar"
                            data-tooltip="Guardar"><i class="fas fa-save fs-5"></i></button>
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
