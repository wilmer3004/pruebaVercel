<div class="modal fade" id="CreateSedeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar Sede</h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formulario">
                    @csrf

                    <div class="row">

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="nombre" class="col-form-label fw-bold">Nombre</label>
                            <input name="nombre" type="text" class="form-control" id="nombre"
                                value="{{ old('name') }}" onkeyup="validateName()" onblur="validateName()">
                            <span id="name-error"></span>
                            @error('name')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="ambientes" class="col-form-label fw-bold">Capacidad de ambientes</label>
                            <input name="ambientes" type="number" class="form-control" id="ambientes"
                                value="{{ old('ambientes') }}" onkeyup="validateAmbientes()" onblur="validateAmbientes()">
                            <span id="ambientes-error"></span>
                            @error('ambientes')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                    </div>

                    <div class="row">

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="direccion" class="col-form-label fw-bold">Dirección</label>
                            <input name="direccion" type="text" class="form-control" id="direccion"
                                value="{{ old('direccion') }}" onkeyup="validateDireccion()" onblur="validateDireccion()">
                            <span id="direccion-error"></span>
                            @error('direccion')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="pisos" class="col-form-label fw-bold">Pisos</label>
                            <input name="pisos" type="number" class="form-control" id="pisos"
                                value="{{ old('pisos') }}" onkeyup="validatePisos()" onblur="validatePisos()">
                            <span id="pisos-error"></span>
                            @error('pisos')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                    </div>
                    <div class="input-grupo my-4">
                        <span id="error"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger tooltipA" data-bs-dismiss="modal" data-tooltip="Cerrar"><i
                                class="fas fa-times-circle fs-5"></i></button>

                        <button type="submit" id="btn" class="btn btn-success tooltipA" id="guardar" data-tooltip="Guardar"><i
                                class="fas fa-save fs-5"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
