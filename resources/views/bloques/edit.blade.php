<div class="modal fade" id="EditBloquesModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Bloques</h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formularioE">
                    @csrf

                    <input type="hidden" name="id" id="id">

                    {{-- Grupo nombre --}}
                    <div class="row">
                        <div class="col-6 col-md-6 py-2">
                            <label for="jornada" class="col-form-label fw-bold">Jornada</label>
                            <select name="jornada" id="jornadaE" class="form-select"
                                aria-label="form-select-sm example" value="{{ old('jornada') }}" required>
                                @foreach ($jornadas as $jornada)
                                    <option value="{{ $jornada->id }}">
                                        {{ $jornada->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jornada')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="hora_inicio" class="col-form-label fw-bold">Hora Inicio</label>
                            <input name="hora_inicio" type="time" class="form-control formulario__input" id="hora_inicioE"
                                onkeyup="validateHora_ini()" onblur="validateHora_ini()">
                            <span id="piso-error"></span>
                            @error('piso')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="hora_fin" class="col-form-label fw-bold">Hora Fin</label>
                            <input name="hora_fin" type="time" class="form-control formulario__input" id="hora_finE"
                                onkeyup="validateHora_fin()" onblur="validateHora_fin()">
                            <span id="hora_fin-error"></span>
                            @error('hora_fin')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>
                    </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger tooltipA" data-bs-dismiss="modal" data-tooltip="Cerrar"><i
                        class="fas fa-times-circle fs-5"></i></button>

                <button type="submit" id="btn" class="btn btn-warning tooltipA" id="guardar"
                    data-tooltip="Actualizar"><i class="fas fa-edit fs-5"></i></button>
            </div>
            </form>
        </div>
    </div>
</div>
</div>
