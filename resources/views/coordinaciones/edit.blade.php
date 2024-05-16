<div class="modal fade" id="editCoordinacionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Coordinación</h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formularioE">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="id" id="id">
                        <div class="col-6 py-2 input-grupo" id="grupo__nombre">
                            <label for="nombre" class="col-form-label fw-bold formulario__label">Coodinación</label>
                            <input name="nombre" type="text" class="form-control formulario__input" id="nombreE"
                                onkeyup="validateNameE()" onblur="validateNameE()">
                            <span id="name-errorE"></span>
                            @error('nombre')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror

                        </div>
                        <div class="col-6 py-2 input-grupo" id="grupo__color">
                            <label for="color" class="col-form-label fw-bold formulario__label">Color</label>
                            <input name="color" type="color" style='height:38px' class="form-control formulario__input" id="colorE"
                                onkeyup="validateColorE()" onblur="validateColorE()">
                            <span id="color-errorE"></span>
                            @error('color')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                        <div>
                            <label for="tipoTecnica" class="col-form-label fw-bold">¿Quieres que sea multi tecnica?</label> <br>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="1" id="tipoTec1" name="tipoTec" >
                                <label class="form-check-label" for="tipoTec1">Si</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="0" id="tipoTec0" name="tipoTec" >
                                <label class="form-check-label" for="tipoTec0">No</label>
                            </div>
                        </div>

                    </div>

                    <div class="input-grupo my-4">
                        <span id="errorE"></span>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger tooltipA" data-bs-dismiss="modal"
                            data-tooltip="Cerrar"><i class="fas fa-times-circle fs-5"></i></button>

                        <button type="submit" id="btn" class="btn btn-warning tooltipA" id="guardar"
                            data-tooltip="Actualizar"><i class="fas fa-edit fs-5"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
