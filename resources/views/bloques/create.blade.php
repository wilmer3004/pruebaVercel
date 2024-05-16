<div class="modal fade" id="CreateBloquesModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Registrar Ambiente</h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formulario">
                    @csrf

                    {{-- Grupo nombre --}}
                    <div class="row">

                        <div class="col-6 col-md-6 py-2">
                            <label for="jornada" class="col-form-label fw-bold">Jornada</label>
                            <select name="jornada" id="jornada" class="form-select"
                                aria-label="form-select-sm example" value="" onkeyup="validateJornada()"
                                onblur="validateJornada()">
                                <option disabled selected>Seleccione una jornada...</option>
                                <span id="jornada-error"></span>
                                @foreach ($jornadas as $jornada)
                                    <option value="{{ old('jornada', $jornada->id) }}">{{ $jornada->name }}</option>
                                @endforeach
                            </select>
                            @error('sede')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                    </div>

                    <div class="row">

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="hora_inicio" class="col-form-label fw-bold">Hora Inicio</label>
                            <input name="hora_inicio" type="time" class="form-control formulario__input"
                                id="hora_inicio">
                            <span id="hora_inicio-error"></span>
                        </div>

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="hora_fin" class="col-form-label fw-bold">Hora Fin</label>
                            <input name="hora_fin" type="time" class="form-control formulario__input" id="hora_fin">
                            <span id="hora_fin-error"></span>
                        </div>
                    </div>

                    <div class="input-grupo my-4">
                        <span id="error"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger tooltipA" data-bs-dismiss="modal"
                            data-tooltip="Cerrar"><i class="fas fa-times-circle fs-5"></i></button>

                        <button type="submit" id="btn" class="btn btn-success tooltipA" id="guardar"
                            data-tooltip="Guardar"><i class="fas fa-save fs-5"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
