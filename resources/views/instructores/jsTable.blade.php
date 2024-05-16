<script defer>
    // *** DATATABLE ***
    // let userRoles = {!! json_encode(auth()->user()->roles->pluck('name')) !!};
    axios.get("{{ route('instructores.listar') }}")
        .then((response) => {
            console.log(response);
        })
    let table = $('#instructores').DataTable({
        "ajax": "{{ route('instructores.listar') }}",
        "columns": [{
                'data': 'id'
            },
            {
                'data': 'name'
            },
            {
                'data': 'lastname'
            },
            {
                'data':'nicknames'
            },
            {
                'data': 'document'
            },
            {
                'data': 'email'
            },
            {
                'data': 'contract'
            },
            {
                'data': 'state'
            },
            {
                'title': 'Acciones',
                'render': function(data, type, row) {
                    let enableButton = `
                    ${row.state === 'activo'
                    ? `
                    <a class = "btn btn-sm mx-1 btn-danger tooltipA eliminar" href="{{ route('instructores.index') }}" onclick="deshabilitarId(${row.id})" data-tooltip="Deshabilitar" >
                        <i class ="fas fa-user-slash"> </i>
                    </a>`
                    : `
                    <a class = "btn btn-sm mx-1 btn-success tooltipA eliminar" href="{{ route('instructores.index') }}" onclick="habilitarId(${row.id})" data-tooltip="Habilitar" >
                        <i class ="fas fa-user"> </i>
                    </a>`
                    }
                    `;
                    return `
                    <div class="d-flex justify-content-around">
                        @role('superadmin|administrador')
                        <div class=" col-3">
                            <a class="btn btn-sm btn-info mx-1 tooltipA" data-tooltip="Mostrar" id="show" onclick="show(${row.id})">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                        @endrole
                        @role('superadmin|administrador')
                        <div class=" col-3">
                            <a class="btn btn-sm btn-warning mx-1 tooltipA" data-tooltip="Editar" id="editar" onclick="edit(${row.id})">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                        @endrole
                        @role('superadmin|administrador')
                        <div class="col-3">
                            ${enableButton}
                        </div>
                        @endrole
                        @role('superadmin|administrador')
                        <div class="col-3">
                            <a class="btn btn-sm btn-danger tooltipA eliminar" href="{{ route('instructores.index') }}" onclick="destroy(${row.id})" data-tooltip="Eliminar">
                                <i class="fas fa-trash"></i>
                            </a>
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

    // if (!userRoles.includes('superadmin') && !userRoles.includes('administrador')) {
    //     table.column('.acciones-column').visible(false);
    // }

    // *** Mostrar ****
    let sNombre = document.querySelector('#nombre');

    function show(id) {
        window.location.href = `/instructores/${id}/show`;
    }

    function edit(id) {
        window.location.href = `/instructores/${id}/edit`;
    }

    // Método para deshabilitar

    function deshabilitarId(id) {
        event.preventDefault();

        const url = event.currentTarget.getAttribute("href");

        Swal.fire({
            title: "¿Estás seguro?",
            text: "Se deshabilitará el registro seleccionado",
            icon: "warning",
            confirmButtonText: "Si, deshabilitar",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                axios.get('instructores/' + id + '/disable').then((result) => {
                    console.log(result);
                    if (result.data.evento == true) {
                        Swal.fire({
                            title: "Recordar",
                            text: "Se deshabilito el instructor, recuerde asignar un nuevo instructor a los eventos que estaban para el/la instructor(a)",
                            icon: "success",
                            showConfirmButton: true,
                            confirmButtonText: "Listo",
                            allowEscapeKey: false,
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = url;
                            }
                        })
                    } else {
                        window.location.href = url;

                    }
                }).catch((error) => {
                    console.log(error);
                })
            }
        });
    }

    // Método para habilitar

    function habilitarId(id) {
        event.preventDefault();

        const url = event.currentTarget.getAttribute("href");

        Swal.fire({
            title: "¿Estás seguro?",
            text: "Se habilitará el registro seleccionado",
            icon: "warning",
            confirmButtonText: "Si, habilitar",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                axios.get('instructores/' + id + '/enable').then((result) => {
                    console.log(result);
                }).catch((error) => {
                    console.log(error);
                })
                window.location.href = url;
            }
        });
    }

    function destroy(id) {
        event.preventDefault();
        const url = event.currentTarget.getAttribute("href");
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Seguro desea eliminar un instructor, no podra usar al mismo para la realizacion de programaciones.",
            icon: "warning",
            confirmButtonText: "Si, eliminar",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                axios.get('instructores/' + id + '/delete')
                    .then((response) => {
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
                            text: "Por favor, contactese con soporte, hubo un error en la eliminación del trimestre",
                            showCancelButton: "Listo",
                            allowEscapeKey: false,
                            allowOutsideClick: false,
                        })
                    });
            }
        })
    }

    @if (isset($error))
        Swal.fire({
            icon: 'error',
            title: '!Ops...¡',
            text: 'Ha ocurrido un error inesperado',
        });
    @endif
</script>
