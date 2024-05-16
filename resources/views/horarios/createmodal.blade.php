 <div class="modal fade" id="evento" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
             <div class="modal-header">
                 <h1 class="modal-title fs-5" id="diaSeleccionado"></h1>
                 <button type="button" class="btn-close btn-danger" data-bs-dismiss="modal"
                     aria-label="Close"></button>
             </div>
             <div class="modal-body">

                 {{-- <a id="btnModal" href="{{route('horarios.create2')}}">Programagar fichas para el día .....</a> --}}

                 <form action="#" method="GET">

                    @csrf
                    <input type="submit" name="enviar" id="btnModal">
                 </form>


             </div>
         </div>
     </div>
 </div>
