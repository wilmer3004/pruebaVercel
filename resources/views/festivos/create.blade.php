<div class="modal fade" id="CreateFestivoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            {{-- TITLE --}}
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar Fecha Festivo</h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- BODY FORM --}}
            <div class="modal-body">
                {{-- FORNM --}}
                <form method="POST" id="formulario">
                    @csrf
                    
                    {{-- Date --}}
                    <label for="dayHoliday">Fecha </label>
                    <input type="date" id="dayHoliday" class="input-group-text">
                    
                    {{-- ERROR SPAN --}}
                    <div class="input-grupo my-1">
                        <span id="error" class="input-grupo"></span>
                    </div>

                    {{-- BUTTON --}}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger tooltipA" data-bs-dismiss="modal" data-tooltip="Cerrar"><i
                                class="fas fa-times-circle fs-5"></i></button>
                        <button type="submit" id="btn" class="btn btn-success tooltipA" id="guardar" data-tooltip="Agregar"><i
                                class="fas fa-save fs-5"></i></button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
