<script>
    // ******RECOLECCION DE ROLESA******
    @auth
    var userRoles = @json(Auth::user()->roles->pluck('name'));
    @else
        var userRoles = [];
    @endauth
    //*** DATATABLES ***
    $id = 0;
    let table = $('#condiciones').DataTable({
        "ajax": "{{ route('condiciones.listar') }}",

        "columns": [{
                'data': 'id'
            },
            {
                'data': 'name'
            },
            {
                'data': 'description'
            },
            {
                'render': function(data, type, row) {
                    let stateContract = '';
                    stateContract = row.state === true ? "Activo" : "Inactivo";
                    return stateContract;
                },
            },
            {
                'title': 'Acciones',
                'render': function(data, type, row) {
                    let buttons = '';
                    // Transformar todos los roles a minúsculas para la comparación
                    let lowerCaseRoles = userRoles.map(role => role.toLowerCase());

                    // Verificar los roles usando la versión en minúsculas
                    if (lowerCaseRoles.includes('superadmin') || lowerCaseRoles.includes(
                            'administrador')) {
                        buttons += `
                            <a class="btn btn-sm btn-warning tooltipA" data-tooltip="Editar" onclick="editar(${row.id})">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a class="btn btn-sm btn-danger tooltipA" data-tooltip="Eliminar" onclick="eliminarId(${row.id}">
                                <i class="fas fa-trash"></i>
                            </a>`;
                    }

                    if ((lowerCaseRoles.includes('superadmin') || lowerCaseRoles.includes(
                            'administrador')) && row.state === true) {
                        buttons += `
                            <a class = "btn btn-sm btn-danger tooltipA eliminar" href="{{ route('condiciones.index') }}" onclick="cambiarEstado(${row.id})" data-tooltip="Deshabilitar" >
                                <i class="fa-solid fa-xmark"></i>
                            </a>`;
                    } else if ((lowerCaseRoles.includes('superadmin') || lowerCaseRoles.includes(
                            'administrador')) && row.state !== true) {
                        buttons += `
                                <a class = "btn  btn-sm btn-success tooltipA eliminar" href="{{ route('condiciones.index') }}" onclick="cambiarEstado(${row.id})"  data-tooltip="Habilitar" >
                                    <i class="fa-solid fa-check"></i>
                                </a>`;
                    }
                    // Comprobación adicional si solo se incluyen ciertos roles sin permisos
                    if (lowerCaseRoles.includes('programador') || lowerCaseRoles.includes(
                            'instructor')) {
                        if (!lowerCaseRoles.includes('superadmin') && !lowerCaseRoles.includes(
                                'administrador')) {
                            buttons += '<p class="text-danger">No tienes acceso</p>';
                        }
                    }

                    return `<div class="d-flex justify-content-around">${buttons}</div>`;
                },


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
    let descripcionError = document.getElementById('descripcion-error');
    let form = document.getElementById('formulario');
    let formEditar = document.getElementById('formularioE');
    let error = document.getElementById('error');
    let nombre = document.getElementById('nombre');
    let descripcion = document.getElementById('descripcion');

    /* COMPONENTS EDIT */
    let nameE = document.getElementById('nombreE');
    let descripcionE = document.getElementById('descripcionE');
    /* ELEMENTS VALIDATION EDIT */
    let spanErrorNameE = document.getElementById('name-errorE');
    let spanErrorDescriptionE = document.getElementById('descripcion-errorE');
    let spanErrorSubmitErrorE = document.getElementById('submit-errorE');

    /* FUNCTION VALIDATE */

    /* VALIDATE CREATE */
    function validateName() {
        name = nombre.value;

        if (name.length == 0) {
            nameError.innerHTML = '*La condición es requerida';
            return false;
        }

        if (!name.match(/^[a-zA-ZÀ-ÿ\s]{4,30}$/)) {
            nameError.innerHTML = '*Digite una condición válida';
            return false;
        }

        nameError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }

    function validateDescripcion() {
        description = descripcion.value;

        if (description.length == 0) {
            descripcionError.innerHTML = '*La descripción es requerida';
            return false;
        }

        if (!description.match(/^[a-zA-ZÀ-ÿ\s]{8,200}$/)) {
            descripcionError.innerHTML = '*Digite una descripción válida';
            return false;
        }

        descripcionError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }
    /* VALIDATE EDIT */
    function validateNameE() {
        nameValue = nameE.value;

        if (nameValue.length == 0) {
            spanErrorNameE.innerHTML = '*La condición es requerida';
            return false;
        }

        if (!nameValue.match(/^[a-zA-ZÀ-ÿ\s]{4,30}$/)) {
            spanErrorNameE.innerHTML = '*Digite una condición válida';
            return false;
        }

        spanErrorNameE.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }

    function validateDescripcionE() {
        descriptionValue = descripcionE.value;

        if (descriptionValue.length == 0) {
            spanErrorDescriptionE.innerHTML = '*La descripción es requerida';
            return false;
        }

        if (!descriptionValue.match(/^[a-zA-ZÀ-ÿ\s]{8,200}$/)) {
            spanErrorDescriptionE.innerHTML = '*Digite una descripción válida';
            return false;
        }

        spanErrorDescriptionE.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }
    //*** MÉTODOS PARA LA CRUD ***

    //Método para crear
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        if (!validateName() || !validateDescripcion()) {
            error.innerHTML = '*Por favor valide que los campos estén correctos';
            return;
        } else {
            error.innerHTML = '';

            // Función para guardar en minúsculas
            const nombreInput = document.getElementById("nombre");
            const descripcionInput = document.getElementById("descripcion");

            nombreInput.value = nombreInput.value.toLowerCase();
            descripcionInput.value = descripcionInput.value.toLowerCase();

            let formData = new FormData(form);
            const formObject = Object.fromEntries(formData);

            axios.post('{{ route('condiciones.store') }}', formData).
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
        axios.get(`condiciones/${id}/edit`)
            .then((result) => {
                let datos = result.data.data[0];

                $('#id').val(datos.id);
                $('#nombreE').val(datos.name);
                $('#descripcionE').val(datos.description);
            })
            .catch((error) => {
                console.log(error);
            })

        $('#EditConModal').modal('show');
    }

    // Método para actualizar un registro
    formEditar.addEventListener('submit', (event) => {
        event.preventDefault();
        if (!validateDescripcionE() || !validateNameE()) {
            spanErrorSubmitErrorE.innerHTML = "*Por favor valida los campos"
            return;
        } else {
            let formData = new FormData(formEditar);
            const formObject = Object.fromEntries(formData);
            console.log(formObject);

            axios.post('{{ route('condiciones.update') }}', formData)
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
                axios.get('condiciones/' + id + '/delete').then((result) => {
                    console.log(result);
                }).catch((error) => {
                    console.log(error);
                })
                window.location.href = url;
            }
        });
    }

    //Metodo para cambiar el estado de las condiciones de contrato
    function cambiarEstado(id) {
        event.preventDefault();

        const url = '{{ route('condiciones.index') }}';

        Swal.fire({
            title: "¿Estás seguro?",
            text: "Se cambiara el estado del registro seleccionado",
            icon: "warning",
            confirmButtonText: "Si",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                axios.get('condiciones/' + id + '/changeState')
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
</script>
