<div class="modal fade" id="EditEventoModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Evento</h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="">
                    @csrf
                
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="idAmbiente" name="idAmbiente">
                    <input type="hidden" id="idFicha" name="idFicha">
                    <input type="hidden" id="idComponent" name="idComponent">
                    <input type="hidden" id="fechaInicio" name="fechaInicio">
                    <input type="hidden" id="fechaFin" name="fechaFin">

                    {{-- Grupo nombre --}}
                    <div class="row">


                        <div class="py-2 col-mb-12 col-12 input-grupo" id="">
                            <h5 class="mt-4">Seleccione un instructor</h5>
                            <div class="col-12 col-md-12 mt-4">
                                <label class="form-label fw-bold">Instructor</label><br>
                                <select  class="select3" name="instructores" id="instructores">
                                    <option  selected>Seleccione un instructor</option>
                                    <option value="null">Instructor en contratación</option>
                                </select>
        
                            </div>
                            <span id="name-error"></span>
                            @error('instructor')
                                <p style="color: red">*{{ $message }}</p>
                                <br>
                            @enderror
                        </div>

                    </div>

                    

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger tooltipA" data-bs-dismiss="modal" data-tooltip="Cerrar"><i
                                class="fas fa-times-circle fs-5"></i></button>

                        <button type="button" id="btnEdit" class="btn btn-warning tooltipA" onclick='editarData()'><i
                                class="fas fa-edit fs-5"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
