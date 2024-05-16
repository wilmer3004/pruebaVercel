<script>
    // *** DATATABLES ***
    $id = 0;
    let table = $('#bloques').DataTable({
        "ajax": "{{ route('bloques.listar') }}",
        "columns": [{
                'data': 'id'
            },
            {
                'data': 'jornada'
            },
            {
                'data': 'hora_inicio'
            },
            {
                'data': 'hora_fin'
            },
            {
                'title': 'Acciones',
                'render': function(data, type, row) {
                    let enableButton = `
                    ${row.state == true
                        ? `
                        <a class = "btn btn-sm btn-danger tooltipA eliminar mx-2" href="{{ route('bloques.index') }}" onclick="changeState(${row.id})" data-tooltip="Deshabilitar" >
                            <i class="fa-solid fa-xmark"></i>
                        </a>`
                        : `
                        <a class = "btn btn-sm btn-success tooltipA eliminar mx-2" href="{{ route('bloques.index') }}" onclick="changeState(${row.id})" data-tooltip="Habilitar" >
                            <i class="fa-solid fa-check"></i>
                        </a>`
                    }`;
                    return `
                    <div class="d-flex justify-content-around">
                        @role('superadmin|administrador')
                        <div class="btn-group col-3">
                            <div class="">
                                <a class="btn btn-sm btn-warning mx-2 tooltipA" data-tooltip="Editar" id="editar" onclick="editar(${row.id})">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                        @endrole
                        @role('superadmin|administrador')
                        <div class="btn-group col-3">
                            <div class="">
                                <a class="btn btn-sm btn-danger mx-2 tooltipA eliminar" href="{{ route('bloques.index') }}" onclick="eliminarId(${row.id})" data-tooltip="Eliminar">
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

    let horaI = document.getElementById("hora_inicio");
    let horaF = document.getElementById("hora_fin");
    let form = document.querySelector('#formulario');
    let formEditar = document.querySelector('#formularioE');


    // *** MÉTODOS PARA LA CRUD ***

    // CREATE
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        let formData = new FormData(form);
        const formObject = Object.fromEntries(formData);

        axios.post('{{ route('bloques.store') }}', formData).
        then((result) => {
            if(result.data.success){
                console.log(result);
                window.location.href = result.data.url;
            }else{
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
    })

    // Edit
    function editar(id) {
        axios.get(`bloques/${id}/edit`)
            .then((result) => {
                let datos = result.data.data[0];

                $('#id').val(datos.id);
                $('#jornadaE').val(datos.jornadaId).find('option:selected').text(datos.jornada);
                $('#hora_inicioE').val(datos.hora_inicio);
                $('#hora_finE').val(datos.hora_fin);
            })
            .catch((error) => {
                console.log(error);
            })

        $('#EditBloquesModal').modal('show');
    }

    // UPDATE
    formEditar.addEventListener('submit', (event) => {
        event.preventDefault();

        let formData = new FormData(formEditar);
        const formObject = Object.fromEntries(formData);

        axios.post('{{ route('bloques.update') }}', formData)
            .then((result) => {
                if(result.data.success){
                    window.location.href = result.data.url;
                }else{
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
    })

    // STATE
    function changeState(id) {
        event.preventDefault();

        const url = event.currentTarget.getAttribute("href");

        Swal.fire({
            title: "¿Estas seguro?",
            text: "Cambiar el estado del bloque conllevara en no realizar programaciones dentro de ese espacio tiempo.",
            icon: "warning",
            confirmButtonText: "Si, cambiar estado",
            showCancelButton: true,
            cancelButtonText: "Cancelar"
        }).then((result)=>{
            if(result.isConfirmed) {
                axios.get('bloques/' + id + '/state').then((result)=>{
                    console.log(result.data.success);
                    if (result.data.success) {
                        Swal.fire({
                            title: result.data.title,
                            text: result.data.message,
                            icon: "success",
                            confirmButtonText: "Aceptar"
                        }).then(() => {
                            window.location.href = url;
                        });
                    }
                    else {
                        Swal.fire({
                            title: "Error",
                            text: result.data.message,
                            icon: "error",
                            confirmButtonText: "Aceptar"
                        });
                    }
                }).catch((error) => {
                    console.log(error);
                });
            }
        });
    }

    // DELETE
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
                axios.get('bloques/' + id + '/delete').then((result) => {
                    console.log(result);
                }).catch((error) => {
                    console.log(error);
                })
                window.location.href = url;
            }
        });
    }
</script>
