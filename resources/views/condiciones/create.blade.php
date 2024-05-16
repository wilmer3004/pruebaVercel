<div class="modal fade" id="CreateConModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar Condición</h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formulario">
                    @csrf

                    <div class="row">

                        <div class="py-2 col-6 input-grupo" id="grupo__nombre">
                            <label for="nombre" class="col-form-label fw-bold input-grupo">Condición</label>
                            <input name="nombre" type="text" class="form-control formulario__input" id="nombre"
                                value="{{ old('name') }}" onkeyup="validateName()" onblur="validateName()">
                            <span id="name-error"></span>
                            @error('name')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                        <div class="col-6 py-2 input-grupo" id="grupo__descripcion">
                            <label for="descripcion" class="col-form-label fw-bold input-grupo">Descripción</label>
                            <textarea class="form-control" name="descripcion" placeholder="Agregar descripción" id="descripcion" type="text"
                                onkeyup="validateDescripcion()" onblur="validateDescripcion()">{{ old('descripcion') }}</textarea>
                            <span id="descripcion-error"></span>
                            @error('descripcion')
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

                        <button type="submit" id="btn" class="btn btn-success tooltipA" id="guardar" data-tooltip="Agregar"><i
                                class="fas fa-save fs-5"></i></button>
                    </div>
                    <center>
                        <span id="submit-error"></span>
                    </center>
                </form>
            </div>
        </div>
    </div>
</div>
