<script>
    //*** DATATABLES ***
    $id = 0;
    let table = $('#trimestresanio').DataTable({
        "ajax": "{{ route('fechasanio.listar') }}",

        "columns": [{
                'data': 'quarter'
            },
            {
                'data': 'start_date'
            },
            {
                'data': 'finish_date'
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
                        <a class = "btn btn-sm btn-danger tooltipA eliminar" href="{{ route('fechasanio.index') }}" onclick="cambiarEstado(${row.id})" data-tooltip="Deshabilitar" >
                            <i class="fa-solid fa-xmark"></i>
                        </a>`
                        : `
                        <a class = "btn btn-sm btn-success tooltipA eliminar" href="{{ route('fechasanio.index') }}" onclick="cambiarEstado(${row.id})" data-tooltip="Habilitar" >
                            <i class="fa-solid fa-check"></i>
                        </a>`
                    }
                    `;
                    return `
                    <div class="d-flex justify-content-around align-items-center">
                        @role('superadmin|administrador')
                        <div class="btn-group">
                            <div class="">
                                <a class="btn btn-sm btn-warning tooltipA" data-tooltip="Editar" id="editar" onclick="editar(${row.id})">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </div>
                        @endrole
                        @role('superadmin|administrador')
                        <div class="btn-group">
                            <div class="">
                                <a class="btn btn-sm btn-danger tooltipA eliminar" href="{{ route('fechasanio.index') }}" onclick="eliminarId(${row.id})" data-tooltip="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                        <div class="btn-group">
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
            "order": [],
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
        "ordering": false,
    });

    //VALIDACIONES DEL FORMULARIO
    var quarterError = document.getElementById("quarter-error");
    let quarterErrorE = document.getElementById("quarter-errorE");
    let yearErrorE = document.getElementById('year-errorE');
    var fechasError = document.getElementById("fechas-error");
    let form = document.querySelector('#formulario');
    let formEditar = document.querySelector('#formularioE');
    let error = document.querySelector('#error');
    let errorE = document.querySelector('#errorE');

    // Para mostrar los 5 años anteriores y los 5 siguientes
    const yearC = document.querySelector('#year');
    const currentYear = new Date().getFullYear();

    for (let i = currentYear - 5; i <= currentYear + 5; i++) {
        const option = document.createElement('option');
        option.value = i;
        option.textContent = i;
        yearC.appendChild(option);
    }

    // Para que solo se pueda escoger dentro de ese año
    function setMinMax() {

        const startInput = document.getElementById('start');
        const endInput = document.getElementById('end');

        if (yearC.value > 0) {
            startInput.readOnly = false;
            endInput.readOnly = false;
        }

        startInput.value = '';
        endInput.value = '';


        startInput.min = `${yearC.value}-01-01`;
        startInput.max = `${yearC.value}-12-31`;

        endInput.min = `${yearC.value}-01-01`;
        endInput.max = `${yearC.value}-12-31`;
    }

    const startLis = document.querySelector('#start');
    startLis.addEventListener('click', setMinMax);

    /* Validación formulario crear */
    function validateQuarter() {
        let quarter = document.getElementById('quarter')
        const validateRegrexNumber = /^([1-4])$/;

        if (quarter.value <= 0 || quarter.value > 4) {
            quarterError.innerHTML = "*Digite trimestre entre el 1 y 4.";
            return false;
        }
        if (!validateRegrexNumber.test(quarter.value)) {
            quarterError.innerHTML = "*Digite un trimestre valido.";
            return false;
        }
        if (validateRegrexNumber.test(quarter.value)) {
            quarterError.innerHTML =
                '<p><i class="fas fa-circle-check me-1"></i> El campo es válido.</p> ';
            return true;
        }
    }

    /* Validación formulario edición */
    function validateQuarterE() {
        let quarterE = document.getElementById('quarterE')
        const validateRegrexNumber = /^([1-4])$/;

        if (quarterE.value <= 0 || quarterE.value > 4) {
            quarterErrorE.innerHTML = "*Digite trimestre entre el 1 y el 4.";
            return false;
        }
        if (!validateRegrexNumber.test(quarterE.value)) {
            quarterErrorE.innerHTML = "*Digite un trimestre valido.";
            return false;
        }
        if (validateRegrexNumber.test(quarterE.value)) {
            quarterErrorE.innerHTML =
                '<p><i class="fas fa-circle-check me-1"></i> El campo es válido.</p> ';
            return true;
        }
    }

    //*** MÉTODOS PARA LA CRUD ***

    // Método para crear
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        const start = new Date(document.getElementById("start").value);
        const end = new Date(document.getElementById("end").value);

        // Sumar un día a la fecha de inicio
        start.setDate(start.getDate() + 1);

        // Sumar un día a la fecha de fin
        end.setDate(end.getDate() + 1);

        if (end <= start) {
            document.getElementById("error-fechas").innerHTML =
                '<p style="color: red">*La fecha final no puede ser menor a la fecha de inicio.</p>';
            return;
        }
        // Calcular la diferencia en meses entre la fecha de inicio y la fecha final
        const diferenciaMeses =
            (end.getFullYear() - start.getFullYear()) * 12 +
            (end.getMonth() - start.getMonth());

        // Verificar si la diferencia en meses es menor a 2
        if (diferenciaMeses < 2) {
            document.getElementById("error-fechas").innerHTML =
                '<p style="color: red">*El rango de fechas debe ser mayor o igual a 2 meses.</p>';
            return;
        } else if (diferenciaMeses > 3) {
            document.getElementById("error-fechas").innerHTML =
                '<p style="color: red">*El rango de fechas no debe ser mayor a 3 meses.</p>';
            return;
        }

        if (!validateQuarter()) {
            error.innerHTML = '*Por favor valide que los campos estén correctos';
            return;

        } else {
            error.innerHTML = '';
            let formData = new FormData(form);
            const formObject = Object.fromEntries(formData);

            axios.post("{{ route('fechasanio.store') }}", formData).
            then((result) => {
                if (result.data.code === 500 || result.data.code === 505) {
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

                    Swal.fire({
                        title: "Exito!",
                        text: "Se registro el trimestre del año correctamente",
                        icon: "success",
                        confirmButtonText: "Ok",
                        showCancelButton: false,
                    }).then(() => {
                        location.reload(); // Recarga la página
                    });

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

    //Método para traer los datos al formulario de editar
    function editar(id) {
        axios.get(`fechasanio/${id}/edit`)
            .then((result) => {

                // Obtener el elemento select por su ID
                var selectElement = document.getElementById('selectYearE');

                // Limpia las opciones existentes
                selectElement.innerHTML = '';

                let datos = result.data.data[0];

                // Generar las opciones para los años hacia atrás y adelante basadas en datos.year
                for (let i = datos.year - 5; i <= datos.year + 5; i++) {
                    // Crea un nuevo elemento option
                    var optionElement = document.createElement('option');

                    // Establecer el valor y el texto que se mostrará para el option
                    optionElement.value = i;
                    optionElement.text = i;

                    // Añadir el nuevo option al select si el valor coincide con el año obtenido
                    if (i === datos.year) {
                        optionElement.selected = true; // Marcar la opción como seleccionada
                    }

                    // Añadir las nuevos options al select
                    selectElement.appendChild(optionElement);
                }

                /* Insertar elementos en input */
                $('#quarterE').val(datos.quarter);
                $('#startE').val(datos.start_date);
                $('#endE').val(datos.finish_date);
                $('#id').val(datos.id);
            })
            .catch((error) => {
                console.log(error);
            })

        $('#EditTrimestreAniolModal').modal('show');

    }

    //Método para actualizar un registro
    formEditar.addEventListener('submit', (event) => {
        event.preventDefault();

        document.getElementById("error-fechas").innerHTML = '';

        const start = new Date(document.getElementById("startE").value);
        const end = new Date(document.getElementById("endE").value);

        // Sumar un día a la fecha de inicio
        start.setDate(start.getDate() + 1);

        // Sumar un día a la fecha de fin
        end.setDate(end.getDate() + 1);

        if (end <= start) {
            document.getElementById("error-fechas").innerHTML =
                '<p style="color: red">*La fecha final no puede ser menor a la fecha de inicio.</p>';
            return;
        }

        // Validacion meses
        // Calcular la diferencia en meses entre la fecha de inicio y la fecha final
        const diferenciaMeses =
            (end.getFullYear() - start.getFullYear()) * 12 +
            (end.getMonth() - start.getMonth());

        // Verificar si la diferencia en meses es menor a 2
        if (diferenciaMeses < 2) {
            document.getElementById("error-fechasE").innerHTML =
                '<p style="color: red">*El rango de fechas debe ser mayor o igual a 2 meses.</p>';
            return;
        } else if (diferenciaMeses > 3) {
            document.getElementById("error-fechasE").innerHTML =
                '<p style="color: red">*El rango de fechas no debe ser mayor a 3 meses.</p>';
            return;
        }

        const year = parseInt(document.getElementById("selectYearE").value);

        // Verificar que el mes coincida con el año al que pertenece el trimestre
        if (start.getFullYear() != year) {

            document.getElementById("start-errorE").innerHTML =
                '<p style="color: red">*El año del mes inicial no coincide con el año al que pertenece el trimestre.</p>';
            return;

        } else {

            document.getElementById("start-errorE").innerHTML = '';

        }

        // Verificar que el mes coincida con el año al que pertenece el trimestre
        if (end.getFullYear() != year) {

            document.getElementById("end-errorE").innerHTML =
                '<p style="color: red">*El año del mes final no coincide con el año al que pertenece el trimestre.</p>';
            return;

        } else {

            document.getElementById("end-errorE").innerHTML = '';

        }

        // Verificar si ambas fechas son válidas
        if (end.getMonth() < start.getMonth()) {

            document.getElementById("error-fechasE").innerHTML =
                '<p style="color: red">*El mes final no puede ser menor al mes de inicio.</p>';
            return;

        }

        if (!validateQuarterE()) {

            errorE.innerHTML = '*Por favor valide que los campos estén correctos';
            return;

        } else {

            errorE.innerHTML = '';
            let formData = new FormData(formEditar);
            const formObject = Object.fromEntries(formData);
            axios.post("{{ route('fechasanio.update') }}", formData)
                .then((result) => {
                    if (result.data.success) {
                        Swal.fire({
                            title: "¡Guardado!",
                            text: result.data.message,
                            icon: "success",
                            showConfirmButton: true,
                            confirmButtonText: "Listo",
                            allowEscapeKey: false,
                            allowOutsideClick: false,
                        });
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
                axios.get('fechasanio/' + id + '/delete').then((result) => {
                    Swal.fire({
                        title: result.data.title,
                        text: result.data.message,
                        icon: result.data.icon,
                        showConfirmButton: true,
                        confirmButtonText: "Listo",
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                    })
                    result.data.success ? window.location.href = url : "";
                }).catch((error) => {
                    console.log(error);
                })

            }
        });
    }

    //Metodo para cambiar estado
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
                axios.get('fechasanio/' + id + '/changeState')
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
