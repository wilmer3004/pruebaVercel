<div class="modal fade" id="editHorasModal{{$hora->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Hora</h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('horas.update', $hora) }}" method="POST" id="formulario">

                    @csrf
                    @method('PUT')

                    <div class="row">

                        <div class="col-6 col-md-6 py-2 input-grupo">
                            <label for="contrato" class="col-form-label fw-bold">Tipo de Contrato</label>
                            <select name="contrato" id="contrato" class="form-select"
                                aria-label=".form-select-sm example" value="{{ old('contrato') }}" required>
                                <option disabled selected>Seleccione un contrato...</option>
                                @foreach ($contratos as $contrato)
                                    <option value="{{ $contrato->id }}"
                                        {{ $hora->contract_id == $contrato->id ? 'selected' : '' }}>
                                        {{ $contrato->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('contrato')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                    </div>

                    <div class="row">

                        {{-- grupo documento --}}
                        <div class="col-6 col-md-6 py-2 input-grupo" id="">
                            <label for="horasdiamin" class="col-form-label fw-bold">Horas diarias
                                mínimas</label>
                            <input name="horasdiamin" type="number" class="form-control formulario__input"
                                id="horasdiamin" value="{{ old('horasdiamin', $hora->dh_min) }}"
                                placeholder="Ingrese las horas diarias mínimas que debe laborar este tipo de contrato"
                                onkeyup="validateHorasDiamin()" onblur="validateHorasDiamin()">
                                <span id="horasdiamin-error"></span> 
                            @error('horasdiamin')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                        {{-- grupo documento --}}
                        <div class="col-6 col-md-6 py-2 input-grupo" id="">
                            <label for="horasdiamax" class="col-form-label fw-bold">Horas diarias
                                máximas</label>
                            <input name="horasdiamax" type="number" class="form-control formulario__input"
                                id="horasdiamax" value="{{ old('horasdiamax', $hora->dh_max) }}"
                                placeholder="Ingrese las horas diarias máximas que debe laborar este tipo de contrato"
                                onkeyup="validateHorasDiamax()" onblur="validateHorasDiamax()">
                                <span id="horasdiamax-error"></span> 
                            @error('horasdiamax')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>


                        <div class="row">

                            {{-- grupo documento --}}
                            <div class="col-6 col-md-6 py-2 input-grupo" id="">
                                <label for="horasmenmin" class="col-form-label fw-bold">Horas
                                    mensulaes
                                    mínimas</label>
                                <input name="horasmenmin" type="number" class="form-control" id="horasmenmin"
                                    value="{{ old('horasmenmin', $hora->mh_min) }}"
                                    placeholder="Ingrese las horas diarias máximas que debe laborar este tipo de contrato"
                                    onkeyup="validateHorasMenMin()" onblur="validateHorasMenMin()">
                                    <span id="horasmenmin-error"></span> 
                                @error('horasmenmin')
                                    <p style="color: red">*{{ $message }}</p>
                                    <br>
                                @enderror
                            </div>

                            {{-- grupo documento --}}
                            <div class="col-6 col-md-6 py-2 input-grupo" id="">
                                <label for="horasmenmax" class="col-form-label fw-bold formulario__label">Horas
                                    mensulaes
                                    máximas</label>
                                <input name="horasmenmax" type="number" class="form-control" id="horasmenmax"
                                    value="{{ old('horasmenmax', $hora->mh_max) }}"
                                    placeholder="Ingrese las horas diarias máximas que debe laborar este tipo de contrato"
                                    onkeyup="validateHorasMenMax()" onblur="validateHorasMenMax()">
                                    <span id="horasmenmax-error"></span> 
                                @error('horasmenmax')
                                    <p style="color: red">*{{ $message }}</p>
                                    <br>
                                @enderror

                            </div>

                        </div>


                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger tooltipA" data-bs-dismiss="modal" data-tooltip="Cerrar"><i
                                    class="fas fa-times-circle fs-5"></i></button>

                            <button type="submit" class="btn btn-warning tooltipA" data-tooltip="Actualizar"><i
                                    class="fas fa-edit fs-5"></i></button>
                        </div>

                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
