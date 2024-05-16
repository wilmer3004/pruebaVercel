<div class="modal fade" id="CreateTrimestreAniolModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar Trimestre Año</h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formulario">
                    @csrf

                    <div class="row">
                        <div class=" py-2 col-6 input-grupo" id="">
                            <label for="year" class="col-form-label fw-bold">Año</label>
                            <select name="year" type="date" class="form-control" id="year" 
                            value="{{ old('year') }}">
                                <option disable selected>Seleccione un año</option>
                            </select>
                            <span id="year-error"></span>
                            @error('year')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                        <div class="py-2 col-6 input-grupo" id="grupo__nombre">
                            <label for="quarter" class="col-form-label fw-bold input-grupo">Número de Trimestre</label>
                            <input name="quarter" type="number" class="form-control formulario__input" id="quarter"
                                value="{{ old('quarter') }}" onkeyup="validateQuarter()" onblur="validateQuarter()">
                            <span id="quarter-error"></span>
                            @error('name')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class=" py-2 col-6 input-grupo" id="">
                            <label for="start" class="col-form-label fw-bold">Fecha Inicio</label>
                            <input name="start" type="date" class="form-control" id="start" readonly>
                            <span id="start-error"></span>
                            @error('start')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                        <div class=" py-2 col-6 input-grupo" id="">
                            <label for="end" class="col-form-label fw-bold">Fecha Final</label>
                            <input name="end" type="date" class="form-control" id="end" readonly
                                value="{{ old('end') }}">
                            <span id="end-error"></span>
                            @error('end')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>
                    </div>
                    <span id="error-fechas">
                        @error('error-fechas')
                            <p style="color: red">*{{ $message }}</p>
                            <br>
                        @enderror
                    </span>

                    <div class="input-grupo my-4">
                        <span id="error"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger tooltipA" data-bs-dismiss="modal"
                            data-tooltip="Cerrar"><i class="fas fa-times-circle fs-5"></i></button>

                        <button type="submit" id="btn" class="btn btn-success tooltipA" id="guardar"
                            data-tooltip="Agregar"><i class="fas fa-save fs-5"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
