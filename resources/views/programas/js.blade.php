<script defer>
    // *** DATATABLE **
    // let id = 0;
    let table = $('#programas').DataTable({
        "ajax": "{{ route('programas.listar') }}",
        "columns": [{
                'data': 'id'
            },
            {
                'data': 'programa'
            },
            {
                'data': 'coordinacion'
            },
            {
                'data': 'tipoprograma'
            },
            {
                'data': 'duracion'
            },
            {
                'data': 'state'
            },
            {
                'title': 'Color',
                'render': function(data, type, row) {
                    // Create a div with a class that you can style in your CSS
                    return '<div class="color-box" style="background-color: ' + row.color + ';"></div>';
                }
            },

            {
                'title': 'Acciones',
                'render': function(data, type, row) {
                    let enableButton = `
                    ${row.state.trim() === 'activo'
                        ? `
                        <a class = "btn btn-sm mx-1 btn-danger tooltipA eliminar" href="{{ route('programas.index') }}" onclick="cambiarEstado(${row.id})" data-tooltip="Deshabilitar" >
                            <i class="fa-solid fa-xmark"></i>
                        </a>`
                        : `
                        <a class = "btn btn-sm mx-1 btn-success tooltipA eliminar" href="{{ route('programas.index') }}" onclick="cambiarEstado(${row.id})" data-tooltip="Habilitar" >
                            <i class="fa-solid fa-check"></i>
                        </a>`
                    }
                    `;
                    return `
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

    // *** COMPONENTES DEL FORMULARIO ***
    let nombre = document.getElementById('nombre');
    let tipoPrograma = document.getElementById('tipo');
    let errorTipo = document.getElementById('tipo-error');
    let descripcion = document.getElementById('descripcion');
    let nameError = document.getElementById('name-error');
    let descripcionError = document.getElementById('descripcion-error');
    let error = document.getElementById('error');
    let form = document.getElementById('formulario');

    /*** EDIT COMPONENTS FORM ***/
    /* TextField */
    let nombreE = document.getElementById('nombreE');
    let tipoProgramaE = document.getElementById('tipoE');
    let coordinacionE = document.getElementById('coordinacionE');
    let descripciónE = document.getElementById('descripcionE');
    let color_previewE = document.getElementById('color-previewE');
    let formEditar = document.getElementById('formularioE');
    /* <Span/> */
    let spanErrorNameE = document.getElementById('name-errorE');
    let spanErrorTiProgramaE = document.getElementById('tipoPrograma-errorE');
    let spanErrorCoordinacionE = document.getElementById('coordinacion-errorE');
    let spanErrorcolorE = document.getElementById('color-errorE');
    let spanErrorDescriptionE = document.getElementById('descripcion-errorE');
    let spanErrorE = document.getElementById('errorE');

    ///////////////////////////////////////////////////////////////////////////////////////////////////
    // COLOR
    //CREATE

    // Event listener for the color input
    document.getElementById('color').addEventListener('input', function() {
        updateColor(this.value);
    });

    // Event listener for the opacity input
    document.getElementById('opacity').addEventListener('input', function() {
        updateOpacity(this.value);
    });

    function updateColor(color) {
        var opacity = document.getElementById('opacity').value;
        document.getElementById('color-preview').style.backgroundColor = 'rgba(' + parseInt(color.slice(1, 3), 16) +
            ',' + parseInt(color.slice(3, 5), 16) + ',' + parseInt(color.slice(5, 7), 16) + ',' + opacity + ')';
    }

    function updateOpacity(opacity) {
        var color = document.getElementById('color').value;
        document.getElementById('color-preview').style.backgroundColor = 'rgba(' + parseInt(color.slice(1, 3), 16) +
            ',' + parseInt(color.slice(3, 5), 16) + ',' + parseInt(color.slice(5, 7), 16) + ',' + opacity + ')';
    }

    //EDIT
    // Event listener for the color input in the edit form
    document.getElementById('colorE').addEventListener('input', function() {
        updateColorE(this.value);
    });

    // Event listener for the opacity input in the edit form
    document.getElementById('opacityE').addEventListener('input', function() {
        updateOpacityE(this.value);
    });

    function updateColorE(colorE) {
        var opacityE = document.getElementById('opacityE').value;
        document.getElementById('color-previewE').style.backgroundColor = 'rgba(' + parseInt(colorE.slice(1, 3), 16) +
            ',' + parseInt(colorE.slice(3, 5), 16) + ',' + parseInt(colorE.slice(5, 7), 16) + ',' + opacityE + ')';
    }

    function updateOpacityE(opacityE) {
        var colorE = document.getElementById('colorE').value;
        document.getElementById('color-previewE').style.backgroundColor = 'rgba(' + parseInt(colorE.slice(1, 3), 16) +
            ',' + parseInt(colorE.slice(3, 5), 16) + ',' + parseInt(colorE.slice(5, 7), 16) + ',' + opacityE + ')';
    }




    ///////////////////////////////////////////////////////////////////////////////////////////////////

    /***VALIDATIONS CREATE***/

    function validateColor() {
        // Get the color-preview element
        var colorPreviewDiv = document.getElementById('color-preview');

        // Get the computed style of the color-preview div
        var style = window.getComputedStyle(colorPreviewDiv);

        // Get the background color from the computed style
        var backgroundColor = style.getPropertyValue('background-color');


        console.log(backgroundColor);
        var colorValue = backgroundColor;
        var errorElement = document.getElementById('color-error');

        // Clear any previous error message
        errorElement.textContent = '';

        // Check if the color value is a valid hexadecimal color code
        var hexColorRegex = /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/;
        if (hexColorRegex.test(colorValue)) {
            // The color value is a valid hexadecimal color code
            return true;
        }

        // Check if the color value is a valid RGB color value
        var rgbColorRegex =
            /^rgb\((\d{1,2}|1\d\d|2[0-4]\d|25[0-5]),\s*(\d{1,2}|1\d\d|2[0-4]\d|25[0-5]),\s*(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\)$/;
        if (rgbColorRegex.test(colorValue)) {
            // The color value is a valid RGB color value
            return true;
        }

        // Check if the color value is a valid RGBA color value
        var rgbaColorRegex =
            /^rgba\((\d{1,2}|1\d\d|2[0-4]\d|25[0-5]),\s*(\d{1,2}|1\d\d|2[0-4]\d|25[0-5]),\s*(\d{1,2}|1\d\d|2[0-4]\d|25[0-5]),\s*((0\.\d{1,2}|1\.0{0,2})|0|1)\)$/;
        if (rgbaColorRegex.test(colorValue)) {
            // The color value is a valid RGBA color value
            return true;
        }

        // If we reach this point, the color value is not valid
        errorElement.textContent =
            'Por favor, ingrese un color válido en formato hexadecimal (#rrggbb), RGB (rgb(r, g, b)) o RGBA (rgba(r, g, b, a)).';
        return false;
    }


    function validateName() {
        let name = nombre.value;

        if (name.length == 0) {
            nameError.innerHTML = '*El nombre es requerido';
            return false;
        }

        if (!name.match(/^[a-zA-ZÀ-ÿ\s]{4,50}$/)) {
            nameError.innerHTML = 'Digite un nombre válido';
            return false;
        }

        nameError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }

    function validateTipoPrograma() {
        tipo = tipoPrograma.value;

        if (tipo === 'Seleccione un tipo de programa...') {
            errorTipo.innerHTML = '*Por favor seleccione el tipo de programa';
        } else {
            errorTipo.innerHTML =
                '<p><i class="fas fa-circle-check me-1" style=color="green"></i> El campo es válido</p>';
        }
    }

    function validateDescripcion() {
        let description = descripcion.value;

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

    /***VALIDATIONS EDIT***/

    function validateColorE() {
        // Get the color-preview element
        var colorPreviewDivE = document.getElementById('color-previewE');

        // Get the computed style of the color-previewE div
        var styleE = window.getComputedStyle(colorPreviewDivE);

        // Get the background color from the computed style
        var backgroundColorE = styleE.getPropertyValue('background-color');

        var colorValueE = backgroundColorE;
        var errorElementE = document.getElementById('color-errorE');

        // Clear any previous error message
        errorElementE.textContent = '';

        // Check if the color value is a valid hexadecimal color code
        var hexColorRegexE = /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/;
        if (hexColorRegexE.test(colorValueE)) {
            // The color value is a valid hexadecimal color code
            return true;
        }

        // Check if the color value is a valid RGB color value
        var rgbColorRegexE =
            /^rgb\((\d{1,2}|1\d\d|2[0-4]\d|25[0-5]),\s*(\d{1,2}|1\d\d|2[0-4]\d|25[0-5]),\s*(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\)$/;
        if (rgbColorRegexE.test(colorValueE)) {
            // The color value is a valid RGB color value
            return true;
        }

        // Check if the color value is a valid RGBA color value
        var rgbaColorRegexE =
            /^rgba\((\d{1,2}|1\d\d|2[0-4]\d|25[0-5]),\s*(\d{1,2}|1\d\d|2[0-4]\d|25[0-5]),\s*(\d{1,2}|1\d\d|2[0-4]\d|25[0-5]),\s*((0\.\d{1,2}|1\.0{0,2})|0|1)\)$/;
        if (rgbaColorRegexE.test(colorValueE)) {
            // The color value is a valid RGBA color value
            return true;
        }

        // If we reach this point, the color value is not valid
        spanErrorcolorE.textContent =
            'Por favor, ingrese un color válido en formato hexadecimal (#rrggbb), RGB (rgb(r, g, b)) o RGBA (rgba(r, g, b, a)).';
        return false;
    }

    function validateNameE() {
        let textFieldName = nombreE.value;
        if (textFieldName.length === 0) {
            spanErrorNameE.innerHTML = '*El nombre es requerido';
            return false;
        }
        if (!textFieldName.match(/^[a-zA-ZÀ-ÿ\s]{4,50}$/)) {
            spanErrorNameE.innerHTML = '*Digite un nombre valido';
            return false;
        }


        spanErrorNameE.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p>';
        return true;
    }

    function validateDescriptionE() {
        let textFieldDescription = descripciónE.value;
        if (textFieldDescription.length === 0) {
            spanErrorDescriptionE.innerHTML = '*La descripción es requerida';
            return false;
        }
        if (!textFieldDescription.match(/^[a-zA-ZÀ-ÿ\s]{8,200}$/)) {
            spanErrorDescriptionE.innerHTML = '*Digite una descripción valida';
            return false;
        }

        spanErrorDescriptionE.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p>';
        return true;
    }

    // *** MÉTODOS PARA LA CRUD ***

    // Create
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        if (!validateDescripcion() || !validateName() || !validateColor()) {
            error.innerHTML = '*Por favor valide que los campos estén correctos';
            return;
        } else {
            error.innerHTML = '';
            // Get the color-preview element
            var colorPreviewDiv = document.getElementById('color-preview');

            // Get the computed style of the color-preview div
            var style = window.getComputedStyle(colorPreviewDiv);

            // Get the background color from the computed style
            var backgroundColor = style.getPropertyValue('background-color');

            // Función para guardar en minúsculas
            const nombreInput = document.getElementById("nombre");
            const descripcionInput = document.getElementById("descripcion");
            // Almacenar el color
            const colorInput = backgroundColor;

            nombreInput.value = nombreInput.value.toLowerCase();
            descripcionInput.value = descripcionInput.value.toLowerCase();


            let formData = new FormData(form);
            formData.append('color', backgroundColor);
            const formObject = Object.fromEntries(formData);

            axios.post("{{ route('programas.store') }}", formData).
            then((result) => {
                console.log(result);
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

    // Edit data
    function editar(id) {
        axios.get(`programas/${id}/edit`)
            .then((result) => {
                let datos = result.data.data[0];
                $('#id').val(datos.id)
                $('#nombreE').val(datos.name);
                $('#descripcionE').val(datos.description);
                $('#tipoE').val(datos.typeId).find('option:selected').text(datos.type);
                $('#coordinacionE').val(datos.coordinationId).find('option:selected').text(datos.coordination);
                // Update the color-preview div's background color
                var colorPreviewDiv = document.getElementById('color-previewE');
                colorPreviewDiv.style.backgroundColor = datos.color;
            }).catch((error) => {
                console.log(error);
            })

        $('#EditProgramaModal').modal('show');
    }

    // Edit
    formEditar.addEventListener('submit', (event) => {
        event.preventDefault();

        if (!validateColorE() || !validateDescriptionE() || !validateNameE() ) {
            spanErrorE.innerHTML = '*Por favor valide que los campos estén correctos';
            return;
        }

        else {

        // Get the color-preview element
        var colorPreviewDiv = document.getElementById('color-previewE');

        // Get the computed style of the color-preview div
        var style = window.getComputedStyle(colorPreviewDiv);

        // Get the background color from the computed style
        var backgroundColor = style.getPropertyValue('background-color');
        let formData = new FormData(formEditar);
        formData.append('color', backgroundColor);
        const formObject = Object.fromEntries(formData);

        axios.post("{{ route('programas.update') }}", formData)
            .then((result) => {
                if (result.data.success) {
                    window.location.href = result.data.url;
                } else {

                    if (result.data.reaload) {

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
                }
            })
            .catch((error) => {
                Swal.fire({
                    title: "¡Ops..!",
                    text: 'Error 500',
                    icon: "error",
                    showConfirmButton: true,
                    confirmButtonText: "Listo",
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                });
            });

        }
    })

    // Método para cambiar estado
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
                axios.get('programas/' + id + '/changeState')
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
                axios.get('programas/' + id + '/delete').then((result) => {
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
