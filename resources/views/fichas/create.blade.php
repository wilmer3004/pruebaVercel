<div class="modal fade" id="CreateFichaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Registrar Ficha</h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formulario">
                    @csrf

                    {{-- Grupo nombre --}}
                    <div class="row">

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="numero" class="col-form-label fw-bold">Número de Ficha</label>
                            <div class="d-flex justify-content-center align-items-center" style="width: 100%">
                                <input name="numero" type="number" class="form-control formulario__input" style="width: 70%;" id="number"
                                value="{{ old('numero') }}" onkeyup="validateNumber()" onblur="validateNumber()">

                                <input name="num" type="num" class="form-control formulario__input " style="width: 25%; margin-left:5%" id="num"
                                value="{{ old('num') }}"  onkeyup="validarNum()" onblur="validarNum()">
                            </div>
                            <span id="number-error"></span>
                            @error('number')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="aprendices" class="col-form-label fw-bold">Número de Aprendices</label>
                            <input name="aprendices" type="number" class="form-control formulario__input" id="aprendices"
                                value="{{ old('aprendices') }}" onkeyup="validateAprendices()" onblur="validateAprendices()">
                            <span id="aprendices-error"></span>
                            @error('aprendices')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-6 col-md-6 py-2">
                            <label for="programa" class="col-form-label fw-bold">Programa</label>
                            <select name="programa" id="" class="form-select"
                                aria-label="form-select-sm example" value="" required>
                                <option disabled selected>Seleccione un programa...</option>
                                @foreach ($programas as $programa)
                                    <option value="{{ $programa->id }}">{{ $programa->name }}</option>
                                @endforeach
                            </select>
                            @error('programa')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                        <div class="col-6 col-md-6 py-2">
                            <label for="jornada" class="col-form-label fw-bold">Jornada</label>
                            <select name="jornada" id="" class="form-select"
                                aria-label="form-select-sm example" value="{{ old('jornada') }}" required>
                                <option disabled selected>Seleccione una jornada...</option>
                                @foreach ($jornadas as $jornada)
                                    <option value="{{ $jornada->id }}">{{ $jornada->name }}</option>
                                @endforeach
                            </select>
                            @error('jornada')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-6 col-md-6 py-2">
                            <label for="oferta" class="col-form-label fw-bold">Oferta</label>
                            <select name="oferta" id="" class="form-select"
                                aria-label="form-select-sm example" value="{{ old('oferta') }}" required>
                                <option disabled selected>Seleccione un oferta...</option>
                                @foreach ($ofertas as $oferta)
                                    <option value="{{ $oferta->id }}">{{ $oferta->name }}</option>
                                @endforeach
                            </select>
                            @error('oferta')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                        <div class="col-6 col-md-6 py-2">
                            <label for="trimestre" class="col-form-label fw-bold">Trimestre</label>
                            <select name="trimestre" id="" class="form-select"
                                aria-label="form-select-sm example" value="{{ old('trimestre') }}" required>
                                <option disabled selected>Seleccione una trimestre...</option>
                                @foreach ($trimestres as $trimestre)
                                    <option value="{{ $trimestre->id }}">{{ $trimestre->name }}</option>
                                @endforeach
                            </select>
                            @error('trimestre')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>
                    </div>

                    <div class="row">

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="inicio" class="col-form-label fw-bold">Inicio de etapa lectiva</label>
                            <input name="inicio" type="date" class="form-control formulario__input"
                                value="{{ old('inicio') }}" required>
                            <span id="name-error"></span>
                            @error('inicio')
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
