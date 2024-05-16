<script>
    // *** DATATABLES ***
    $id = 0;
    let table = $('#ambientes').DataTable({
        "ajax": "{{ route('ambientes.listar') }}",
        "columns": [{
                'data': 'id'
            },
            {
                'data': 'ambiente'
            },
            {
                'data': 'sede'
            },
            {
                'data': 'piso'
            },
            {
                'data': 'capacidad'
            },
            {
                'data': 'componente'
            },
            {
                'data': 'state'
            },
            {
                'title': 'Acciones',
                'render': function(data, type, row) {
                    let enableButton = `
                ${row.state.trim() === 'activo'
                    ? `
                    <a class = "btn btn-sm btn-danger tooltipA eliminar" href="{{ route('ambientes.index') }}" onclick="cambiarEstado(${row.id})" data-tooltip="Deshabilitar" >
                        <i class="fa-solid fa-xmark"></i>
                    </a>`
                    : `
                    <a class = "btn btn-sm btn-success tooltipA eliminar" href="{{ route('ambientes.index') }}" onclick="cambiarEstado(${row.id})" data-tooltip="Habilitar" >
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
                            <a class="btn btn-sm btn-danger tooltipA eliminar" onclick="deleteEnviroment(${row.id})" data-tooltip="Eliminar">
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
                `;
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
        "rowsGroup": [5] // Especifica las columnas por las que deseas agrupar
    });

    // *** VALIDACIONES DEL FORMULARIO ***
    let nameError = document.getElementById('name-error');
    let pisoError = document.getElementById('piso-error');
    let capacidadError = document.getElementById('capacidad-error');
    let coordinationsError = document.getElementById('coordinations-error');
    let componentesError = document.getElementById('components-error');
    let form = document.getElementById('formulario');
    let formEditar = document.getElementById('formularioE');
    let error = document.getElementById('error');
    let nombre = document.getElementById('nombre');
    let piso = document.getElementById('piso');
    let capacidad = document.getElementById('capacidad');
    let componentes = document.getElementById('tipoComponentesAmbiente');
    let componentesSeleccionados = [];
    let arrayCoordinations = [];
    $('#coordinations').select2();
    let dataResponseValidation = {

        }
    function validateName() {
        name = nombre.value;

        if (name.length == 0) {
            nameError.innerHTML = '*El ambiente es requerido';
            return false;
        }

        if (!name.match(/^[a-zA-ZÀ-ÿ\s0-9]{4,20}$/)) {
            nameError.innerHTML = "*Digite un ambiente válido";
            return false;
        }

        nameError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }

    function validatePiso() {
        floor = piso.value;

        if (floor.length == 0) {
            pisoError.innerHTML = '*El piso es requerido';
            return false;
        }

        if (!floor.match(/^\d{1,3}$/)) {
            pisoError.innerHTML = '*Digite un piso válido';
            return false;
        }

        pisoError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }

    function validateCapacidad() {
        capacity = capacidad.value;

        if (capacity.length == 0) {
            capacidadError.innerHTML = '*La capacidad es requerida';
            return false;
        }

        if (capacity >= 100 || capacity <= 0) {
            capacidadError.innerHTML = '*Digite una capacidad válida';
            return false;
        }

        capacidadError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }

    function validateCoordination() {
        // Borra todo los datos del array
        arrayCoordinations.length = 0
        // Para agregar todos los datos al array
        var selection = $('#coordinations').select2('data');
        selection.forEach(element => {
            arrayCoordinations.push(element['id']);
        });

        if (arrayCoordinations.length == 0) {
            coordinationsError.innerHTML = '*Escoja una coordinacion válida';
            return false;
        }

        coordinationsError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }

    // Escuche cada vez que se selecciona un elemento en el Select2
    $('#coordinations').on('select2:select select2:unselect', function(e) {
        validateCoordination();
    });

    // Para ir agregando los ids en la edicion del ambiente
    function updateArrayProgramsE() {
        // Borra todo los datos del array
        arrayCoordinations.length = 0;
        // Para agregar todos los datos al array
        var selection = $('#coordinationsE').select2('data');
        selection.forEach(element => {
            arrayCoordinations.push(element['id']);
        });
    }

    $('#coordinationsE').on('select2:select select2:unselect', function(e) {
        updateArrayProgramsE();
    });

    function validateComponente() {
        if (tipoComponetesID.length === 0) {
            componentesError.innerHTML = "*Por favor seleccione el tipo de componente a dictar en ambiente";
            return false;
        }
        else {
            componentesError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
            return true;
        }
    }

    // *** MÉTODOS PARA LA CRUD ***

    // Array Tipo Componentes
    // Array para almacenar los IDs seleccionados
    var tipoComponetesID = [];
    // Obtener todos los checkboxes
    var checkboxes = document.querySelectorAll('input[name="componentes[]"]');

    // Escuchar los cambios en los checkboxes
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            // Verificar si el checkbox está marcado o desmarcado
            if (this.checked) {
                // Si está marcado, agregar el ID al array
                tipoComponetesID.push(this.value);
            } else {
                // Si está desmarcado, eliminar el ID del array
                var index = tipoComponetesID.indexOf(this.value);
                if (index !== -1) {
                    tipoComponetesID.splice(index, 1);
                }
            }

        });
    });

    //Método para crear
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        if (!validatePiso() || !validateName() || !validateCapacidad() || !validateCoordination() || !validateComponente()) {
            error.innerHTML = '*Por favor valide que los campos estén correctos';
            return;
        } else {
            error.innerHTML = '';

            //Función para guardar en minúsculas
            const nombreInput = document.getElementById("nombre");
            nombreInput.value = nombreInput.value.toLowerCase();

            let formData = new FormData(form);
            formData.append('tipoComponetesID', JSON.stringify(tipoComponetesID));
            const formObject = Object.fromEntries(formData);

            // Agrega cada valor del arrayPrograms a formData
            arrayCoordinations.forEach(item => {
                formData.append('coordinations[]', item);
            });

            axios.post('{{ route('ambientes.store') }}', formData).
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
                    console.log(result.data.datosTotal, result.data.ambiMaxSede)
                } else {

                    window.location.href = result.data.url;
                }
                console.log(result)
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
        axios.get(`ambientes/${id}/edit`)
            .then((result) => {
                let datos = result.data.data;

                componentesSeleccionados = []; // Inicializar para limpiar el array

                // Limpiar todas las selecciones anteriores
                $('.tipoComponenteCheckbox').prop('checked', false);

                datos.forEach((dato) => {
                    $('#id').val(dato.id);
                    $('#nombreE').val(dato.name);
                    $('#sedeE').val(dato.headquarterId).find('option:selected').text(dato.headquarter);
                    $('#pisoE').val(dato.floor);
                    $('#capacidadE').val(dato.capacity);

                    // Seleccionar las opciones correspondientes según los datos recibidos
                    if (Array.isArray(dato.componentId)) {
                        dato.componentId.forEach(componentId => {
                            $(`#tipoComponenteAmbienteE_${componentId}`).prop('checked', true);
                        });
                    } else {
                        $(`#tipoComponenteAmbienteE_${dato.componentId}`).prop('checked', true);
                        componentesSeleccionados.push(dato
                            .componentId); // Almacenar id de los elementos seleccionados
                    }
                });


                $('#coordinationsE').select2();
                // Extraer solo los IDs de las coordinaciones
                let coordinationsIds = result.data.data2.map(item => item.coordination_id);

                $('#coordinationsE').val(coordinationsIds).trigger('change');

                for (var key in result.data.data2) {
                    if (result.data.data2.hasOwnProperty(key)) {
                        // Verificar si el program_id ya está en el array
                        if (!arrayCoordinations.includes(result.data.data2[key].coordination_id)) {
                            // Si no está, insertarlo en el array
                            arrayCoordinations.push(result.data.data2[key].coordination_id);
                        }
                    }
                }

            })
            .catch((error) => {
                console.log(error);
            });

        $('#EditAmbienteModal').modal('show');
    }

    // Método para actualizar un registro
    formEditar.addEventListener('submit', (event) => {
        event.preventDefault();

        //Función para guardar en minúsculas
        const nombreInput = document.getElementById("nombreE");

        nombreInput.value = nombreInput.value.toLowerCase();

        let formData = new FormData(formEditar);
        const formObject = Object.fromEntries(formData);

        // Agrega cada valor del arrayPrograms a formData
        formData.append('confirmation',false);
        formData.append('confirmation2',false);
        arrayCoordinations.forEach(item => {
            formData.append('coordinations[]', item);
        });

        capacityEnv = formData.get('capacidad');
        FloorEnv = formData.get('piso');
        // Obtener todos los checkboxes con el nombre 'tipoComponenteAmbienteE[]'
        const checkboxes = document.querySelectorAll('input[name="tipoComponenteAmbienteE[]"]');


        validateCapacityEnv = capacityValidation(capacityEnv);
        validateFloorEnv = floorValidation(FloorEnv);

        if(validateCapacityEnv){

            titleValidateCapacityEnv = validateCapacityEnv.title;
            iconValidateCapacityEnv = validateCapacityEnv.icon;
            messageValidateCapacityEnv = validateCapacityEnv.message;

            alertErrorTextMessage(titleValidateCapacityEnv,iconValidateCapacityEnv,messageValidateCapacityEnv);

            return;
        }

        if(validateFloorEnv){

            titleValidateFloorEnv = validateFloorEnv.title;
            iconValidateFloorEnv = validateFloorEnv.icon;
            messageValidateFloorEnv = validateFloorEnv.message;

            alertErrorTextMessage(titleValidateFloorEnv,iconValidateFloorEnv,messageValidateFloorEnv);

            return;
        }
        let errorCheckBox = false;
        // Iterar sobre los checkboxes
        checkboxes.forEach(checkbox => {
            // Si el checkbox no está marcado, agregar un valor de 0 al FormData
            if (!checkbox.checked) {
                errorCheckbox = true; // Detener la ejecución si es necesario
            }else{
                errorCheckbox=false;
            }

        });

        if (errorCheckbox) {
            titleValidateCheckboxEnv = 'Ops..';
            iconValidateCheckboxEnv = 'error';
            messageValidateCheckboxEnv = 'Se debe de elegir por lo menos un tipo de componente';

            alertErrorTextMessage(titleValidateCheckboxEnv,iconValidateCheckboxEnv,messageValidateCheckboxEnv);

            return;
        }



        axios.post('{{ route('ambientes.update') }}', formData)
            .then((result) => {

                if (result.data.success) {
                    window.location.href = result.data.url;
                }else if(result.data.confirmation){
                    Swal.fire({
                            title: result.data.title,
                            text: result.data.message,
                            icon: "question",
                            confirmButtonText: "Sí, actualizar",
                            showCancelButton: true,
                            cancelButtonText: "Cancelar",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                formData.set('confirmation',true);
                                axios.post('{{ route('ambientes.update') }}', formData)
                                    .then((result) => {
                                        if(result.data.success){
                                            window.location.href = result.data.url;
                                        }else {
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
                }else if(result.data.confirmation2){
                    Swal.fire({
                            title: result.data.title,
                            text: result.data.message,
                            icon: "question",
                            confirmButtonText: "Sí, actualizar",
                            showCancelButton: true,
                            cancelButtonText: "Cancelar",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                formData.set('confirmation2',true);
                                axios.post('{{ route('ambientes.update') }}', formData)
                                    .then((result) => {
                                        if(result.data.success){
                                            window.location.href = result.data.url;
                                        }else {
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

    //Método para cambiar estado
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
                axios.get('ambientes/' + id + '/delete')
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
                                window.location.href = "/ambientes";
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


    //Funcion para realizar mensajes de alerta por respuesta de errores
    function alertErrorTextMessage(titleMessage, iconMessage, messageText){
        return Swal.fire({
                        title: titleMessage,
                        text: messageText,
                        icon:iconMessage,
                        confirmButtonText: "Aceptar"
                    });
    }


    // Validacion para piso
    function floorValidation(floorEnvironment) {
        let dataResponseValidation = {
            "title": "Ops...",
            "icon": "error",
            "message": ""
        };

        // Verificar si el valor es un número
        if (isNaN(floorEnvironment)) {
            dataResponseValidation.message = "El piso debe ser un número válido";
            return dataResponseValidation;
        }

        // Convertir el valor a número para realizar las comparaciones
        let floorNumber = parseInt(floorEnvironment, 10);

        // Verificar si el valor es mayor a 0 y menor o igual a 30
        if (floorNumber <= 0) {
            dataResponseValidation.message = "El piso debe ser mayor a 0";
            return dataResponseValidation;
        } else if (floorNumber > 30) {
            dataResponseValidation.message = "El piso debe ser menor o igual a 30";
            return dataResponseValidation;
        }

        // Si todas las validaciones pasan, retornar false para indicar que no hay errores
        return false;
    }
    //Validacion para capacidad

    function capacityValidation(capacityEnvironment) {
        let dataResponseValidation = {
            "title": "Ops...",
            "icon": "error",
            "message": ""
        };

        // Verificar si el valor es un número
        if (isNaN(capacityEnvironment)) {
            dataResponseValidation.message = "La capacidad debe ser un número válido";
            return dataResponseValidation;
        }

        // Convertir el valor a número para realizar las comparaciones
        let capacityNumber = parseInt(capacityEnvironment, 10);

        // Verificar si el valor es mayor a 0 y menor o igual a 40
        if (capacityNumber <= 0) {
            dataResponseValidation.message = "La capacidad debe ser mayor a 0";
            return dataResponseValidation;
        } else if (capacityNumber > 40) {
            dataResponseValidation.message = "La capacidad debe ser menor o igual a 40";
            return dataResponseValidation;
        }

        // Si todas las validaciones pasan, retornar false para indicar que no hay errores
        return false;
    }

    function deleteEnviroment(id) {
        const url = event.currentTarget.getAttribute("href");
        Swal.fire({
            title: "¿Estás seguro?",
            text: "Seguro desea borrar el ambiente ",
            icon: "warning",
            confirmButtonText: "Si",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                axios.get('ambientes/' + id + '/delete')
                    .then((result) => {
                        if (result.data.success) {
                            Swal.fire({
                                title: result.data.title,
                                text: result.data.message,
                                icon: "success",
                                confirmButtonText: "Aceptar"
                            }).then(() => {
                                // Redirige a otra página, si es necesario
                                window.location.href = "/ambientes";
                            });
                        } else {
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
