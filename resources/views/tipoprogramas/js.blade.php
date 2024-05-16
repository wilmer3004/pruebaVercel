<script>
    //*** DATATABLES ***
    $id = 0;
    let table = $('#tipoprograma').DataTable({
        "ajax": "{{ route('tiposprograma.listar') }}",

        "columns": [{
                'data': 'id'
            },
            {
                'data': 'name'
            },
            {
                'title': 'Acciones',
                'render': function(data, type, row) {
                    let enableButton = `
                    ${row.state == true
                        ? `
                        <a class = "btn btn-danger tooltipA eliminar mx-2" href="{{ route('tiposprograma.index') }}" onclick="changeState(${row.id})" data-tooltip="Deshabilitar" >
                            <i class="fa-solid fa-xmark"></i>
                        </a>`
                        : `
                        <a class = "btn btn-success tooltipA eliminar mx-2" href="{{ route('tiposprograma.index') }}" onclick="changeState(${row.id})" data-tooltip="Habilitar" >
                            <i class="fa-solid fa-check"></i>
                        </a>`
                    }
                    `;
                    return `<div class="btn-group"> <div class=""> <a class="btn btn-warning mx-2 tooltipA" data-tooltip="Editar" onclick="editar(` +
                        row.id +
                        `)"><i class="fas fa-edit"></i></a></div> <div class=""><a class="btn btn btn-danger tooltipA eliminar" href="{{ route('tiposprograma.index') }}" onclick="eliminarId(` +
                        row.id +
                        `)" data-tooltip="Eliminar"><i class="fas fa-trash"></i></a></div> </div>` +
                        `<div class="btn-group"><div class="">` + enableButton + `</div></div>`;

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
    let form = document.getElementById('formulario');
    let formEditar = document.getElementById('formularioE');
    let error = document.getElementById('error');

    function validateName() {
        let name = document.getElementById('name').value;

        if (name.length == 0) {
            nameError.innerHTML = '*El tipo de programa es requerido';
            return false;
        }

        if (!name.match(/^[a-zA-ZÀ-ÿ\s]{4,20}$/)) {
            nameError.innerHTML = '*Digite un tipo de programa válido';
            return false;
        }

        nameError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }

    //*** MÉTODOS PARA LA CRUD ***

    // CREATE
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

            axios.post('{{ route('tiposprograma.store') }}', formData).
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
        axios.get(`tipo-programa/${id}/edit`)
            .then((result) => {
                let datos = result.data.data[0];

                $('#id').val(datos.id);
                $('#nombreE').val(datos.name);
            })
            .catch((error) => {
                console.log(error);
            })

        $('#EditTipopModal').modal('show');
    }

    // Update
    formEditar.addEventListener('submit', (event) => {
        event.preventDefault();

        let formData = new FormData(formEditar);
        const formObject = Object.fromEntries(formData);

        axios.post('{{ route('tiposprograma.update') }}', formData)
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

    // CHANGE STATE
    function changeState(id) {
        event.preventDefault();

        const url = event.currentTarget.getAttribute("href");
        Swal.fire({
            title: "¿Estas seguro?",
            text: "Cambiar el estado de programa conlleva en la no programación de programas de ese tipo",
            icon: "warning",
            confirmButtonText: "Si, cambiar estado",
            showCancelButton: true,
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                axios.get('tipo-programa/' + id + '/changeState').then((result) => {
                    console.log(result.data.success);
                    if (result.data.success) {
                        Swal.fire({
                            title: result.data.title,
                            text: result.data.message,
                            icon: "success",
                            confirmButtonText: "Aceptar"
                        }).then(() => {
                            window.location.href = url;
                        })
                    } else {
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
        })
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
                axios.get('tipo-programa/' + id + '/delete').then((result) => {
                    console.log(result);
                }).catch((error) => {
                    console.log(error);
                })
                window.location.href = url;
            }
        });
    }
</script>
