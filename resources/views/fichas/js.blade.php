<script>

    //*** DATATABLES ***
    $id = 0;
    let table = $('#fichas').DataTable({
        "ajax": "{{ route('fichas.listar') }}",
        "columns": [{
                'data': 'id'
            },
            {
                'data': null,
                'render': function(data, type, row) {

                    if(row.num==null){
                        return row.numficha ;
                    } else {
                        // Concatena 'numficha' y 'num' con un espacio o el separador que prefieras
                        return row.numficha + '-' + row.num;
                    }
                }
            },
            {
                'data': 'numaprendices'
            },
            {
                'data': 'programa'
            },
            {
                'data': 'jornada'
            },
            {
                'data': 'oferta'
            },
            {
                'data': 'trimestre'
            },
            {
                'data': null,
                'render': function(data, type, row) {

                    return row.state.replace('_',' ');

                }
            },
            {
                'title': 'Acciones',
                'render': function(data, type, row) {
                    let enableButton= `
                    ${row.state.trim() === 'activo'
                        ? `
                        <a class = "btn btn-sm btn-danger tooltipA eliminar" href="{{ route('fichas.index') }}" onclick="cambiarEstado(${row.id})" data-tooltip="Deshabilitar" >
                            <i class="fa-solid fa-xmark"></i>
                        </a>`
                        : `
                        <a class = "btn  btn-sm btn-success tooltipA eliminar" href="{{ route('fichas.index') }}" onclick="cambiarEstado(${row.id})" data-tooltip="Habilitar" >
                            <i class="fa-solid fa-check"></i>
                        </a>`
                    }
                    `;
                    return `
                    <div class="d-flex justify-content-around">
                        @role('superadmin|administrador')
                        <div class="btn-group col-3">
                            <div class="">
                                <a class="btn btn-sm btn-warning tooltipA" data-tooltip="Editar" id="editar" onclick="editar(${row.id})">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                        @endrole
                        @role('superadmin')
                        <div class="btn-group col-3">
                            <div class="">
                                <a class="btn btn-sm btn-danger tooltipA" data-tooltip="Eliminar" id="eliminar" onclick="eliminarId(${row.id})">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                        @endrole
                        @role('superadmin|administrador')
                        <div class="btn-group col-3">
                            <div class="">
                                ${enableButton}
                            </div>
                        </div>
                        @endrole
                        @role('programador|instructor')
                        <div class="col-12">
                            <p class="text-danger">No tienes acceso</p>
                        </div>
                        @endrole
                    </div>
                    `
                }
            },
        ],
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "sProcessing": "Procesando...",
        },
    });

    //VALIDACION DEL FORMULARIO
    let numberError = document.getElementById('number-error');
    let aprendicesError = document.getElementById('aprendices-error');
    let form = document.getElementById('formulario');
    let formEditar = document.getElementById('formularioE');
    let formUnionFicha= document.getElementById('formularioUnion');
    let error = document.getElementById('error');
    let errorUnion = document.getElementById('errorUnion');
    let numero = document.getElementById('number');
    let trainnes = document.getElementById('aprendices');
    let unionChips= document.getElementById('unionChipsButton')
    let ficha1= document.getElementById('ficha1')
    let ficha2= document.getElementById('ficha2')
    let info=document.querySelectorAll('#info')
    let condi= document.getElementById('condi')
    let conte1= document.getElementById('contenedor1')
    let conte2= document.getElementById('contenedor2')
    let resultado=document.getElementById('result');


    // Union de fichas
    unionChips.addEventListener('click', ()=>{

        // Restaurar todos los contenedores
        condi.classList.remove('disappear');
        conte2.classList.remove('moveD')
        info[1].classList.remove('moveA');
        conte1.classList.remove('moveI')
        info[0].classList.remove('moveA');
        resultado.classList.remove('result');

        // Reestablecer los botones de cerrar
        document.querySelector('#btnCerrar').setAttribute('data-bs-dismiss', 'modal');

        resultado.innerHTML= ``
        condi.innerHTML= ``

        let contLinea= document.getElementById('contLinea')
        contLinea.innerHTML=`
            <hr id="linea">
            `

        // Restablecer los selects a su opción por defecto
        $('#ficha1').prop('selectedIndex', 0);
        $('#ficha2').prop('selectedIndex', 0);

        info.forEach(element => {
            element.innerHTML=`
                    <p class="placeholder-glow text-bg-li">
                        <span class="placeholder col-7" id="numero"></span>
                    </p>
                    <p class="card-text placeholder-glow">
                        <span class="placeholder col-12" id="aprendices"></span>
                    </p>
                    <p class="card-text placeholder-glow">
                        <span class="placeholder col-12" id="programa"></span>
                    </p>
                    <p class="card-text placeholder-glow">
                        <span class="placeholder col-9" id="jornada"></span>
                    </p>
                    <p class="card-text placeholder-glow">
                        <span class="placeholder col-11" id="oferta"></span>
                    </p>
                    <p class="card-text placeholder-glow">
                        <span class="placeholder col-6" id="trimestre"></span>
                    </p>
                    <p class="card-text placeholder-glow">
                        <span class="placeholder col-10" id="estado"></span>
                    </p>
            `
        });
    });

    function validarFichas(){

        if ((ficha1.value <= 0 || ficha1.value === '' || ficha1.value === null || ficha1.value==='Seleccione una ficha...') ||
                (ficha2.value <= 0 || ficha2.value === '' || ficha2.value === null || ficha2.value==='Seleccione una ficha...')) {
                return false;
            }

        return true
    }

    $(document).ready(function() {
        var ficha1Options = $('#ficha1 option');
        var ficha2Options = $('#ficha2 option');

        $('#ficha1').change(function() {
            var selectedValue = $(this).val();
            if (selectedValue) {
                $('#ficha2 option').show();
                $('#ficha2 option[value="' + selectedValue + '"]').hide();
            }

            axios.get(`fichas/${$(this).val()}/edit`)
            .then((result) => {

                let datos=result.data.data[0]

                info[0].innerHTML=`
                        <p class=" row placeholder-glow text-bg-li  fichaUnion" >
                            <span id="numeroU1">${datos.num!=null ? `${datos.number}-${datos.num}` : datos.number}</span>
                        </p>
                        <p class=" row card-text placeholder-glow  datoUnionFicha">
                            <span id="aprendicesU1">${datos.trainnies}</span>
                        </p>
                        <p class=" row card-text placeholder-glow  datoUnionFicha">
                            <span id="programaU1">${datos.program}</span>
                        </p>
                        <p class=" row card-text placeholder-glow  datoUnionFicha">
                            <span id="jornadaU1">${datos.day}</span>
                        </p>
                        <p class=" row card-text placeholder-glow  datoUnionFicha">
                            <span id="ofertaU1">${datos.offer}</span>
                        </p>
                        <p class=" row card-text placeholder-glow  datoUnionTrimestre">
                            <span id="trimestreU1">${datos.quarter}</span>
                        </p>
                        <p class=" row card-text placeholder-glow  datoUnionFicha">
                            <span id="estadoU1">${datos.estado}</span>
                        </p>
                `

                verificacionUnionFichas();
            }).catch((error) => {
                console.log(error);
            })


        });

        $('#ficha2').change(function() {
            var selectedValue = $(this).val();
            if (selectedValue) {
                $('#ficha1 option').show();
                $('#ficha1 option[value="' + selectedValue + '"]').hide();
            }

            axios.get(`fichas/${$(this).val()}/edit`)
            .then((result) => {

                let datos=result.data.data[0]

                console.log(datos)
                info[1].innerHTML=`
                        <p class="row placeholder-glow text-bg-li fichaUnion">
                            <span id="numeroU2">${datos.num!=null ? `${datos.number}-${datos.num}` : datos.number}</span>
                        </p>
                        <p class="row card-text placeholder-glow datoUnionFicha">
                            <span id="aprendicesU2">${datos.trainnies}</span>
                        </p>
                        <p class="row card-text placeholder-glow datoUnionFicha">
                            <span id="programaU2">${datos.program}</span>
                        </p>
                        <p class="row card-text placeholder-glow datoUnionFicha">
                            <span id="jornadaU2">${datos.day}</span>
                        </p>
                        <p class="row card-text placeholder-glow datoUnionFicha">
                            <span id="ofertaU2">${datos.offer}</span>
                        </p>
                        <p class="row card-text placeholder-glow datoUnionTrimestre">
                            <span id="trimestreU2">${datos.quarter}</span>
                        </p>
                        <p class="row card-text placeholder-glow datoUnionFicha">
                            <span id="estadoU2">${datos.estado}</span>
                        </p>
                `

                verificacionUnionFichas();
            }).catch((error) => {
                console.log(error);
            })

        });
    });

    function verificacionUnionFichas(){

        let numFicha1=document.getElementById('numeroU1')
        let numFicha2=document.getElementById('numeroU2')


        console.log(numFicha1, numFicha2)

        if(numFicha1!=null && numFicha2!=null){

            // Para el numero de ficha
            condi.innerHTML= `
                        <p class="card-text placeholder-glow text-center" style="margin-top: 4px;">
                            <i class="fa-solid fa-check" style="color: #63E6BE;"></i>
                        </p>
                        `

            // Para numero de estudiantes
            let numAprendices1=document.getElementById('aprendicesU1').textContent
            let numAprendices2=document.getElementById('aprendicesU2').textContent

            if( parseInt(numAprendices1)== 0 && parseInt(numAprendices2)== 0){
                condi.innerHTML+= `
                        <p class="card-text placeholder-glow text-center" style="margin-top: 26px;">
                            <i class="fa-solid fa-xmark" style="color: #fd172e;"></i>
                        </p>`
            } else if((parseInt(numAprendices1)+parseInt(numAprendices2))<=65){
                condi.innerHTML+= `
                        <p class="card-text placeholder-glow text-center" style="margin-top: 26px;">
                            <i class="fa-solid fa-check" style="color: #63E6BE;"></i>
                        </p>
                        `
            } else {
                condi.innerHTML+= `
                        <p class="card-text placeholder-glow text-center" style="margin-top: 26px;">
                            <i class="fa-solid fa-xmark" style="color: #fd172e;"></i>
                        </p>`
            }


            // Para verificar lo programas
            let program1=document.getElementById('programaU1')
            let program2=document.getElementById('programaU2')

            console.log(program1.textContent, program2.textContent)
            if(program1.textContent==program2.textContent){
                condi.innerHTML+= `
                        <p class="card-text placeholder-glow text-center" style="margin-top: 36px;">
                            <i class="fa-solid fa-check" style="color: #63E6BE;"></i>
                        </p>`
            } else {
                condi.innerHTML+= `
                        <p class="card-text placeholder-glow text-center" style="margin-top: 36px;">
                            <i class="fa-solid fa-xmark" style="color: #fd172e;"></i>
                        </p>`
            }

            // Para verificar jornada

            condi.innerHTML+= `
                        <p class="card-text placeholder-glow text-center" style="margin-top: 38px;">
                            <i class="fa-solid fa-check" style="color: #63E6BE;"></i>
                        </p>
                        `

            // Para verificar la oferta
            let oferta1=document.getElementById('ofertaU1')
            let oferta2=document.getElementById('ofertaU2')

            if(oferta1.textContent==oferta2.textContent){
                condi.innerHTML+= `
                        <p class="card-text placeholder-glow text-center" style="margin-top: 25px;">
                            <i class="fa-solid fa-check" style="color: #63E6BE;"></i>
                        </p>
                        `
            } else {
                condi.innerHTML+= `
                        <p class="card-text placeholder-glow text-center" style="margin-top: 25px;">
                            <i class="fa-solid fa-xmark" style="color: #fd172e;"></i>
                        </p>`
            }

            // Para verificar el trimestre

            let trimestre1=document.getElementById('trimestreU1')
            let trimestre2=document.getElementById('trimestreU2')
            if(trimestre1.textContent==trimestre2.textContent){
                condi.innerHTML+= `
                            <p class="card-text placeholder-glow text-center" style="margin-top: 22px;">
                                <i class="fa-solid fa-check" style="color: #63E6BE;"></i>
                            </p>
                            `
            } else {
                condi.innerHTML+= `
                            <p class="card-text placeholder-glow text-center" style="margin-top: 22px;" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-title="Tomara el trimestre de la ficha que recibira la union">
                                <i class="fa-solid fa-xmark" style="color: #fd172e;"></i>
                            </p>
                            `
            }


            // Para verificar el estado
            let estado1=document.getElementById('estadoU1')
            let estado2=document.getElementById('estadoU2')
            if(estado1.textContent=='inactivo' && estado2.textContent=='inactivo' ){
                condi.innerHTML+= `
                            <p class="card-text placeholder-glow text-center" style="margin-top: 26px;">
                                <i class="fa-solid fa-xmark" style="color: #fd172e;"></i>
                            </p>
                            `
            } else if(estado1.textContent==estado2.textContent){
                condi.innerHTML+= `
                            <p class="card-text placeholder-glow text-center" style="margin-top: 26px;">
                                <i class="fa-solid fa-check" style="color: #63E6BE;"></i>
                            </p>
                            `
            } else {
                condi.innerHTML+= `
                            <p class="card-text placeholder-glow text-center" style="margin-top: 26px;">
                                <i class="fa-solid fa-xmark" style="color: #fd172e;"></i>
                            </p>
                            `
            }
        }




    }

    formUnionFicha.addEventListener('submit', (event)=> {
        event.preventDefault();

        // Para verificar si algunas de las condiciones no se cumple
        var iEtiquetas = condi.querySelectorAll('p i');

        // Utiliza el método some para verificar si alguna de las etiquetas <i> tiene la clase 'fa-xmark'
        var tieneXmark = Array.from(iEtiquetas).some(function(item) {
            return item.classList.contains('fa-xmark');
        });


        if(!validarFichas()){
            errorUnion.innerHTML= '*Por favor valide las fichas'
        } else if (tieneXmark) {

            Swal.fire({
                title: "¡Ops..!",
                text: "Alguna de las condiciones no se cumple",
                icon: "error",
                showConfirmButton: true,
                confirmButtonText: "Listo",
                allowEscapeKey: false,
                allowOutsideClick: false,
            });

        }else {
            let formData = new FormData(formUnionFicha);
            const formObject= Object.fromEntries(formData);

            axios.post("{{ route('fichas.listarEvents')}}", formData).
            then((result)=>{
                if(result.data.code==201){
                    Swal.fire({
                        title: "¿Estás seguro?",
                        text: `${result.data.message}, ¿Desea eliminar la programacion y seguir con la union de fichas?`,
                        icon: "warning",
                        confirmButtonText: "Si",
                        showCancelButton: true,
                        cancelButtonText: "Cancelar",
                    }).then((result) => {
                        if (result.isConfirmed) {

                            //Funcion para union de fichas
                            joinTiles(formData);

                        }
                    });
                } else if (result.data.code==200){

                    //Funcion para union de fichas
                    joinTiles(formData);

                } else {
                    Swal.fire({
                        title: "¡Ops..!",
                        text: result.data.message,
                        icon: "error",
                        showConfirmButton: true,
                        confirmButtonText: "Listo",
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                    });
                }
            }).catch((error) => {
                Swal.fire({
                    title: "¡Ops..!",
                    text: error,
                    icon: "error",
                    showConfirmButton: true,
                    confirmButtonText: "Listo",
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                });
            });
        }


    })

    function joinTiles(formData){
        //Para que no se pueda cerrar el modal desde los botones
        document.querySelector('#btnCerrar').removeAttribute('data-bs-dismiss');


        console.log(formData);
        let linea= document.getElementById('linea')
        linea.remove()


        condi.classList.add('disappear');
        conte2.classList.add('moveD')
        info[1].classList.add('moveA');
        conte1.classList.add('moveI')
        info[0].classList.add('moveA');

        axios.post("{{ route('fichas.joinTiles')}}", formData).
        then((result)=>{

            console.log(result.data.message);
            let datos= result.data.message[0]

            // Reestablecer los botones de cerrar
           document.getElementById('btnCerrar').classList.add('cerrarModal')
           document.getElementById('btnReiniciar').classList.add('reiniciar');
           document.getElementById('btnReiniciar').textContent= 'Cerrar Modal';


            setTimeout(() => {
                resultado.innerHTML=`
                    <div class="til">
                        <h3>Ficha final</h1>
                        <i class="fa-regular fa-circle-check" style="color: #63E6BE;"></i>
                    </div>
                    <div class="infoResultado">
                        <div>
                            <p class=" row placeholder-glow text-bg-li  fichaUnion" >
                                <span id="resultado">${datos.num!=null ? `${datos.number}-${datos.num}` : datos.number}</span>
                            </p>
                            <p class=" row card-text placeholder-glow  datoUnionFicha">
                                <span id="resultado">${datos.trainnies}</span>
                            </p>
                            <p class=" row card-text placeholder-glow  datoUnionFicha">
                                <span id="resultado">${datos.program}</span>
                            </p>
                        </div>
                        <div>
                            <p class=" row card-text placeholder-glow  datoUnionFicha">
                                <span id="resultado">${datos.day}</span>
                            </p>
                            <p class=" row card-text placeholder-glow  datoUnionFicha">
                                <span id="resultado">${datos.offer}</span>
                            </p>
                            <p class=" row card-text placeholder-glow  datoUnionTrimestre">
                                <span id="resultado">${datos.quarter}</span>
                            </p>
                            <p class=" row card-text placeholder-glow  datoUnionFicha">
                                <span id="resultado">${datos.estado}</span>
                            </p>
                        </div>
                    </div>

                    `
                    resultado.classList.add('result');

                }, 1000);


            }).catch((error) => {
                Swal.fire({
                    title: "¡Ops..!",
                    text: error,
                    icon: "error",
                    showConfirmButton: true,
                    confirmButtonText: "Listo",
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                });
            });

            document.getElementById('btnReiniciar').addEventListener('click', () => {
                window.location.reload();
            });
    }




    function validarNum(){
        num2 = num.value;

        if(num2.length==0){
            console.log('entro')
            numberError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
            return true;
        }

        let num1=null

        if(num2){
            num1=num2
        }

        if(num1<=0 || num1>4){
            numberError.innerHTML = '*El número de la ficha dividida debe estar entre el 1 y el 4';
            return false;
        }


        numberError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;

    }

    function validateNumber() {
        number = numero.value;


        if (number.length == 0) {
            numberError.innerHTML = '*El número de la ficha es requerido';
            return false;
        }

        if (!number.match(/^[a-zA-ZÀ-ÿ\s0-9-]{5,10}$/)) {
            numberError.innerHTML = '*Digite un número de ficha válido';
            return false;
        }


        numberError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }

    function validateAprendices() {
        trainnes = aprendices.value;
        num2 = num.value;

        console.log(trainnes.length)

        if (trainnes.length == 0) {
            aprendicesError.innerHTML = '*La cantidad de aprendices es requerida';
            return false;
        }

        if (!trainnes.match(/^\d{1,3}$/)) {
            aprendicesError.innerHTML = '*Digite una cantidad de aprendices válida';
            return false;
        }

        if (trainnes>65){
            aprendicesError.innerHTML = 'El número de la ficha debe ser menor a 65';
            return false;
        }

        if(num2.length>0 && trainnes>35){
            aprendicesError.innerHTML = 'El número de la ficha dividida debe ser menor a 35';
            return false;
        }

        aprendicesError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }

    //*** MÉTODOS DE LA CRUD ***

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        if (!validateNumber() || !validateAprendices() || !validarNum() ) {
            error.innerHTML = '*Por favor valide que los campos estén correctos';
            return;
        } else {
            error.innerHTML = '';
            let formData = new FormData(form);
            const formObject = Object.fromEntries(formData);
            console.log(formObject);

            axios.post("{{ route('fichas.store') }}", formData).
            then((result) => {
                if (result.data.code === 505) {
                    Swal.fire({
                        title: "¡Ops..!",
                        text: result.data.message,
                        icon: "error",
                        showConfirmButton: true,
                        confirmButtonText: "Listo",
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                    });
                } else if (result.data.code === 500) {
                    Swal.fire({
                        title: "¡Ops..!",
                        text: result.data.message,
                        icon: "error",
                        showConfirmButton: true,
                        confirmButtonText: "Listo",
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                    });
                } else {
                    window.location.href = result.data.url;
                }
            }).catch((error) => {
                // console.log(error);
                Swal.fire({
                    title: "¡Ops..!",
                    text: error.response.data.message,
                    icon: "error",
                    showConfirmButton: true,
                    confirmButtonText: "Listo",
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                });
            })
        }
    })

    // Método para traer los datos al formulario de editar
    function editar(id) {
        axios.get(`fichas/${id}/edit`)
            .then((result) => {
                let datos = result.data.data[0];
                console.log(datos);

                $('#id').val(datos.id);
                $('#numberE').val(datos.number);
                $('#numE').val(datos.num);
                $('#aprendicesE').val(datos.trainnies);
                $('#programaE').val(datos.programId).find('option:selected').text(datos.program);
                $('#jornadaE').val(datos.dayId).find('option:selected').text(datos.day);
                $('#ofertaE').val(datos.offerId).find('option:selected').text(datos.offer);
                $('#trimestreE').val(datos.quarterId).find('option:selected').text(datos.quarter);
                $('#inicioE').val(datos.start)
            })
            .catch((error) => {
                console.log(error);
            })

        $('#EditFichaModal').modal('show');
    }

    // Método para actualizar un registro
    formEditar.addEventListener('submit', (event) => {
        event.preventDefault();

        let formData = new FormData(formEditar);
        const formObject = Object.fromEntries(formData);

        axios.post("{{ route('fichas.update') }}", formData)
            .then((result) => {
                if (result.data.success) {
                    window.location.href = result.data.url;
                } else {
                    Swal.fire({
                        title: "¡Ops..!",
                        text: result.data.message,
                        icon: "error",
                        showConfirmButton: true,
                        confirmButtonText: "Listo",
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                    });
                }
            })
            .catch((error) => {
                Swal.fire({
                    title: "¡Ops..!",
                    text: error.response.data.message,
                    icon: "error",
                    showConfirmButton: true,
                    confirmButtonText: "Listo",
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                });
            });
    })

    // Método para cambiar estado
    function cambiarEstado(id) {
        event.preventDefault();

        const url = event.currentTarget.getAttribute("href");

        Swal.fire({
            title: "¿Estás seguro?",
            text: "Se cambiara el estado del registro seleccionado",
            icon: "warning",
            confirmButtonText: "Si",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                axios.get('fichas/' + id + '/changeState')
                .then((result) => {
                    if (result.data.success) {
                        // Muestra una alerta de éxito o redirige a otra página
                        Swal.fire({
                            title: result.data.title,
                            text: result.data.message,
                            icon: "success",
                            confirmButtonText: "Aceptar"
                        }).then(() => {
                            // Redirige a otra página, si es necesario
                            window.location.href = url;
                        });
                    } else {
                        // Muestra una alerta de error
                        Swal.fire({
                            title: "Error",
                            text: result.data.message,
                            icon: "error",
                            confirmButtonText: "Aceptar"
                        });
                    }

                }).catch((error) => {
                    console.log(error);
                })
            }
        });
    }

    //Método para eliminar
    function eliminarId(id) {
        event.preventDefault();

        const url = event.currentTarget.getAttribute("href");

        Swal.fire({
            title: "¿Estás seguro?",
            text: "Se eliminará el registro seleccionado",
            icon: "warning",
            confirmButtonText: "Si, eliminar",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                axios.get('fichas/' + id + '/delete').then((result) => {
                    console.log(result);
                    if (result.data.success) {
                        // Muestra una alerta de éxito o redirige a otra página
                        Swal.fire({
                            title: result.data.title,
                            text: result.data.message,
                            icon: "success",
                            confirmButtonText: "Aceptar"
                        }).then(() => {
                            // Redirige a otra página, si es necesario
                            window.location.reload();
                        });
                    } else {
                        // Muestra una alerta de error
                        Swal.fire({
                            title: "Error",
                            text: result.data.message,
                            icon: "error",
                            confirmButtonText: "Aceptar"
                        });
                    }
                }).catch((error) => {
                    console.log(error);
                })
            }
        });
    }

</script>
