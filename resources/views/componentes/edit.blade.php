<div class="modal fade" id="EditComponenteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Componente</h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formularioE">
                    @csrf

                    <input type="hidden" name="id" id="id" value="">

                    {{-- Grupo nombre --}}
                    <div class="row">

                        <div class="py-2 col-mb-6 col-12 input-grupo" id="">
                            <label for="name" class="col-form-label fw-bold">Nombre del Componente</label>
                            <input name="nombre" type="text" class="form-control formulario__input" id="nombreE"
                                onkeyup="validateName()" onblur="validateName()">
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
                            <select name="trimestre" id="trimestreE" class="form-select"
                                aria-label="form-select-sm example" value="{{ old('trimestre') }}" required>
                                @foreach ($trimestre as $trimestre)
                                    <option value="{{ $trimestre->id }}">
                                        {{ $trimestre->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('trimestre')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                        <div class="col-6 col-md-6 py-2">
                            <label for="tipo" class="col-form-label fw-bold">Tipo de Componente</label>
                            <select name="tipo" id="tipoE" class="form-select"
                                aria-label=".form-select-sm example" value="" required>
                                @foreach ($tipocomponente as $tipo)
                                    <option value="{{ $tipo->id }}">
                                        {{ $tipo->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('coordinacion')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>
                    </div>

                    <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                        <label for="totalh" class="col-form-label fw-bold">Total Horas</label>
                        <input name="totalh" type="number" class="form-control formulario__input" id="totalE"
                            onkeyup="validateName()" onblur="validateName()" >
                        <span id="name-error"></span>
                        @error('totalh')
                            <p style="color: red">*{{ $message }}</p>
                            <br>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-6 col-md-6 py-2">
                            <label for="programa" class="col-form-label fw-bold">Programa</label>
                            <br>
                            <select name="programa" id="programaE" class="form-select" aria-label=".form-select-sm example" multiple required style="width:  150%">
                                @foreach ($programaas as $program)
                                    <option value="{{ $program->id }}">
                                        {{ $program->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('programa')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                    </div>

                    <div class="row">
                        <div class=" py-2 col-12 input-grupo" id="">
                            <label for="nombre" class="col-form-label fw-bold">Descripción</label>
                            <textarea class="form-control" name="descripcion" placeholder="Agregar descripción" id="descripcionE"
                                onkeyup="validateDescripcion()" onblur="validateDescripcion()"></textarea>
                            <span id="descripcion-error"></span>
                            @error('descripcion')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>
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
