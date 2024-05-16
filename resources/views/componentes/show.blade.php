<div class="modal fade" id="ShowComponenteModal{{ $componente->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Información del Componente {{ $componente->name }}
                </h1>
                <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="formulario">

                    <input type="text" name="idcom" value="{{ $componente->id }}" hidden>

                    {{-- Grupo nombre --}}
                    <div class="row">

                        <div class="py-2 col-mb-6 col-12 input-grupo" id="">
                            <label for="name" class="col-form-label fw-bold">Nombre Componente</label>
                            <input name="name" type="text" class="form-control" id="nombre"
                                value="{{ old('name', $componente->name) }}" disabled>
                        </div>

                    </div>

                    <div class="row">

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="name" class="col-form-label fw-bold">Trimestre</label>
                            <input name="name" type="text" class="form-control" id="nombre"
                                value="{{ old('name', $componente->trimestre->name) }}" disabled>
                        </div>

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="name" class="col-form-label fw-bold">Tipo de Componente</label>
                            <input name="name" type="text" class="form-control" id="nombre"
                                value="{{ old('name', $componente->tipo->name) }}" disabled>
                        </div>
                    </div>

                    <div class="row">
                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="name" class="col-form-label fw-bold">Programa</label>
                            <input name="name" type="text" class="form-control" id="nombre"
                                value="{{ old('name', $componente->programa->name) }}" disabled>
                        </div>

                        <div class="py-2 col-mb-6 col-6 input-grupo" id="">
                            <label for="name" class="col-form-label fw-bold">Totral horas</label>
                            <input name="name" type="text" class="form-control" id="nombre"
                                value="{{ old('name', $componente->total_hours) }}" disabled>
                        </div>

                    </div>

                    <div class="row">
                        <div class="py-2 col-mb-12 col-12 input-grupo" id="">
                            <label for="nombre" class="col-form-label fw-bold">Descripción</label>
                            <textarea class="form-control" name="descripcion" placeholder="Agregar descripción" id="descripcion" disabled>{{ old('descripcion', $componente->description) }}</textarea>
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger tooltipA" data-bs-dismiss="modal"
                            data-tooltip="Cerrar"><i class="fas fa-times-circle fs-5"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
