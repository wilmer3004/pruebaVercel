<script>
    //*** DATATABLES ***
    $id = 0;
    let table = $('#sedes').DataTable({
        "ajax": "{{ route('sedes.listar') }}",

        "columns": [{
                'data': 'id'
            },
            {
                'data': 'name'
            },
            {
                'data': 'adress'
            },
            {
                'data': 'environment_capacity'
            },
            {
                'data': 'floors'
            },
            {
                'title': 'Acciones',
                'render': function(data, type, row) {
                    let enableButton = `
                    ${row.state.trim() === 'activo'
                        ? `
                        <a class = "btn btn-sm btn-danger tooltipA" href="{{ route('sedes.index') }}" onclick="cambiarEstado(${row.id})" data-tooltip="Deshabilitar" >
                            <i class="fa-solid fa-xmark"></i>
                        </a>`
                        : `
                        <a class = "btn btn-sm btn-success tooltipA" href="{{ route('sedes.index') }}" onclick="cambiarEstado(${row.id})" data-tooltip="Habilitar" >
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
                        @role('superadmin|administrador')
                        <div class="btn-group col-3">
                            <div class="">
                                <a class="btn btn-sm btn-danger tooltipA eliminar" href="{{ route('sedes.index') }}" onclick="eliminarId(${row.id})" data-tooltip="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                        @endrole
                        @role('superadmin|administrador')
                        <div class="btn-group">
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

    //*** VALIDACIONES DEL FORMULARIO ***
    let nameError = document.getElementById('name-error');
    let ambientesError = document.getElementById("ambientes-error");
    let descripcionError = document.getElementById("direccion-error");
    let nameErrorE = document.getElementById('name-errorE');
    let ambientesErrorE = document.getElementById("ambientes-errorE");
    let descripcionErrorE = document.getElementById("direccion-errorE");
    let pisosError = document.getElementById("pisos-error");
    let pisosErrorE = document.getElementById("pisos-errorE");
    let form = document.getElementById('formulario');
    let formEditar = document.getElementById('formularioE');
    let error = document.getElementById('error');
    let errorE = document.getElementById('errorE');
    let nombre = document.getElementById("nombre");
    let ambientes = document.getElementById("ambientes");
    let adress = document.getElementById("direccion");
    let pisos = document.getElementById("pisos");
    let pisosE = document.getElementById("pisosE");
    let nombreE = document.getElementById("nombreE");
    let ambientesE = document.getElementById("ambienteE");
    let adressE = document.getElementById("direccionE");

    // Validacion de campos creacion front
    function validateName() {
        name = nombre.value;

        if (name.length == 0) {
            nameError.innerHTML = "*La sede es requerida";
            return false;
        }

        if (!name.match(/^[a-zA-ZÀ-ÿ\s]{4,20}$/)) {
            nameError.innerHTML = "*Digite una sede válida";
            return false;
        }

        nameError.innerHTML =
            '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }

    function validateAmbientes() {
        environments = ambientes.value;

        if (environments.length == 0) {
            ambientesError.innerHTML = "*La capacidad de ambientes es requerida";
            return false;
        }

        if (!environments.match(/^\d{1,4}$/)) {
            ambientesError.innerHTML = "*La capacidad no es válida";
            return false;
        }

        ambientesError.innerHTML =
            '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }

    function validateDireccion() {
        adress = direccion.value;

        if (adress.length == 0) {
            descripcionError.innerHTML = "*La dirección es requerida";
            return false;
        }

        if (!adress.match(/^[a-zA-ZÀ-ÿ\s0-9-#]{4,100}$/)) {
            descripcionError.innerHTML = "*La dirección no es válida";
            return false;
        }

        descripcionError.innerHTML =
            '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }

    // Validacion de campos edicion front
    function validateNameE() {
        name = nombreE.value;
        nameErrorE.innerHTML = ''

        if (name.length == 0) {
            nameErrorE.innerHTML = "*La sede es requerida";
            return false;
        }

        if (!name.match(/^[a-zA-ZÀ-ÿ\s]{4,20}$/)) {
            nameErrorE.innerHTML = "*Digite una sede válida";
            return false;
        }

        nameErrorE.innerHTML =
            '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }

    function validateAmbientesE() {
        environments = ambientesE.value;

        ambientesErrorE.innerHTML = ''

        if (environments.length == 0 || environments == 0) {
            ambientesErrorE.innerHTML = "*La capacidad de ambientes es requerida";
            return false;
        }

        if (!environments.match(/^\d{1,4}$/)) {
            ambientesErrorE.innerHTML = "*La capacidad no es válida";
            return false;
        }

        ambientesErrorE.innerHTML =
            '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }

    function validateDireccionE() {
        adress = adressE.value;
        descripcionErrorE.innerHTML = ''

        if (adress.length == 0) {
            descripcionErrorE.innerHTML = "*La dirección es requerida";
            return false;
        }

        if (!adress.match(/^[a-zA-ZÀ-ÿ\s0-9#]{4,100}$/)) {
            descripcionErrorE.innerHTML = "*La dirección no es válida";
            return false;
        }

        if (!isNaN(adress)) {
            descripcionErrorE.innerHTML = "*La dirección no puede constar de solo numeros";
            return false;
        }

        descripcionErrorE.innerHTML =
            '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }

    function validatePisos() {
        valuePisos = pisos.value;
        const regex = /^\d{1,2}$/;
        pisosError.innerHTML = '';

        if (valuePisos.length === 0 || valuePisos == 0) {
            pisosError.innerHTML = 'El numero de pisos no es valido';
            return false;
        }
        if (!valuePisos.match(regex)) {
            pisosError.innerHTML = 'El numero de pisos no es valido';
            return false;
        }
        pisosError.innerHTML =
            '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }

    function validatePisosE() {
        valuePisosE = pisosE.value;
        const regex = /^\d{1,2}$/;
        pisosErrorE.innerHTML = '';

        if (valuePisosE.length === 0 || valuePisosE == 0) {
            pisosErrorE.innerHTML = 'El numero de pisos no es valido';
            return false;
        }
        if (!valuePisosE.match(regex)) {
            pisosErrorE.innerHTML = 'El numero de pisos no es valido';
            return false;
        }
        pisosErrorE.innerHTML =
            '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }
    //*** MÉTODOS PARA LA CRUD ***

    //Método para crear
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        if (!validateAmbientes() || !validateName() || !validateDireccion() || !validatePisos()) {
            error.innerHTML = '*Por favor valide que los campos estén correctos';
            return;
        } else {
            error.innerHTML = '';

            // Función para guardar en minúsculas
            const nombreInput = document.getElementById("nombre");
            const ambientesInput = document.getElementById("ambientes");

            nombreInput.value = nombreInput.value.toLowerCase();
            ambientesInput.value = ambientesInput.value.toLowerCase();

            let formData = new FormData(form);
            const formObject = Object.fromEntries(formData);

            axios.post("{{ route('sedes.store') }}", formData).
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
                } else {
                    window.location.href = result.data.url;
                }
            }).catch((error) => {
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
        axios.get(`sedes/${id}/edit`)
            .then((result) => {
                let datos = result.data.data[0];
                $('#id').val(datos.id);
                $('#nombreE').val(datos.name);
                $('#ambienteE').val(datos.environment);
                $('#direccionE').val(datos.adress);
                $('#pisosE').val(datos.floors);
            })
            .catch((error) => {

            })

        $('#EditSedeModal').modal('show');
    }

    // Método para actualizar un registro
    formEditar.addEventListener('submit', (event) => {
        event.preventDefault();

        if (!validateAmbientesE() || !validateNameE() || !validateDireccionE() || !validatePisosE()) {
            errorE.innerHTML = '*Por favor valide que los campos estén correctos';
            return;
        } else {
            errorE.innerHTML = '';

            // Función para guardar en minúsculas
            const nombreInput = document.getElementById("nombreE");
            const ambientesInput = document.getElementById("ambienteE");

            nombreInput.value = nombreInput.value.toLowerCase();
            ambientesInput.value = ambientesInput.value.toLowerCase();


            let formData = new FormData(formEditar);
            const formObject = Object.fromEntries(formData);

            axios.post("{{ route('sedes.update') }}", formData)
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
        }
    })

    function cambiarEstado(id) {
        event.preventDefault();
        const url = event.currentTarget.getAttribute("href");
        Swal.fire({
            title:"¿Estas seguro?",
            text: "Seguro de cambiar el estado de la sede, conllevara en la no programación de ambientes asociados a esa sede.",
            icon:"warning",
            confirmButtonText: "Si, cambiar estado",
            showCancelButton: true,
            cancelButtonText: "Cancelar"
        }).then((result)=>{
            if(result.isConfirmed) {
                axios.get('sedes/' + id + '/state').then((response) => {
                    Swal.fire({
                        icon: response.data.icon,
                        title: response.data.title,
                        text: response.data.message,
                        showConfirmButton: true,
                        confirmButtonText: "Listo",
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                    }).then(() => {
                        response.data.success ? window.location.href = url : "";
                    })
                })
                .catch((error) => {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Por favor, contatese con soporte, hubo un error en el cambio de estado.",
                        showCancelButton: "Listo",
                        allowEscapeKey: false,
                        allowOutsideClick: false
                    })
                })
            }
        })
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
                axios.get('sedes/' + id + '/delete').then((result) => {

                }).catch((error) => {

                })
                window.location.href = url;
            }
        });
    }
</script>
