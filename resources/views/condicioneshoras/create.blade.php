<div class="modal fade" id="CreateCondicionHoraModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar Condición Hora</h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formulario">
                    @csrf

                    {{-- Grupo nombre --}}
                    <div class="row">

                        <div class="col-6 col-md-6 py-2">
                            <label for="contract" class="col-form-label fw-bold">Contrato</label>
                            <select name="contrato" id="contract" class="form-select"
                                aria-label="form-select-sm example" value="" onchange="validateContract()">
                                <option disabled selected>Seleccione un contrato...</option>
                                @foreach ($contratos as $contratos)
                                    <option value="{{ old('sede', $contratos->id) }}">{{ $contratos->name }}</option>
                                @endforeach
                            </select>
                            <div class="input-grupo">
                                <span id="contract-error"></span>
                            </div>
                            @error('contract')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                        <div class="col-6 col-md-6 py-2">
                            <label for="condition" class="col-form-label fw-bold">Condición</label>
                            <select name="condicion" id="condition" class="form-select"
                                aria-label="form-select-sm example" value="" onchange="validateCondition()"
                                onblur="validateCondition()">
                                <option disabled selected>Seleccione una condición...</option>
                                @foreach ($condiciones as $condiciones)
                                    <option value="{{ old('sede', $condiciones->id) }}">{{ $condiciones->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="input-grupo">
                                <span id="condition-error"></span>
                            </div>
                            @error('condition')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                    </div>

                    <div class="row">

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="percentaje" class="col-form-label fw-bold">Porcentaje</label>
                            <input name="porcentaje" type="number" class="form-control formulario__input"
                                id="percentaje" value="{{ old('percentaje') }}" onkeyup="validatePercentaje()"
                                onblur="validatePercentaje()">
                            <div class="input-grupo">
                                <span id="percentaje-error"></span>
                            </div>
                            @error('percentaje')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
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
