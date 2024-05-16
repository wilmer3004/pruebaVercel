<script>
    // ******RECOLECCION DE ROLESA******
    @auth
        var userRoles = @json(Auth::user()->roles->pluck('name'));
    @else
        var userRoles = [];
    @endauth
    //*** DATATABLES ***
    $id = 0;
    let table = $('#tipocomponente').DataTable({
        "ajax": "{{ route('tipos.listar') }}",

        "columns": [{
                'data': 'id'
            },
            {
                'data': 'name'
            },
            {
                'render':function(data, type, row){
                    let dataState = '';
                    dataState = row.state===true? "Activo" : "Inactivo";
                    return dataState;
                },
            },
            {
                'title': 'Acciones',
                'render': function(data, type, row) {
                    let buttons = '';
                    // Transformar todos los roles a minúsculas para la comparación
                    let lowerCaseRoles = userRoles.map(role => role.toLowerCase());

                    if ((lowerCaseRoles.includes('superadmin') || lowerCaseRoles.includes('administrador')) && row.state === true) {
                        buttons += `
                            <a class = "btn btn-sm btn-danger tooltipA eliminar" href="{{ route('tipos.index') }}" onclick="cambiarEstado(${row.id})" data-tooltip="Deshabilitar" >
                                <i class="fa-solid fa-xmark"></i>
                            </a>`;
                    } else if ((lowerCaseRoles.includes('superadmin') || lowerCaseRoles.includes('administrador')) && row.state !== true) {
                        buttons += `
                                <a class = "btn  btn-sm btn-success tooltipA eliminar" href="{{ route('tipos.index') }}" onclick="cambiarEstado(${row.id})"  data-tooltip="Habilitar" >
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


            }
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
    let form = document.getElementById('formulario');
    let formEditar = document.getElementById('formularioE');
    let error = document.getElementById('error');
    let nombre = document.getElementById('nombre');

    function validateName() {
        name = nombre.value;

        if (name.length == 0) {
            nameError.innerHTML = '*El tipo de componente es requerido';
            return false;
        }

        if (!name.match(/^[a-zA-ZÀ-ÿ\s]{4,20}$/)) {
            nameError.innerHTML = '*Digite una tipo de componente válido';
            return false;
        }

        nameError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }

    //*** METODOS PARA LA CRUD ***

    //Método para crear
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        if (!validateName()) {
            error.innerHTML = '*Por favor valide que los campos estén correctos';
            return;
        } else {
            error.innerHTML = '';

            // Función para guardar en minúsculas
            const nombreInput = document.getElementById("nombre");

            nombreInput.value = nombreInput.value.toLowerCase();

            let formData = new FormData(form);
            const formObject = Object.fromEntries(formData);
            console.log(formObject);

            axios.post('{{ route('tipos.store') }}', formData).
            then((result) => {
                console.log(result);
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
                console.log(error);
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

    function editar(id) {
        axios.get(`tiposcomponente/${id}/edit`)
            .then((result) => {
                let datos = result.data.data[0];

                $('#id').val(datos.id);
                $('#nombreE').val(datos.name);
            })
            .catch((error) => {
                console.log(error);
            })

        $('#EditTipoModal').modal('show');
    }

    // Método para actualizar un registro
    formEditar.addEventListener('submit', (event)=>{
        event.preventDefault();

        let formData = new FormData(formEditar);
        const formObject = Object.fromEntries(formData);

        axios.post('{{route('tipos.update')}}', formData)
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


    //Metodo para cambiar el estado del tipo de componente
    function cambiarEstado(id) {
        event.preventDefault();

        const url = '{{ route("tipos.index") }}';

        Swal.fire({
            title: "¿Estás seguro?",
            text: "Se cambiara el estado del registro seleccionado",
            icon: "warning",
            confirmButtonText: "Si",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                axios.get('tiposcomponente/' + id + '/changeState')
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
                axios.get('tiposcomponente/' + id + '/delete').then((result) => {
                    console.log(result);
                }).catch((error) => {
                    console.log(error);
                })
                window.location.href = url;
            }
        });
    }
</script>
