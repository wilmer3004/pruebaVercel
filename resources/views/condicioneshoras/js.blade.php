<script>
    // *** DATATABLES ***
    $id = 0;
    let table = $('#condicioneshoras').DataTable({
        "ajax": "{{ route('condicioneshoras.listar') }}",
        "columns": [{
            'data': 'id'
        },
            {
                'data': 'contract'
            },
            {
                'data': 'condition'
            },
            {
                'data': 'percentage'
            },
            {
                'title': 'Acciones',
                'render': function (data, type, row) {
                    return `
                    <div class="d-flex justify-content-around">
                        <div class=" col-3">
                            <a class="btn btn-sm btn-warning mx-2 tooltipA" data-tooltip="Editar" id="editar" onclick="edit(${row.id})">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                        <div class="col-3">
                            <a href="{{ route('condicioneshoras.index') }}" class="btn btn-sm btn-danger mx-2 tooltipA" data-tooltip="Elminar" id="eliminar" onclick="eliminarId(${row.id})">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                    `
                }
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

    // *** VALIDACIONES DEL FORMULARIO ***

    /*** CREATE ***/
    /* input and selects */
    let contractSelect = document.getElementById('contract');
    let conditionSelect = document.getElementById('condition');
    let porcentaje = document.getElementById('percentaje');
    /* span error */
    let spanErrorContractSelect = document.getElementById('contract-error');
    let spanErrorConditionSelect = document.getElementById('condition-error');
    let percentajeError = document.getElementById('percentaje-error');

    /*** EDIT ***/
    /* input and selects */
    let contractSelectE = document.getElementById('contratoE');
    let conditionSelectE = document.getElementById('condicionE');
    let porcentajeE = document.getElementById('porcentajeE');

    /* span error */
    let spanErrorcontractSelectE = document.getElementById('contrato-errorE');
    let spanErrorconditionSelectE = document.getElementById('condicion-errorE');
    let percentajeErrorE = document.getElementById('percentaje-errorE');
    let errorE = document.getElementById('errorE');

    /* FORM */
    let form = document.getElementById('formulario');
    let formEditar = document.getElementById('formularioE');


    //Validaciones de create front

    // Validación porcentaje
    function validatePercentaje() {
        percentaje = porcentaje.value;

        if (porcentaje.length == 0) {
            percentajeError.innerHTML = '*El porcentaje es requerido';
            return false;
        }

        if (!percentaje.match(/^\d{1,3}$/)) {
            percentajeError.innerHTML = 'Digite un porcentaje válido';
            return false;
        }

        if (percentaje > 100 || percentaje <= 0) {
            percentajeError.innerHTML = '*El porcentaje no puede ser mayor a 100 o menor a 1';
            return false;
        }

        percentajeError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p>';
        return true;
    }

    // Validacion select contrato
    function validateContract() {
        contractValu = contractSelect.value;
        if (contractValu.length === 0 || contractValu === 'Seleccione un contrato...') {
            spanErrorContractSelect.innerHTML = '*El contrato es requerido';
            return false;
        }
        spanErrorContractSelect.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p>';
        return true;
    }

    // Validación select condición
    function validateCondition() {
        conditionValu = conditionSelect.value;
        if (conditionValu.length === 0 || contractValu === 'Seleccione una condición...') {
            spanErrorConditionSelect.innerHTML = '*La condition es requerido';
            return false;
        }
        if (conditionValu.length === 0 || conditionValu === 'Seleccione una condición...') {
            spanErrorConditionSelect.innerHTML = '*La condición es requerida';
            return false;
        }
        spanErrorConditionSelect.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p>';
        return true;
    }

    // Validaciones de edit front

    //Validaciones de edit front

    // Validación porcentaje
    function validatePercentajeE() {
        let porcentajeValueE = porcentajeE.value;

        if (porcentajeValueE.length == 0) {
            percentajeErrorE.innerHTML = '*El porcentaje es requerido';
            return false;
        }
        if (!porcentajeValueE.match(/^\d{1,3}$/)) {
            percentajeErrorE.innerHTML = 'Digite un porcentaje válido';
            return false;
        }
        if (porcentajeValueE > 100 || porcentajeValueE <= 0) {
            percentajeErrorE.innerHTML = '*El porcentaje no puede ser mayor a 100 o menor a 1';
            return false;
        }
        percentajeErrorE.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p>';
        return true;
    }

    // Validación contrato
    function validateContractE() {
        contractValuE = contractSelectE.value;
        if (contractValuE.length === 0) {
            spanErrorcontractSelectE.innerHTML = '*El contrato es requerido';
            return false;
        }
        spanErrorcontractSelectE.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p>';
        return true;
    }

    // Validación condición
    function validateConditionE() {
        valueConditionE = conditionSelectE.value;

        if (valueConditionE.length === 0) {
            spanErrorconditionSelectE.innerHTML = '*La condición es requerida';
            return false;
        }
        spanErrorconditionSelectE.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p>';
        return true;
    }

    // *** MÉTODOS PARA LA CRUD ***

    // Método para crear
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        if (!validatePercentaje() || !validateContract() || !validateCondition()) {
            error.innerHTML = '*Por favor valide que los campos estén correctos';
            return;
        } else {
            error.innerHTML = '';
            let formData = new FormData(form);
            const formObject = Object.fromEntries(formData);

            axios.post("{{ route('condicioneshoras.store') }}", formData).then((result) => {

                /* ALERT */
                Swal.fire({
                    icon: result.data.icon,
                    title: result.data.title,
                    text: result.data.message,
                    showConfirmButton: true,
                    confirmButtonText: "Listo",
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                });

                /* RELOAD */
                result.data.success ? window.location.href = "condicioneshoras" : "";

            }).catch((error) => {
                Swal.fire({
                    icon: "error",
                    title: "¡Ops..!",
                    text: 'Surgio un error en crear una nueva condición hora, por favor contactarse con soporte, gracias.',
                    showConfirmButton: true,
                    confirmButtonText: "Listo",
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                });
            })
        }
    })

    // Método para traer los datos al formulario de editar
    function edit(id) {
        axios.get(`condicioneshoras/${id}/edit`)
            .then((result) => {
                let datos = result.data.data[0];

                $('#id').val(datos.id);
                $('#contratoE').val(datos.contractId).find('option:selected').text(datos.contract);
                $('#condicionE').val(datos.conditionId).find('option:selected').text(datos.condition);
                $('#porcentajeE').val(datos.percentage);
            })
            .catch((error) => {
                console.log(error);
            })

        $('#EditCondicionHoraModal').modal('show');
    }

    // Método para actualizar un registro
    formEditar.addEventListener('submit', (event) => {
        event.preventDefault();

        if (!validatePercentajeE() || !validateContractE() || !validateConditionE()) {
            errorE.innerHTML = '*Por favor valide que los campos estén correctos';
            return;
        } else {
            errorE.innerHTML = '';

            let formData = new FormData(formEditar);
            const formObject = Object.fromEntries(formData);
            console.log(formObject);

            axios.post("{{ route('condicioneshoras.update') }}", formData)
                .then((result) => {

                    /* ALERT */
                    Swal.fire({
                        icon: result.data.icon,
                        title: result.data.title,
                        text: result.data.message,
                        showConfirmButton: true,
                        confirmButtonText: "Listo",
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                    });

                    /* RELOAD */
                    result.data.success ? window.location.href = "condicioneshoras" : "";
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
        }
    })

    // Método para eliminar
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
                axios.get('condicioneshoras/' + id + '/delete').then((result) => {
                    console.log(result);
                }).catch((error) => {
                    console.log(error);
                })
                window.location.href = url;
            }
        });
    }
</script>
