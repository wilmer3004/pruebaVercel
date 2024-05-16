<div class="modal fade" id="EditCondicionHoraModal" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Condición Hora</h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="formularioE">
                    @csrf

                    <input type="hidden" name="id" id="id">

                    {{-- Grupo nombre --}}
                    <div class="row">
                        <div class="col-6 col-md-6 py-2">
                            <label for="contrato" class="col-form-label fw-bold">Contrato</label>
                            <select name="contrato" id="contratoE" class="form-select"
                                    aria-label="form-select-sm example" value="{{ old('contrato') }}" required
                                    onchange="validateContractE()" onblur="validateContractE()">
                                @foreach ($contratos as $contratos)
                                    <option value="{{ $contratos->id }}">
                                        {{ $contratos->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="input-grupo">
                                <span id="contrato-errorE"></span>
                            </div>
                            @error('contrato')
                            <p style="color: red">*{{ $message }}</p>
                            <br>
                            @enderror
                        </div>

                        <div class="col-6 col-md-6 py-2">
                            <label for="condicion" class="col-form-label fw-bold">Condición</label>
                            <select name="condicion" id="condicionE" class="form-select"
                                    aria-label="form-select-sm example" value="{{ old('condicion') }}" required
                                    onchange="validateConditionE()" onblur="validateConditionE()">
                                @foreach ($condiciones as $condiciones)
                                    <option value="{{ $condiciones->id }}">
                                        {{ $condiciones->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="input-grupo">
                                <span id="condicion-errorE"></span>
                            </div>
                            @error('tipo')
                            <p style="color: red">*{{ $message }}</p>
                            <br>
                            @enderror
                        </div>

                    </div>

                    <div class="row">
                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="porcentajeE" class="col-form-label fw-bold">Porcentaje</label>
                            <input name="porcentajeE" type="number" class="form-control formulario__input"
                                   id="porcentajeE"
                                   onkeyup="validatePercentajeE()" onblur="validatePercentajeE()">
                            <div class="input-grupo">
                                <span id="percentaje-errorE"></span>
                            </div>
                            @error('porcentaje')
                            <p style="color: red">*{{ $message }}</p>
                            <br>
                            @enderror
                        </div>
                    </div>

                    <div class="input-grupo my-4">
                        <span id="errorE"></span>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger tooltipA" data-bs-dismiss="modal"
                                data-tooltip="Cerrar"><i
                                class="fas fa-times-circle fs-5"></i></button>

                        <button type="submit" id="btn" class="btn btn-warning tooltipA" id="guardar"
                                data-tooltip="Actualizar"><i class="fas fa-edit fs-5"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
