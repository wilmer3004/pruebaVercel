<div class="modal fade" id="EditSedeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Sede</h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formularioE">
                    @csrf

                    <input type="hidden" id="id" name="id">

                    <div class="row">

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="nombre" class="col-form-label fw-bold">Nombre</label>
                            <input name="nombre" type="text" class="form-control" id="nombreE"
                                onkeyup="validateNameE()" onblur="validateNameE()">
                            <span id="name-errorE"></span>
                            @error('name')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="ambientes" class="col-form-label fw-bold">Capacidad de ambientes</label>
                            <input name="ambientes" type="number" class="form-control" id="ambienteE"
                                onkeyup="validateAmbientesE()" onblur="validateAmbientesE()">
                            <span id="ambientes-errorE"></span>
                            @error('ambientes')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>


                    </div>

                    <div class="row">

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="direccion" class="col-form-label fw-bold">Dirección</label>
                            <input name="direccion" type="text" class="form-control" id="direccionE"
                                onkeyup="validateDireccionE()" onblur="validateDireccionE()">
                            <span id="direccion-errorE"></span>
                            @error('direccion')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>
                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="pisosE" class="col-form-label fw-bold">Pisos</label>
                            <input name="pisosE" type="number" class="form-control" id="pisosE"
                                value="{{ old('pisosE') }}" onkeyup="validatePisosE()" onblur="validatePisosE()">
                            <span id="pisos-errorE"></span>
                            @error('pisos')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                    </div>
                    <div class="input-grupo my-4">
                        <span id="errorE"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger tooltipA" data-bs-dismiss="modal"
                            data-tooltip="Cerrar"><i class="fas fa-times-circle fs-5"></i></button>

                        <button type="submit" id="btn" class="btn btn-warning tooltipA"
                            data-tooltip="Actualizar"><i class="fas fa-edit fs-5"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
