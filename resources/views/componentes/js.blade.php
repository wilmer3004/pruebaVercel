<script defer>
    //*** DATATABLES ***
    $id = 0;
    let table = $('#componentes').DataTable({
        "ajax": "{{ route('componentes.listar') }}",
        "columns": [{
                'data': 'id'
            },
            {
                'data': 'componente'
            },
            {
                'data': 'tipocomponente'
            },
            {
                'data': 'trimestre'
            },
            {
                'data': 'totalhoras'
            },
            {
                'data': 'descripcion'
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
                        <a class = "btn btn-sm mx-2 btn-danger tooltipA eliminar" href="{{ route('componentes.index') }}" onclick="cambiarEstado(${row.id})" data-tooltip="Deshabilitar" >
                            <i class="fa-solid fa-xmark"></i>
                        </a>`
                        : `
                        <a class = "btn btn-sm mx-2 btn-success tooltipA eliminar" href="{{ route('componentes.index') }}" onclick="cambiarEstado(${row.id})" data-tooltip="Habilitar" >
                            <i class="fa-solid fa-check"></i>
                        </a>`
                    }
                    `;
                    return`
                    <div class="row">
                        @role('superadmin|administrador')
                        <div class="btn-group col-3">
                            <div class="">
                                <a class="btn btn-sm btn-warning mx-2 tooltipA" data-tooltip="Editar" id="editar" onclick="editar(${row.id})">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                        @endrole
                        @role('superadmin')
                        <div class="btn-group col-3">
                            <div class="">
                                <a class="btn btn-sm btn-danger mx-2 tooltipA" data-tooltip="Eliminar" id="eliminar" onclick="eliminarId(${row.id})">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                        @endrole
                        @role('superadmin|administrador')
                        <div class="btn-group col-3">
                            ${enableButton}
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
    let descripcionError = document.getElementById('descripcion-error');
    let horaError = document.getElementById('horas-error');
    let form = document.getElementById('formulario');
    let formEditar = document.getElementById('formularioE');
    let error = document.getElementById('error');
    let nombre = document.getElementById('name');
    let descripcion = document.getElementById('descripcion');
    let horas = document.getElementById('horas');
    let program= document.querySelector('#programas')
    let programsE= document.querySelector('#programaE')
    let component_type= document.querySelector('#component_type')
    let component_typeE= document.querySelector('#tipoE')
    let arrayPrograms=[];
    let arrayProgramsE=[];
    let first_choice=null;


    // Select2 En la creacion del componente
    $(program).select2();

    function updateArrayPrograms() {
        // Borra todo los datos del array
        arrayPrograms.length =  0;
        // Para agregar todos los datos al array
        var selection = $(program).select2('data');
        selection.forEach(element => {
            arrayPrograms.push(element['id']);
        });
        console.log(arrayPrograms);
    }

    $(program).on('select2:select select2:unselect', function (e) {
        updateArrayPrograms();
    });

    // Select2 En la edicion del componente
    $(programsE).select2();

    function updateArrayProgramsE() {
        // Borra todo los datos del array
        arrayPrograms.length =  0;
        // Para agregar todos los datos al array
        var selection = $(programsE).select2('data');
        selection.forEach(element => {
            arrayPrograms.push(element['id']);
        });
        console.log(arrayPrograms);
    }

    $(programsE).on('select2:select select2:unselect', function (e) {
        updateArrayProgramsE();
    });



    function validateName() {
        name = nombre.value;

        if (name.length == 0) {
            nameError.innerHTML = '*El nombre del componente es requerido';
            return false;
        }

        if (!name.match(/^[a-zA-ZÀ-ÿ\s]{4,100}$/)) {
            nameError.innerHTML = '*Digite un componente válido';
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

    function validateHoras() {
        hours = horas.value;

        if (hours.length == 0) {
            horaError.innerHTML = '*La hora es requerida';
            return false;
        }

        if (!hours.match(/^\d{2,7}$/)) {
            horaError.innerHTML = '*Digite una hora válida';
            return false;
        }

        horaError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }

    //MÉTODOS PARA LA CRUD

    //Método para crear / registrar
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        if (!validateDescripcion() || !validateName() || !validateHoras()) {
            error.innerHTML = '*Por favor valide que los campos estén correctos';
            return;
        } else {
            error.innerHTML = '';

            // Función para guardar en minúsculas
            const nombreInput = document.getElementById("name");
            const descripcionInput = document.getElementById("descripcion");

            nombreInput.value = nombreInput.value.toLowerCase();
            descripcionInput.value = descripcionInput.value.toLowerCase();

            let formData = new FormData(form);
            const formObject = Object.fromEntries(formData);

            // Agrega cada valor del arrayPrograms a formData
            arrayPrograms.forEach(item => {
                formData.append('programs[]', item);
            });

            // Funcion para el resultado
            function handleResponse(result) {
                if (result.data.code === 505 || result.data.code === 500) {
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
            }

            // Funcion para el error
            function handleError(error) {
                Swal.fire({
                    title: "¡Ops..!",
                    text: error.response.data.message,
                    icon: "error",
                    showConfirmButton: true,
                    confirmButtonText: "Listo",
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                });
            }

            // Validacion para que cuando sea tecnica y el select2 tenga mas de dos elementos
            if(formObject['tipo'] == 1 && arrayPrograms.length > 1){
                Swal.fire({
                    title: "Aviso",
                    text: "¿Seguro desea relacionar este componente tecnico a los programas?",
                    icon: "warning",
                    confirmButtonText: "Si, aceptar",
                    showCancelButton: true,
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.post("{{ route('componentes.store') }}", formData)
                            .then(handleResponse)
                            .catch(handleError);
                    }
                });
            } else {
                axios.post("{{ route('componentes.store') }}", formData)
                    .then(handleResponse)
                    .catch(handleError);
            }


        }
    })

    // Método para traer los datos al formulario de editar
    function editar(id) {
        axios.get(`componentes/${id}/edit`)
            .then((result) => {
                let datos = result.data.data[0];
                $('#id').val(datos.id);
                $('#nombreE').val(datos.name);
                $('#descripcionE').val(datos.description);
                $('#trimestreE').val(datos.quarterId).find('option:selected').text(datos.quarter);
                $('#tipoE').val(datos.typeId).find('option:selected').text(datos.type);
                $('#totalE').val(datos.hours)

                $('#programaE').select2();
                let programIds = result.data.data2.map(item => item.program_id); // Extraer solo los IDs de los programas


                $('#programaE').val(programIds).trigger('change');


                console.log($('#programaE').val())

                for (var key in result.data.data2) {
                    if (result.data.data2.hasOwnProperty(key)) {
                        // Verificar si el program_id ya está en el array
                        if (!arrayPrograms.includes(result.data.data2[key].program_id)) {
                            // Si no está, insertarlo en el array
                            arrayPrograms.push(result.data.data2[key].program_id);
                        }
                    }
                }

            })
            .catch((error) => {
                console.log(error);
            });



        $('#EditComponenteModal').modal('show');
    }

    // Método para actualizar el registro
    formEditar.addEventListener('submit', (event) => {
        event.preventDefault();

        let formData = new FormData(formEditar);
        const formObject = Object.fromEntries(formData);

        // Agrega cada valor del arrayPrograms a formData
        arrayPrograms.forEach(item => {
            formData.append('programs[]', item);
        });
        console.log(arrayPrograms)


        // Funcion para el resultado
        function handleResponse(result) {
            if (result.data.code === 505 || result.data.code === 500) {
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
        }

        // Funcion para el error
        function handleError(error) {
            Swal.fire({
                title: "¡Ops..!",
                text: error.response.data.message,
                icon: "error",
                showConfirmButton: true,
                confirmButtonText: "Listo",
                allowEscapeKey: false,
                allowOutsideClick: false,
            });
        }


        // Validacion para que cuando sea tecnica y el select2 tenga mas de dos elementos
        if(formObject['tipo'] == 1 && arrayPrograms.length > 1){
            Swal.fire({
                title: "Aviso",
                text: "¿Seguro desea relacionar este componente tecnico a los programas?",
                icon: "warning",
                confirmButtonText: "Si, aceptar",
                showCancelButton: true,
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post("{{ route('componentes.update') }}", formData)
                        .then(handleResponse)
                        .catch(handleError);
                }
            });
        } else {
            axios.post("{{ route('componentes.update') }}", formData)
                .then(handleResponse)
                .catch(handleError);
        }

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
            if (result.isConfirmed) {
                axios.get('componentes/' + id + '/changeState')
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
                axios.get('componentes/' + id + '/delete').then((result) => {
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
