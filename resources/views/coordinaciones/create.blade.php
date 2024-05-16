<div class="modal fade" id="CreateCoordinacionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar Coordinación</h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formulario">
                    @csrf
                    <div class="row">
                        <div class="col-6 py-2 input-grupo" id="grupo__nombre">
                            <label for="nombre" class="col-form-label fw-bold formulario__label">Coordinación</label>
                                <input name="nombre" type="text" class="form-control formulario__input" id="nombre"
                                    value="" onkeyup="validateName()" onblur="validateName()">
                                    <span id="name-error"></span>
                            @error('name')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>
                        <div class="col-6 py-2 input-grupo" id="grupo__nombre">
                            <label for="color" class="col-form-label fw-bold formulario__label">Color</label>
                                <input name="color" type="color" style='height:38px' class="form-control formulario__input" id="color"
                                    value="" onkeyup="validateColor()" onblur="validateColor()">
                                    <span id="color-error"></span>
                            @error('color')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="tipoTecnica" class="col-form-label fw-bold">¿Quieres que sea multi tecnica?</label> <br>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1" id="tipoTec1" name="tipoTec" >
                            <label class="form-check-label" for="tipoTec1">Si</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="0" id="tipoTec0" name="tipoTec" checked>
                            <label class="form-check-label" for="tipoTec0">No</label>
                        </div>


                    </div>

                    <div class="input-grupo my-4">
                        <span id="error"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger tooltipA" data-bs-dismiss="modal" data-tooltip="Cerrar"><i
                                class="fas fa-times-circle fs-5"></i></button>
                        <button type="submit" id="btn" class="btn btn-success tooltipA" id="guardar" data-tooltip="Agregar"><i
                                class="fas fa-save fs-5"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
