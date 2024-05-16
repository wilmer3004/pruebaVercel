
<div class="modal fade" id="CreateComponenteModal" tabindex="1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Registrar Componente</h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formulario">
                    @csrf

                    {{-- Grupo nombre --}}
                    <div class="row">

                        <div class="py-2 col-mb-6 col-12 input-grupo" id="">
                            <label for="name" class="col-form-label fw-bold">Nombre del Componente</label>
                            <input name="nombre" type="text" class="form-control formulario__input" id="name"
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
                            <label for="trimestre" class="col-form-label fw-bold">Trimestre</label>
                            <select name="trimestre" id="" class="form-select"
                                aria-label="form-select-sm example" value="{{ old('trimestre') }}" required>
                                <option disabled selected>Seleccione un trimestre...</option>
                                @foreach ($trimestre as $trimestre)
                                    <option value="{{ old('trimestre', $trimestre->id) }}">{{ $trimestre->name }}</option>
                                @endforeach
                            </select>
                            @error('trimestre')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>


                        <div class="col-6 col-md-6 py-2">
                            <label for="tipo" class="col-form-label fw-bold">Tipo de Componente</label>
                            <select name="tipo" id="component_type" class="form-select"
                                aria-label="form-select-sm example" required>
                                <option disabled selected>Seleccione un tipo de componente...</option>
                                @foreach ($tipocomponente as $tipo)
                                    <option value="{{ old('tipo', $tipo->id) }}">{{ $tipo->name }}</option>
                                @endforeach
                            </select>
                            @error('tipo')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 col-md-6 py-2">
                            <label for="programa" class="col-form-label fw-bold">Programa</label><br>
                            <select name="programas" id="programas" style="width: 100%" multiple required>
                                <option disabled>Seleccione un programa...</option>
                                @foreach ($programa as $program)
                                    <option value="{{ $program->id }}">{{ $program->name }}</option>
                                @endforeach
                            </select>
                            @error('programas')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="horas" class="col-form-label fw-bold">Total de Horas</label>
                            <input name="horas" type="number" class="form-control" id="horas"
                                value="{{ old('horas') }}" onkeyup="validateHoras()" onblur="validateHoras()">
                            <span id="horas-error"></span>
                            @error('horas')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                    </div>

                    <div class="row">
                        <div class=" py-2 col-12 input-grupo" id="">
                            <label for="descripcion" class="col-form-label fw-bold">Descripción</label>
                            <textarea class="form-control" name="descripcion" placeholder="Agregar descripción" id="descripcion"
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

                        <button type="submit" id="btn" class="btn btn-success tooltipA" id="guardar" data-tooltip="Guardar"><i
                                class="fas fa-save fs-5"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
