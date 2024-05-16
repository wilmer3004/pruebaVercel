<div class="modal fade" id="EditJornadaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Jornada</h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formularioE">
                    @csrf

                    {{-- Nombre de la jornada --}}
                    <input type="hidden" name="id" id="id">

                    <div class="container px-4 text-start">
                        <div class="row">
                            <div class="col input-grupo" id="">
                                <label for="nombre" class="col-form-label fw-bold">Nombre</label>
                                <input name="nombre" type="text" class="form-control form-control-colo"
                                    id="nombreE" onkeyup="validateName()" onblur="validateName()">
                                <span id="name-error"></span>
                                @error('name')
                                    <p style="color: red">*{{ $message }}</p>
                                    <br>
                                @enderror
                            </div>

                            <div class="col input-grupo">
                                <label for="actualColor" class="col-form-label fw-bold me-5">Color Actual</label>
                                <button type="button" class="color-preview tooltipA" data-tooltip="" id="color-actualPreview" disabled></button>
                            </div>
                        </div>
                    </div>

                    {{-- Caracterisitcas de la jornada --}}
                    <div class="row" id="grupo__color">

                        {{-- Color --}}

                        <div class="py-2 col-mb-1 col-4 colorData">
                            <label for="colorE" class="col-form-label fw-bold formulario__label">Color base</label>
                            <input id="colorE" class="form-control-color" type="color" value="#DDE1E9"
                                onchange="updateColor(this.value)">
                        </div>

                        {{-- Opacidad --}}

                        <div class="py-2 col-mb-1 col-4 colorData">
                            <label for="opacityE" class="col-form-label fw-bold formulario__label">Opacidad</label>
                            <input id="opacityE" type="range" min="0.5" max="1" step="0.01"
                                value="1" onchange="updateOpacity(this.value)">

                        </div>

                        {{-- Resultado --}}

                        <div class="py-2 col-mb-1 col-4 colorData">
                            <label for="color-preview"
                                class="col-form-label fw-bold formulario__label">Resultado</label>
                            <div id="color-previewE" class="color-preview"></div>
                        </div>
                        <span id="color-error"></span>
                        @error('color')
                            <p style="color: red">*{{ $message }}</p>
                            <br>
                        @enderror
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
