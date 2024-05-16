<div class="modal fade" id="EditTipopModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Tipo de Programa</h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formularioE">
                    @csrf

                    <input type="hidden" name="id" id="id">

                    <div class="py-2 col-6 input-grupo" id="">
                        <label for="nombre" class="col-form-label fw-bold">Nombre</label>
                        <input name="nombre" type="text" class="form-control" id="nombreE"
                            onkeyup="validateName()" onblur="validateName()">
                        <span id="name-error"></span>
                        @error('name')
                            <p style="color: red">*{{ $message }}</p>
                            <br>
                        @enderror
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
