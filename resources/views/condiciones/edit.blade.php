<div class="modal fade" id="EditConModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Condición</h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formularioE">
                    @csrf

                    <input type="hidden" name="id" id="id">

                    {{-- <div class="row"> --}}
                        <div class="input-grupo" id="grupo__nombre">
                            <label for="nombre" class="col-form-label fw-bold input-grupo">Condición</label>
                            <input name="nombre" type="text" class="form-control formulario__input" id="nombreE"
                                onkeyup="validateNameE()" onblur="validateNameE()">
                            <span id="name-errorE"></span>
                            @error('nombre')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                        <div class="input-grupo" id="grupo__descripcion">
                            <label for="nombre" class="col-form-label fw-bold input-grupo">Descripción</label>
                            <textarea class="form-control" name="descripcion" placeholder="Agregar descripción" id="descripcionE" type="text"
                                onkeyup="validateDescripcionE()" onblur="validateDescripcionE()"></textarea>
                            <span id="descripcion-errorE"></span>
                            @error('descripcion')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>
                    {{-- </div> --}}
                    <div class="input-grupo">
                        <span class="mt-4" id="submit-errorE"></span>
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
