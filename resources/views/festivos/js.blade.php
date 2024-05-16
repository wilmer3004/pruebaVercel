<script>
    /* DATA TABLE */
    $id = 0;
    let table = $('#holidays').DataTable({
        "ajax": "{{ route('festivos.listar') }}",
        "columns": [{
                'data': 'id'
            },
            {
                'data': 'date'
            },
            {
                'title': 'Acciones',
                'render': function(data, type, row) {
                    return `
                        <div class="d-flex justify-content-around">
                            @role('superadmin|administrador')
                            <div class="btn-group">
                                <div class="">
                                    <a class="btn btn-sm btn-danger mx-2 tooltipA eliminar" onclick="deleteId(${row.id})" data-tooltip="Eliminar">
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

    /* HTML ELEMENTS */

    let formCreate = document.getElementById("formulario");
    let buttonSave = document.getElementById("guardar");
    let inputDate = document.getElementById("dayHoliday");
    let spanError = document.getElementById("error");

    /* CREATE */
    formCreate.addEventListener('submit', (event) => {
        event.preventDefault();

        /* VALIDATION DATE */
        let actualDate = new Date();
        let FormatActualDate = actualDate.toISOString().split('T')[0]; // ACTUAL DATE
        let valueDate = inputDate.value; // VALUE INPUT DATE

        // VALIDATE SELECT A DATE
        if (valueDate.length === 0) {
            spanError.innerHTML = '*La fecha es requerida';
            return false;
        }
        // VALIDATE DATE IS GREATER THAN VALUE DATE INPUT
        else if (FormatActualDate > valueDate) {
            spanError.innerHTML = '*Unicamente se puede registrar fechas de este año hacia adelante';
            return false;
        }
        // CALL METHOD SAVE NEW HOLIDAY DATE
        else {
            // MESSAGE
            spanError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i>La fecha es valida</p>';

            // DATA
            let data = {
                date: valueDate
            }

            // REQUEST CREATE
            axios.post("{{ route('festivos.store') }}", data).then((response) => {

                /* ALERT */
                Swal.fire({
                    icon: response.data.icon,
                    title: response.data.title,
                    text: response.data.message,
                    showConfirmButton: true,
                    confirmButtonText: "Listo",
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                });

                /* RELOAD */
                response.data.success ? window.location.href = "festivos" : "";

            }).catch((error) => {
                Swal.fire({
                    icon: "error",
                    title: "Ops...",
                    text: "Surgio un error en el registro de una fecha festivo, por favor comunicarse con soporte.",
                    showConfirmButton: true,
                    confirmButtonText: "Listo",
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                });
            });
        }

    });

    /* DELETE */
    function deleteId(id) {
        event.preventDefault();
        /* CONFIRM */
        Swal.fire({
            icon: "warning",
            title: "¿Seguro desea eliminar la fecha?",
            message: "Si elimina una fecha festivo significara que esta fecha sera tomada en cuenta para la programación de las sesiones de clase y no sera saltada.",
            confirmButtonText: "Si, eliminar",
            showCancelButton: true,
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                /* REQUEST DELETE */
                axios.get('festivos/' + id + '/delete').then((response) => {
                    Swal.fire({
                        icon: response.data.icon,
                        title: response.data.title,
                        text: response.data.message,
                        showConfirmButton: true,
                        confirmButtonText: 'Listo',
                        allowEscapeKey: false,
                        allowOutsideClick: false
                    })

                    /* RELOAD */
                    response.data.success ? window.location.href = "festivos" : "";

                }).catch((error) => {
                    /* MESSAGE ERROR */
                    Swal.fire({
                        icon: 'error',
                        title: 'Ops...',
                        text: 'Surgio un error al momento de eliminar la fecha, por favor contactarse con soporte.',
                        showConfirmButton: true,
                        confirmButtonText: 'Listo',
                        allowEscapeKey: false,
                        allowOutsideClick: false
                    })
                })
            }
        })
    }
</script>
