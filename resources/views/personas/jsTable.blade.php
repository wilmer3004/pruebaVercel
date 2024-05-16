<script defer>
    // *** DATATABLE ***
    @auth
        let idRol = @json(Auth::user()->roles->pluck('id')->toArray());
        var userRoles = @json(Auth::user()->roles->pluck('name'));
    @else
        var userRoles = [];
    @endauth

    let table = $('#personas').DataTable({
        "ajax": "{{ route('personas.listar') }}",    "ajax": {
        "url": "{{ route('personas.listar') }}",
        "type": "GET",
        "data": function (d) {
            d.idRol = idRol; // Replace 'yourValueHere' with the actual value you want to send
        }
    },        "columns": [{
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
                'data': 'phone'
            },
            {
                'data': 'state'
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
                            <a class="btn btn-sm btn-info mx-1 tooltipA" data-tooltip="Mostrar" onclick="show(${row.id})">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a class="btn btn-sm btn-warning mx-1 tooltipA" data-tooltip="Editar" onclick="edit(${row.id})">
                                <i class="fas fa-edit"></i>
                            </a>`;
                    }

                    if ((lowerCaseRoles.includes('superadmin') || lowerCaseRoles.includes('administrador')) && row.state === 'activo') {
                        buttons += `
                            <a class="btn btn-sm mx-1 btn-danger tooltipA eliminar" onclick="deshabilitarId(${row.id})" data-tooltip="Deshabilitar">
                                <i class="fas fa-user-slash"></i>
                            </a>`;
                    } else if ((lowerCaseRoles.includes('superadmin') || lowerCaseRoles.includes('administrador')) && row.state !== 'activo') {
                        buttons += `
                            <a class="btn btn-sm mx-1 btn-success tooltipA eliminar" onclick="habilitarId(${row.id})" data-tooltip="Habilitar">
                                <i class="fas fa-user"></i>
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

    // Método para deshabilitar

    function deshabilitarId(id) {
        event.preventDefault();

        const url = '{{ route("personas.index")}}';

        Swal.fire({
            title: "¿Estás seguro?",
            text: "Se deshabilitará el registro seleccionado",
            icon: "warning",
            confirmButtonText: "Si, deshabilitar",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                axios.get('personas/' + id + '/disable').then((result) => {
                    console.log(result);
                }).catch((error) => {
                    console.log(error);
                })
                window.location.href = url;
            }
        });
    }
    // Función para mostrar

    function show(id) {
        window.location.href = `/personas/${id}/show`;
    }

    function edit(id) {
        window.location.href = `/personas/${id}/edit`;
    }

    // Método para habilitar

    function habilitarId(id) {
        event.preventDefault();

        const url = '{{ route("personas.index")}}';

        Swal.fire({
            title: "¿Estás seguro?",
            text: "Se habilitará el registro seleccionado",
            icon: "warning",
            confirmButtonText: "Si, habilitar",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                axios.get('personas/' + id + '/enable').then((result) => {
                    console.log(result);
                }).catch((error) => {
                    console.log(error);
                })
                window.location.href = url;
            }
        });
    }

    @if (isset($error))
        Swal.fire({
            icon: 'error',
            title: '!Ops...¡',
            text: 'Ha ocurrido un error inesperado',
        });
    @endif
</script>
