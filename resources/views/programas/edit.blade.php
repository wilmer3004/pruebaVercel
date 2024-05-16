<div class="modal fade" id="EditProgramaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Programa</h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formularioE">
                    @csrf
                    {{-- @method('PUT') --}}

                    {{-- Grupo nombre --}}
                    <input type="hidden" name="id" id="id">
                    <div class="row">
                        <div class="py-2 col-mb-6 col-12 input-grupo" id="">
                            <label for="nombre" class="col-form-label fw-bold">Nombre Programa</label>
                            <input name="nombre" type="text" class="form-control formulario__input" id="nombreE"
                                value="" onkeyup="validateNameE()" onblur="validateNameE()">
                            <span id="name-errorE"></span>
                            @error('nombre')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-6 col-md-6 py-2">
                            <label for="tipo" class="col-form-label fw-bold">Tipo Programa</label>
                            <select name="tipo" class="form-select" aria-label="form-select-sm example" required
                                id="tipoE">
                                @foreach ($tiposprograma as $tipo)
                                    <option value="{{ $tipo->id }}">
                                        {{ $tipo->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span id="tipoPrograma-errorE"></span>
                            @error('tipo')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                        <div class="col-6 col-md-6 py-2">
                            <label for="coordinacion" class="col-form-label fw-bold">Coordinacion</label>
                            <select name="coordinacion" class="form-select" aria-label=".form-select-sm example"
                                value="{{ old('coordinacion') }}" required id="coordinacionE">
                                @foreach ($coordinaciones as $coordinacion)
                                    <option value="{{ $coordinacion->id }}">
                                        {{ $coordinacion->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span id="coordinacion-errorE"></span>
                            @error('coordinacion')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>
                    </div>

                    {{-- grupo descripcion --}}
                    <div class="row">
                        <div class=" py-2 col-12 input-grupo" id="">
                            <label for="nombre" class="col-form-label fw-bold">Descripción</label>
                            <textarea class="form-control" name="descripcion" placeholder="Agregar descripción" id="descripcionE"
                                onkeyup="validateDescriptionE()" onblur="validateDescriptionE()">{{ old('descripcion') }}</textarea>
                            {{-- <i class="formulario__validacion-estado fas fa-times-circle"></i> --}}
                            <span id="descripcion-errorE"></span>
                            @error('descripcion')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>
                    </div>
                    <div class="row" id="grupo__color">
                        <div class="py-2 col-mb-1 col-4 colorData">
                            <label for="colorE" class="col-form-label fw-bold formulario__label">Color</label>
                            <input id="colorE" type="color" value="#FFFFFF" onchange="updateColorE(this.value)">
                        </div>
                        <div class="py-2 col-mb-1 col-4 colorData">
                            <label for="opacityE" class="col-form-label fw-bold formulario__label">Opacidad</label>
                            <input id="opacityE" type="range" min="0.5" max="1" step="0.01"
                                value="1" onchange="updateOpacity(this.value)">
                        </div>
                        <div class="py-2 col-mb-1 col-4 colorData">
                            <label for="color-previewE"
                                class="col-form-label fw-bold formulario__label">Resultado</label>
                            <div id="color-previewE" class="color-preview"></div>
                        </div>
                        <span id="color-errorE"></span>
                        @error('color')
                            <p style="color: red">*{{ $message }}</p>
                            <br>
                        @enderror
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
