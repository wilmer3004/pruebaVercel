<!-- Modal -->
<div class="modal fade" id="unionChips" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Union de fichas</h5>
        </div>
        <div class="modal-body">
          <form id="formularioUnion">
            @csrf

            {{-- Grupo de fichas --}}
            <div style="height: 55vh">

                <div class="d-flex justify-content-between" id="modalUnionFichas">
                    <div class="py-2 col-mb-6 col-6 input-grupo" style="width: 40%" id="contenedor1">
                        <label for="ficha1" class="col-form-label fw-bold">Ficha para unir</label>
                        <select name="ficha1" id="ficha1" style="width: 100%; border-radius: 5px; height: 30px; border: 1px gray solid" required>
                            <option disabled selected>Seleccione una ficha...</option>
                            @foreach ($fichas as $ficha)
                            <option value="{{ $ficha->id }}">
                                {{ $ficha->num > 0 ? $ficha->number . '-' . $ficha->num : $ficha->number }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="py-2 col-mb-6 col-6 input-grupo" style="width: 40%" id="contenedor2">
                        <label for="ficha2" class="col-form-label fw-bold">Ficha que recibira la union</label>
                        <select name="ficha2" id="ficha2" style="width: 100%; border-radius: 5px; height: 30px; border: 1px gray solid" required>
                            <option disabled selected>Seleccione una ficha...</option>
                            @foreach ($fichas as $ficha)
                            <option value="{{ $ficha->id }}">
                                {{ $ficha->num > 0 ? $ficha->number . '-' . $ficha->num : $ficha->number }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="contLinea">

                </div>

                <!-- Simulacion de carga -->
                <div class="d-flex justify-content-between" >
                    <div style="width: 40%; padding: 0 5%" id="info" class="text-start">
                    </div>

                    <div tyle="width: 20%; border:" id="condi">

                    </div>

                    <div style="width: 40%; padding: 0 5%" id="info" class="text-end">
                    </div>
                </div>

                <div id="result" class="text-center">
                </div>

            </div>

        </div>
        <div class="input-grupo m-4">
            <span id="errorUnion"></span>
        </div>
        <div class="modal-footer">
            <button type="button" id="btnCerrar" class="btn btn-danger tooltipA" data-bs-dismiss="modal" data-tooltip="Cerrar"><i
            class="fas fa-times-circle fs-5"></i></button>
            <button type="submit" id="btnReiniciar" class="btn btn-large my-3 mx-2 boton" data-tooltip="Unir">Unir fichas</button>
        </div>
        </form>
      </div>
    </div>
  </div>
