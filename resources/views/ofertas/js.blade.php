<script>
    //*** DATATABLES ***
    $id = 0;
    let table = $('#ofertas').DataTable({
        "ajax": "{{ route('ofertas.listar') }}",

        "columns": [{
                'data': 'id'
            },
            {
                'data': 'name'
            },
            {
                'data': 'state'
            },
            {
                'title': 'Acciones',
                'render': function(data, type, row) {
                    let enableButton= `
                    ${row.state.trim() === 'activo'
                        ? `
                        <a class = "btn mx-1 btn-danger tooltipA eliminar" href="{{ route('ofertas.index') }}" onclick="cambiarEstado(${row.id})" data-tooltip="Deshabilitar" >
                            <i class="fa-solid fa-xmark"></i>
                        </a>`
                        : `
                        <a class = "btn mx-1 btn-success tooltipA eliminar" href="{{ route('ofertas.index') }}" onclick="cambiarEstado(${row.id})" data-tooltip="Habilitar" >
                            <i class="fa-solid fa-check"></i>
                        </a>`
                    }
                    `;
                    return `
                    <div class="btn-group">
                        <div class="">
                            <a class="btn btn-warning mx-2 tooltipA" data-tooltip="Editar" onclick="editar(' + row.id + ')">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                        <div class="">
                            ${enableButton}
                        </div>
                    </div>`
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
    let form = document.getElementById('formulario');
    let formEditar = document.getElementById('formularioE');
    let error = document.getElementById('error');
    let nombre = document.getElementById('name');

    function validateName() {
        name = nombre.value;

        if (name.length == 0) {
            nameError.innerHTML = '*La oferta es requerida';
            return false;
        }

        if (!name.match(/^[a-zA-ZÀ-ÿ\s]{4,20}$/)) {
            nameError.innerHTML = '*Digite una oferta válida';
            return false;
        }

        nameError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }

    //*** MÉTODOS PARA LA CRUD ***

    //Método para crear
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        if (!validateName()) {
            error.innerHTML = '*Por favor valide que los campos estén correctos';
            return;
        } else {
            error.innerHTML = '';

            // Función para guardar en minúsculas
            const nombreInput = document.getElementById("name");

            nombreInput.value = nombreInput.value.toLowerCase();

            let formData = new FormData(form);
            const formObject = Object.fromEntries(formData);
            console.log(formObject);

            axios.post(' {{ route('ofertas.store') }}', formData).
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
        axios.get(`ofertas/${id}/edit`)
            .then((result) => {
                let datos = result.data.data[0];

                $('#id').val(datos.id);
                $('#nombreE').val(datos.name);
            })
            .catch((error) => {
                console.log(error);
            })

        $('#EditOfertaModal').modal('show');
    }

    // Método para actualizar un registro
    formEditar.addEventListener('submit', (event) => {
        event.preventDefault();

        let formData = new FormData(formEditar);
        const formObject = Object.fromEntries(formData);

        axios.post('{{ route('ofertas.update') }}', formData)
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

    //Método para eliminar
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
            if(result.isConfirmed){
                axios.get('ofertas/' + id + '/delete')
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
                })
                .catch((error) => {
                    console.log(error);
                    // Muestra una alerta de error en caso de error en la solicitud
                    Swal.fire({
                        title: "Error",
                        text: "Ocurrió un error al eliminar el registro",
                        icon: "error",
                        confirmButtonText: "Aceptar"
                    });
                });
            }
        });
    }
</script>
