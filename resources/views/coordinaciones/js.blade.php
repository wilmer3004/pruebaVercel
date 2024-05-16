<script>

    // ******RECOLECCION DE ROLESA******
        @auth
            var userRoles = @json(Auth::user()->roles->pluck('name'));
        @else
            var userRoles = [];
        @endauth
    //*** DATATABLES ***
    $id = 0;
    let table = $('#coordinaciones').DataTable({
        "ajax": "{{ route('coordinaciones.listar') }}",

        "columns": [{
                'data': 'id'
            },
            {
                'data': 'name'
            },
            {
                'title': 'Color',
                'render': function(data, type, row) {
                    // Create a div with a class that you can style in your CSS
                    return '<div class="color-box" style="background-color: ' + row.color + ';"></div>';
                }
            },
            {
                'render':function(data, type, row) {
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
                    if (lowerCaseRoles.includes('superadmin') || lowerCaseRoles.includes('administrador')) {
                        buttons += `
                            <a class="btn btn-sm btn-warning tooltipA" data-tooltip="Editar" onclick="editar(${row.id})">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a class="btn btn-sm btn-danger tooltipA" href="{{ route('coordinaciones.index') }}" data-tooltip="Eliminar" onclick="eliminarId(${row.id})">
                                <i class="fas fa-trash"></i>
                            </a>`;
                    }

                    if ((lowerCaseRoles.includes('superadmin') || lowerCaseRoles.includes('administrador')) && row.state === true) {
                        buttons += `
                            <a class = "btn btn-sm btn-danger tooltipA eliminar" href="{{ route('coordinaciones.index') }}" onclick="cambiarEstado(${row.id})" data-tooltip="Deshabilitar" >
                                <i class="fa-solid fa-xmark"></i>
                            </a>`;
                    } else if ((lowerCaseRoles.includes('superadmin') || lowerCaseRoles.includes('administrador')) && row.state !== true) {
                        buttons += `
                                <a class = "btn  btn-sm btn-success tooltipA eliminar" href="{{ route('coordinaciones.index') }}" onclick="cambiarEstado(${row.id})"  data-tooltip="Habilitar" >
                                    <i class="fa-solid fa-check"></i>
                                </a>`;
                    }
                    // Comprobación adicional si solo se incluyen ciertos roles sin permisos
                    if (lowerCaseRoles.includes('programador') || lowerCaseRoles.includes('instructor')) {
                        if (!lowerCaseRoles.includes('superadmin') && !lowerCaseRoles.includes('administrador')) {
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

    //*** FORM VALIDATIONS ***

    /* TextField Create */
    let nombre = document.getElementById('nombre');
    let color = document.getElementById('color');

    /* Span Error Create */
    let nameError = document.getElementById('name-error');
    let colorError = document.getElementById('color-error');
    let error = document.getElementById('error');

    /* TextField Edit */
    let nombreE = document.getElementById('nombreE');
    let colorE = document.getElementById('colorE');

    /* Span Error Edit */
    let nameErrorE = document.getElementById('name-errorE');
    let colorErrorE = document.getElementById('color-errorE')
    let errorE = document.getElementById('errorE');

    /* Form Create and Edit  */
    let formCreate = document.getElementById('formulario')
    let formEdit = document.getElementById('formularioE'); // BUTTON EDTI

    function validateName() {
        name = nombre.value;

        if (name.length == 0) {
            nameError.innerHTML = '*La coordinación es requerida';
            return false;
        }

        if (!name.match(/^[a-zA-ZÀ-ÿ\s]{4,20}$/)) {
            nameError.innerHTML = '*Digite una coordinación válida';
            return false;
        }

        nameError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }

    function validateColor() {
        colora = color.value;
        if (colora.length == 0) {
            colorError.innerHTML = '*El color es requerido';
            return false;
        }

        // Regex for a valid hexadecimal color code
        var colorRegex = /^#([0-9a-fA-F]{3}){1,2}$/;

        if (!colorRegex.test(colora)) {
            colorError.innerHTML = '*Ingrese un color válido';
            return false;
        }

        colorError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El color es válido</p>';
        return true;
    }

    function validateNameE() {
        name = nombreE.value;

        if (name.length == 0) {
            nameErrorE.innerHTML = '*La coordinación es requerida';
            return false;
        }

        if (!name.match(/^[a-zA-ZÀ-ÿ\s]{4,20}$/)) {
            nameErrorE.innerHTML = '*Digite una coordinación válida';
            return false;
        }

        nameErrorE.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }

    function validateColorE() {
        colora = colorE.value;
        if (colora.length == 0) {
            colorErrorE.innerHTML = '*El color es requerido';
            return false;
        }

        // Regex for a valid hexadecimal color code
        var colorRegex = /^#([0-9a-fA-F]{3}){1,2}$/;

        if (!colorRegex.test(colora)) {
            colorErrorE.innerHTML = '*Ingrese un color válido';
            return false;
        }

        colorErrorE.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El color es válido</p>';
        return true;
    }


    /* *** VALIDATIONS FUNCTIONS *** */


    //*** MÉTODOS PARA LA CRUD ***

    // CREATE
    formCreate.addEventListener('submit', (event) => {
        event.preventDefault();

        if (!validateName() || !validateColor()) {
            error.innerHTML = '*Por favor valide que los campos estén correctos';
            return;
        } else {
            error.innerHTML = '';

            // Función para guardar en minúsculas
            const nombreInput = document.getElementById("nombre");
            const colorInput = document.getElementById("color");


            nombreInput.value = nombreInput.value.toLowerCase();
            color.value = colorInput.value;

            let formData = new FormData(formCreate);
            const formObject = Object.fromEntries(formData);

            axios.post("{{ route('coordinaciones.store') }}", formData).
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

    // EDIT
    function editar(id) {
        axios.get(`coordinaciones/${id}/edit`)
            .then((result) => {
                let datos = result.data.data[0];

                $('#id').val(datos.id);
                $('#nombreE').val(datos.name);
                $('#colorC').val(datos.color);
                if (datos.multi_technique == true) {
                    $('input:radio[name=tipoTec][value=1]').prop('checked',
                        true); // Selecciona el radio button con id 'tipoTec1'
                } else {
                    $('input:radio[name=tipoTec][value=0]').prop('checked',
                        true); // Selecciona el radio button con id 'tipoTec0'
                }

            })
            .catch((error) => {
                console.log(error);
            })

        $('#editCoordinacionModal').modal('show');
    }

    // Método para actualizar un registro
    formEdit.addEventListener('submit', (event) => {
        event.preventDefault();

        if (!validateNameE() || !validateColorE()) {
            errorE.innerHTML = '*Por favor valide que los campos estén correctos';
            return;
        } else {
            errorE.innerHTML = '';

            let formData = new FormData(formEdit);
            const formObject = Object.fromEntries(formData);

            axios.post('{{ route('coordinaciones.update') }}', formData)
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
    });

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
                axios.get('coordinaciones/' + id + '/delete').then((result) => {
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

    function cambiarEstado(id) {
        event.preventDefault();

        const url = '{{ route("coordinaciones.index") }}';

        Swal.fire({
            title: "¿Estás seguro?",
            text: "Se cambiara el estado del registro seleccionado",
            icon: "warning",
            confirmButtonText: "Si",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                axios.get('coordinaciones/' + id + '/changeState')
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
