<div class="modal fade" id="CreateOfertaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar Oferta</h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="GET" id="formulario">
                    @csrf

                    <div class="py-2 col-6 input-grupo" id="">
                        <label for="nombre" class="col-form-label fw-bold">Oferta</label>
                        <input name="nombre" type="text" class="form-control" id="name"
                            value="" onkeyup="validateName()" onblur="validateName()">
                        <span id="name-error"></span>
                        @error('name')
                            <p style="color: red">*{{ $message }}</p>
                            <br>
                        @enderror
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
