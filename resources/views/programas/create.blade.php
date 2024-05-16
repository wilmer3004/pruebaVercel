<div class="modal fade" id="CreateProgramaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Registrar Programa</h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formulario">
                    @csrf

                    {{-- Grupo nombre --}}
                    <div class="row">

                        <div class="py-2 col-mb-6 col-12 input-grupo" id="">
                            <label for="nombre" class="col-form-label fw-bold">Nombre Programa</label>
                            <input name="nombre" type="text" class="form-control formulario__input" id="nombre"
                                value="{{ old('name') }}" onkeyup="validateName()" onblur="validateName()">
                            <span id="name-error"></span>
                            @error('name')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-6 col-md-6 py-2">
                            <label for="tipo" class="col-form-label fw-bold">Tipo Programa</label>
                            <select name="tipo" id="tipo" class="form-select"
                                aria-label="form-select-sm example" value="{{ old('tipo') }}" onchange=validateTipoPrograma() required>
                                <option disabled selected>Seleccione un tipo de programa...</option>
                                @foreach ($tiposprograma as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->name }}</option>
                                @endforeach
                            </select>
                            <span id='tipo-error'></span>
                            @error('coordinacion')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                        <div class="col-6 col-md-6 py-2">
                            <label for="coordinacion" class="col-form-label fw-bold">Coordinacion</label>
                            <select name="coordinacion" id="coordinacion" class="form-select"
                                aria-label="form-select-sm example" value="{{ old('coordinacion') }}" required>
                                <option disabled selected>Seleccione una coordinacion...</option>
                                @foreach ($coordinaciones as $coordinacion)
                                    <option value="{{ $coordinacion->id }}">{{ $coordinacion->name }}</option>
                                @endforeach
                            </select>
                            @error('tipo')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>
                    </div>

                    {{-- grupo descripcion --}}
                    <div class="row">
                        <div class=" py-2 col-12 input-grupo" id="">
                            <label for="nombre" class="col-form-label fw-bold">Descripción</label>
                            <textarea class="form-control" name="descripcion" placeholder="Agregar descripción" id="descripcion"
                                onkeyup="validateDescripcion()" onblur="validateDescripcion()">{{ old('descripcion') }}</textarea>
                            <span id="descripcion-error"></span>
                            @error('descripcion')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>
                    </div>
                    <div class="row" id="grupo__color">
                        <div class="py-2 col-mb-1 col-4 colorData">
                            <label for="color" class="col-form-label fw-bold formulario__label">Color</label>
                                <input id="color" type="color" value="#358BE0" onchange="updateColor(this.value)">
                        </div>
                        <div class="py-2 col-mb-1 col-4 colorData">
                            <label for="opacity" class="col-form-label fw-bold formulario__label">Opacidad</label>
                            <input id="opacity" type="range" min="0" max="1" step="0.01" value="1" onchange="updateOpacity(this.value)">
 
                        </div>
                        <div class="py-2 col-mb-1 col-4 colorData">
                            <label for="color-preview" class="col-form-label fw-bold formulario__label">Resultado</label>
                            <div id="color-preview" class="color-preview"></div>
                        </div>
                        <span id="color-error"></span>          
                        @error('color')
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
