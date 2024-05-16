<script>
        // ******RECOLECCION DE ROLESA******
        @auth
            var userRoles = @json(Auth::user()->roles->pluck('name'));
        @else
            var userRoles = [];
        @endauth
    //*** DATATABLES ***
    $id = 0;
    let table = $('#contrato').DataTable({
        "ajax": "{{ route('contratos.listar') }}",

        "columns": [{
                'data': 'id'
            },
            {
                'data': 'name'
            },
            {
                'data': 'total_hours'
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
                            <a class="btn btn-sm btn-warning tooltipA" href="{{ route('contratos.index') }}" data-tooltip="Editar" onclick="editar(${row.id})">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a class="btn btn-sm btn-danger tooltipA" href="{{ route('contratos.index') }}" data-tooltip="Eliminar" onclick="eliminarId(${row.id}">
                                <i class="fas fa-trash"></i>
                            </a>`;
                    }

                    if ((lowerCaseRoles.includes('superadmin') || lowerCaseRoles.includes('administrador')) && row.state === true) {
                        buttons += `
                            <a class = "btn btn-sm btn-danger tooltipA eliminar" href="{{ route('contratos.index') }}" onclick="cambiarEstado(${row.id})" data-tooltip="Deshabilitar" >
                                <i class="fa-solid fa-xmark"></i>
                            </a>`;
                    } else if ((lowerCaseRoles.includes('superadmin') || lowerCaseRoles.includes('administrador')) && row.state !== true) {
                        buttons += `
                                <a class = "btn  btn-sm btn-success tooltipA eliminar" href="{{ route('contratos.index') }}" onclick="cambiarEstado(${row.id})"  data-tooltip="Habilitar" >
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
    let hourError = document.getElementById('hour-error');
    let form = document.getElementById('formulario');
    let formEditar = document.getElementById('formularioE');
    let error = document.getElementById('error');
    let nombre = document.getElementById('name');
    let hora = document.getElementById('hora');

    function validateName() {
        name = nombre.value;

        if (name.length == 0) {
            nameError.innerHTML = '*El contrato es requerido';
            return false;
        }

        if (!name.match(/^[a-zA-ZÀ-ÿ\s]{4,20}$/)) {
            nameError.innerHTML = '*Digite un contrato válido';
            return false;
        }

        nameError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }

    function validateHour() {
        hour = hora.value;

        if (hour.length == 0) {
            hourError.innerHTML = '*El total de horas es requerido';
            return false;
        }

        if (!hour.match(/^\d{1,3}$/)) {
            hourError.innerHTML = 'Digite un campo válido';
            return false;
        }

        hourError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p>';
        return true;
    }

    //*** MÉTODOS PARA LA CRUD ***

    //Método para crear
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        if (!validateName() || !validateHour()) {
            error.innerHTML = '*Por favor valide que los campos estén correctos';
            return;
        } else {
            error.innerHTML = '';

            // Función para guardar en minúsculas
            const nombreInput = document.getElementById("name");

            nombreInput.value = nombreInput.value.toLowerCase();

            let formData = new FormData(form);
            const formObject = Object.fromEntries(formData);

            axios.post('{{ route('contratos.store') }}', formData).
            then((result) => {
                if (result.data.success === false) {
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
        axios.get(`contratos/${id}/edit`)
            .then((result) => {
                let datos = result.data.data[0];

                $('#id').val(datos.id);
                $('#nombreE').val(datos.name);
                $('#horaE').val(datos.total_hours);
            })
            .catch((error) => {
            })

        $('#EditContratoModal').modal('show');
    }

    // Método para actualizar un registro
    formEditar.addEventListener('submit', (event) => {
        event.preventDefault();



        validation = {};



        let formData = new FormData(formEditar);
        formData.append('confirmation',false);
        const formObject = Object.fromEntries(formData);
        const hoursForm = formData.get('hora');
        const validationHoursV = validationHours(hoursForm);

        //Validacion campos nombre
        const nameForm = formData.get('nombre');
        const validationNameV = validationName(nameForm);

        if(validationNameV){
            titleValidateName = validationNameV.title;
            iconValidateName = validationNameV.icon;
            messageValidateName = validationNameV.message;

            alertErrorTextMessage(titleValidateName,iconValidateName,messageValidateName);

            return;
        }
        if(validationHoursV){
            titleValidateHour = validationHoursV.title;
            iconValidateHour = validationHoursV.icon;
            messageValidateHour = validationHoursV.message;

            alertErrorTextMessage(titleValidateHour,iconValidateHour,messageValidateHour);

            return;
        }


        axios.post('{{ route('contratos.update') }}', formData)
        .then((result) => {
                if (result.data.success) {
                    window.location.href = result.data.url;
                }else if(result.data.confirmationHour){
                    Swal.fire({
                        title: "¿Estás seguro de modificar las horas?",
                        text: result.data.message,
                        icon: "warning",
                        confirmButtonText: "Si",
                        showCancelButton: true,
                        cancelButtonText: "Cancelar",
                    }).then((alertResponse)=>{
                        if(alertResponse.isConfirmed){
                            formData.set('confirmation', true);
                            axios.post('{{ route('contratos.update') }}',formData)
                                .then((responseConfirmation)=>{
                                    if (responseConfirmation.data.success) {

                                        window.location.href = responseConfirmation.data.url;
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
                                }).catch((error)=>{
                                    alertErrorTextMessage(
                                        "¡Ocurrio un error en el sistema!",
                                        "error",
                                        "Error 500"
                                    );
                                });
                            }
                    })

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
                axios.get('contratos/' + id + '/delete').then((result) => {
                }).catch((error) => {
                })
                window.location.href = url;
            }
        });
    }

//Metodo para cambiar el estado del contrato
function cambiarEstado(id) {
        event.preventDefault();

        const url = '{{ route("contratos.index") }}';

        Swal.fire({
            title: "¿Estás seguro?",
            text: "Se cambiara el estado del registro seleccionado",
            icon: "warning",
            confirmButtonText: "Si",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
        }).then((result) => {
            if (result.isConfirmed) {
                axios.get('contratos/' + id + '/changeState')
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


    function alertErrorTextMessage(titleMessage, iconMessage, messageText){
        return Swal.fire({
                        title: titleMessage,
                        text: messageText,
                        icon:iconMessage,
                        confirmButtonText: "Aceptar"
                    });
    }

    function validationHours(hours){
        let dataResponseValidation = {
            "title": "Ops...",
            "icon": "error",
            "message": ""
        };

        if(hours<80){
            dataResponseValidation.message = "El campo horas debe ser mayor o igual a 80";
            return dataResponseValidation;
        }
        if(hours>200){
            dataResponseValidation.message = "El campo horas debe ser menor o igual a 200";
            return dataResponseValidation;
        }

        return false;
    }

    function validationName(name){
        let dataResponseValidation = {
            "title": "Ops...",
            "icon": "error",
            "message": ""
        };

        if(name.length>20){
            dataResponseValidation.message = "El campo nombre no puede ser mayor a 20 caracteres";
            return dataResponseValidation;
        }

        return false;
    }


</script>
