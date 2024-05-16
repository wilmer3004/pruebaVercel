<div class="modal fade" id="ShowFichaModal{{ $ficha->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Información de la Ficha</h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="formulario">

                    {{-- Grupo nombre --}}
                    <div class="row">

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="number" class="col-form-label fw-bold">Número de Ficha</label>
                            <input name="number" type="text" class="form-control formulario__input" id="nombre"
                                value="{{ $ficha->number }}" disabled>
                        </div>

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="number" class="col-form-label fw-bold">Número de Aprendices</label>
                            <input name="number" type="text" class="form-control formulario__input" id="nombre"
                                value="{{ $ficha->num_trainnies }}" disabled>
                        </div>

                    </div>

                    <div class="row">

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="number" class="col-form-label fw-bold">Programa</label>
                            <input name="number" type="text" class="form-control formulario__input" id="nombre"
                                value="{{ $ficha->programa->name }}" disabled>
                        </div>

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="number" class="col-form-label fw-bold">Jornada</label>
                            <input name="number" type="text" class="form-control formulario__input" id="nombre"
                                value="{{ $ficha->jornada->name }}" disabled>
                        </div>

                    </div>

                    <div class="row">

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="number" class="col-form-label fw-bold">Oferta</label>
                            <input name="number" type="text" class="form-control formulario__input" id="nombre"
                                value="{{ $ficha->oferta->name }}" disabled>
                        </div>

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="number" class="col-form-label fw-bold">Trimestre</label>
                            <input name="number" type="text" class="form-control formulario__input" id="nombre"
                                value="{{ $ficha->trimestre->name }}" disabled>
                        </div>

                    </div>

                    <div class="row">

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="number" class="col-form-label fw-bold">Inicio etapa lectiva</label>
                            <input name="number" type="text" class="form-control formulario__input" id="nombre"
                                value="{{ $ficha->start_lective }}" disabled>
                        </div>

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="number" class="col-form-label fw-bold">Fin etapa lectiva</label>
                            <input name="number" type="text" class="form-control formulario__input" id="nombre"
                                value="{{ $ficha->end_lective }}" disabled>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger tooltipA" data-bs-dismiss="modal" data-tooltip="Cerrar">
                            <i class="fas fa-times-circle fs-5"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
