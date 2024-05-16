<div class="modal fade" id="EditTrimestreAniolModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Trimestre Año</h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formularioE">
                    @csrf

                    <input type="hidden" name="id" id="id">

                    <div class="row">
                        {{-- Selección de año --}}
                        <div class=" py-2 col-6 input-grupo" id="">
                            <label for="year" class="col-form-label fw-bold">Año</label>
                            <select name="year" type="date" class="form-control" id="selectYearE" 
                            value="{{ old('year') }}"/>
                                <option id="yearE"></option>
                            </select>
                            <span id="year-errorE"></span>
                            @error('year')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>
                        {{-- Seleccion trimestre --}}
                        <div class="col-6 py-2 input-grupo" id="grupo__nombre">
                            <label for="quarter" class="col-form-label fw-bold input-grupo">Número de Trimestre</label>
                            <input name="quarter" type="number" class="form-control formulario__input" id="quarterE"
                                value="{{ old('quarter') }}" onkeyup="validateQuarterE()" onblur="validateQuarterE()" />
                            <span id="quarter-errorE"></span>
                            @error('quarter')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>
                    </div>

                    <div class="row">

                        {{-- Seleccion fecha inicio --}}
                        <div class=" py-2 col-6 input-grupo" id="">
                            <label for="start" class="col-form-label fw-bold">Fecha Inicio</label>
                            <input name="start" type="date" class="form-control" id="startE">
                            <span id="start-errorE"></span>
                            @error('start')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                        {{-- Seleccion fecha final --}}
                        <div class=" py-2 col-6 input-grupo" id="">
                            <label for="end" class="col-form-label fw-bold">Fecha Final</label>
                            <input name="end" type="date" class="form-control" id="endE" onkeyup="validateQuarter()" onblur="validateQuarter()">
                            <span id="end-errorE"></span>
                            @error('end')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>
                    </div>
                    <span id="error-fechasE">
                        @error('error-fechasE')
                            <p style="color: red">*{{ $message }}</p>
                            <br>
                        @enderror
                    </span>

                    <div class="input-grupo my-4">
                        <span id="errorE"></span>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger tooltipA" data-bs-dismiss="modal"
                            data-tooltip="Cerrar"><i class="fas fa-times-circle fs-5"></i></button>

                        <button type="submit" id="btn" class="btn btn-warning tooltipA" id="guardar"
                            data-tooltip="Actualizar"><i class="fas fa-edit fs-5"></i></button>
                    </div>
                    <center>
                        <span id="submit-error"></span>
                    </center>
                </form>
            </div>
        </div>
    </div>
</div>
