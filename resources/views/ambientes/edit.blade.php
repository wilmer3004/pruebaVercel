    <div class="modal fade" id="EditAmbienteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Ambiente</h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formularioE">
                    @csrf

                    <input type="hidden" name="id" id="id">

                    {{-- Grupo nombre --}}

                    <!-- Nombre ambiente -->
                    <div class="row">
                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="name" class="col-form-label fw-bold">Nombre del Ambiente</label>
                            <input name="nombre" type="text" class="form-control formulario__input" id="nombreE"
                                onkeyup="validateName()" onblur="validateName()">
                            <span id="name-error"></span>
                            @error('name')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                        <!-- Sede -->
                        <div class="col-6 col-md-6 py-2">
                            <label for="sede" class="col-form-label fw-bold">Sede</label>
                            <select name="sede" id="sedeE" class="form-select"
                                aria-label="form-select-sm example" value="{{ old('tipo') }}" required>
                                @foreach ($sedes as $sede)
                                    <option value="{{ $sede->id }}">
                                        {{ $sede->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                    </div>

                    <div class="row">

                        <!-- Piso -->
                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="piso" class="col-form-label fw-bold">Piso</label>
                            <input name="piso" type="number" class="form-control formulario__input" id="pisoE"
                                onkeyup="validateName()" onblur="validateName()">
                            <span id="piso-error"></span>
                            @error('piso')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                        <!-- Capacidad -->
                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="capacidad" class="col-form-label fw-bold">Capacidad</label>
                            <input name="capacidad" type="number" class="form-control formulario__input" id="capacidadE"
                                onkeyup="validateCapacidad()" onblur="validateCapacidad()">
                            <span id="capacidad-error"></span>
                            @error('capacidad')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="py-2 col-mb-6 col-6 input-grupo">
                            <label for="coordinations" class="col-form-label fw-bold">Coordinaciones</label>
                            <br>
                            <select name="coordinations" id="coordinationsE" class="form-select" aria-label=".form-select-sm example" multiple required style="width:  100%">
                                @foreach ($coordinations as $coordination)
                                    <option value="{{ $coordination->id }}">
                                        {{ $coordination->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('coordinations')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                        <div class="py-2 col-mb-6 col-6 input-grupo">
                            <!-- Tipo componente -->
                            <label class="col-form-label fw-bold">Tipo componente</label>
                            @foreach ($tipoComponentes as $tipoComponente)
                                <div class="form-check">
                                    <input class="form-check-input tipoComponenteCheckbox" type="checkbox" value="{{ $tipoComponente->id }}" id="tipoComponenteAmbienteE_{{ $tipoComponente->id }}" name="tipoComponenteAmbienteE[]">
                                    <label class="form-check-label" for="tipoComponenteAmbienteE_{{ $tipoComponente->id }}">
                                        {{ $tipoComponente->name }}
                                    </label>
                                </div>
                            @endforeach
                            @error('tipoComponenteAmbienteE')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                    </div>


            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger tooltipA " data-bs-dismiss="modal" data-tooltip="Cerrar"><i
                        class="fas fa-times-circle fs-5"></i></button>

                <button type="submit" id="btn" class="btn btn-warning tooltipA" id="guardar"
                    data-tooltip="Actualizar"><i class="fas fa-edit fs-5"></i></button>
            </div>
            </form>
        </div>
    </div>
</div>
</div>
