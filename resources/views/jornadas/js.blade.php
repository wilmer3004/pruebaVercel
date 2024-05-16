<script>
    //*** DATATABLES ***
    $id = 0;
    let table = $('#jornadas').DataTable({
        "ajax": "{{ route('jornadas.listar') }}",

        "columns": [{
                'data': 'id'
            },
            {
                'data': 'name'
            },
            {
                'title': 'Color',
                'render': function(data, type, row) {
                    // Create a div with a class that you can style in your CSS
                    return '<div class="color-box" style="background-color: ' + row.color + ';"></div>';
                }
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
                        <a class = "btn btn-sm mx-1 btn-danger tooltipA eliminar" href="{{ route('jornadas.index') }}" onclick="cambiarEstado(${row.id})" data-tooltip="Deshabilitar" >
                            <i class="fa-solid fa-xmark"></i>
                        </a>`
                        : `
                        <a class = "btn btn-sm mx-1 btn-success tooltipA eliminar" href="{{ route('jornadas.index') }}" onclick="cambiarEstado(${row.id})" data-tooltip="Habilitar" >
                            <i class="fa-solid fa-check"></i>
                        </a>`
                    }
                    `;
                    return `<div class="btn-group"> 
                                <div class=""> 
                                    <a class="btn btn-sm btn-warning mx-1 tooltipA" data-tooltip="Editar" onclick="editar(${row.id})">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div> 
                                <div class="">
                                    <a class="btn btn-sm btn-danger mx-1 tooltipA eliminar" href="{{ route('jornadas.index') }}" onclick="eliminarId(${row.id})" data-tooltip="Eliminar">
                                        <i class="fas fa-trash"></i>
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
    let nombre = document.getElementById('nombre');


      ////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
            document.getElementById('color-preview').style.backgroundColor = 'rgba(' + parseInt(color.slice(1,  3),  16) + ',' + parseInt(color.slice(3,  5),  16) + ',' + parseInt(color.slice(5,  7),  16) + ',' + opacity + ')';
        }

        function updateOpacity(opacity) {
            var color = document.getElementById('color').value;
            document.getElementById('color-preview').style.backgroundColor = 'rgba(' + parseInt(color.slice(1,  3),  16) + ',' + parseInt(color.slice(3,  5),  16) + ',' + parseInt(color.slice(5,  7),  16) + ',' + opacity + ')';
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
            document.getElementById('color-previewE').style.backgroundColor = 'rgba(' + parseInt(colorE.slice(1,   3),   16) + ',' + parseInt(colorE.slice(3,   5),   16) + ',' + parseInt(colorE.slice(5,   7),   16) + ',' + opacityE + ')';
        }

        function updateOpacityE(opacityE) {
            var colorE = document.getElementById('colorE').value;
            document.getElementById('color-previewE').style.backgroundColor = 'rgba(' + parseInt(colorE.slice(1,   3),   16) + ',' + parseInt(colorE.slice(3,   5),   16) + ',' + parseInt(colorE.slice(5,   7),   16) + ',' + opacityE + ')';
        }




    ///////////////////////////////////////////////////////////////////////////////////////////////////
    //Validacion de colores

    function validateColor() {
        // Get the color-preview element
        var colorPreviewDiv = document.getElementById('color-preview');

        // Get the computed style of the color-preview div
        var style = window.getComputedStyle(colorPreviewDiv);

        // Get the background color from the computed style
        var backgroundColor = style.getPropertyValue('background-color');

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
        var rgbColorRegex = /^rgb\((\d{1,2}|1\d\d|2[0-4]\d|25[0-5]),\s*(\d{1,2}|1\d\d|2[0-4]\d|25[0-5]),\s*(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\)$/;
        if (rgbColorRegex.test(colorValue)) {
            // The color value is a valid RGB color value
            return true;
        }

        // Check if the color value is a valid RGBA color value
        var rgbaColorRegex = /^rgba\((\d{1,2}|1\d\d|2[0-4]\d|25[0-5]),\s*(\d{1,2}|1\d\d|2[0-4]\d|25[0-5]),\s*(\d{1,2}|1\d\d|2[0-4]\d|25[0-5]),\s*((0\.\d{1,2}|1\.0{0,2})|0|1)\)$/;
        if (rgbaColorRegex.test(colorValue)) {
            // The color value is a valid RGBA color value
            return true;
        }

        // If we reach this point, the color value is not valid
        errorElement.textContent = 'Por favor, ingrese un color válido en formato hexadecimal (#rrggbb), RGB (rgb(r, g, b)) o RGBA (rgba(r, g, b, a)).';
        return false;
    }
    function validateColorE() {
        // Get the color-preview element
        var colorPreviewDiv = document.getElementById('color-previewE');

        // Get the computed style of the color-preview div
        var style = window.getComputedStyle(colorPreviewDiv);

        // Get the background color from the computed style
        var backgroundColor = style.getPropertyValue('background-color');

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
        var rgbColorRegex = /^rgb\((\d{1,2}|1\d\d|2[0-4]\d|25[0-5]),\s*(\d{1,2}|1\d\d|2[0-4]\d|25[0-5]),\s*(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\)$/;
        if (rgbColorRegex.test(colorValue)) {
            // The color value is a valid RGB color value
            return true;
        }

        // Check if the color value is a valid RGBA color value
        var rgbaColorRegex = /^rgba\((\d{1,2}|1\d\d|2[0-4]\d|25[0-5]),\s*(\d{1,2}|1\d\d|2[0-4]\d|25[0-5]),\s*(\d{1,2}|1\d\d|2[0-4]\d|25[0-5]),\s*((0\.\d{1,2}|1\.0{0,2})|0|1)\)$/;
        if (rgbaColorRegex.test(colorValue)) {
            // The color value is a valid RGBA color value
            return true;
        }

        // If we reach this point, the color value is not valid
        errorElement.textContent = 'Por favor, ingrese un color válido en formato hexadecimal (#rrggbb), RGB (rgb(r, g, b)) o RGBA (rgba(r, g, b, a)).';
        return false;
    }
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    function validateName() {
        name = nombre.value;

        if (name.length == 0) {
            nameError.innerHTML = '*La jornada es requerida';
            return false;
        }
        if (name.length == 0) {
            nameError.innerHTML = '*La jornada es requerida';
            return false;
        }
        if (!name.match(/^[a-zA-ZÀ-ÿ\s]{4,20}$/)) {
            nameError.innerHTML = '*Digite una jornada válida';
            return false;
        }

        nameError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }

    //*** MÉTODOS PARA LA CRUD ***

    //Método para crear
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        if (!validateName() || !validateColor()) {
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

            nombreInput.value = nombreInput.value.toLowerCase();
            //Almacenar el color
            const colorInput = backgroundColor;


            let formData = new FormData(form);
            formData.append('color', backgroundColor);
            const formObject = Object.fromEntries(formData);

            axios.post("{{ route('jornadas.store') }}", formData).
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
        axios.get(`jornadas/${id}/edit`)
            .then((result) => {
                let datos = result.data.data[0];
                
                $('#id').val(datos.id);
                $('#nombreE').val(datos.name);

                // Update the color-preview div's background color
                var actulColor = document.getElementById('color-actualPreview');
                actulColor.style.backgroundColor = datos.color;

                // TootlTip
                let boton = document.getElementById('color-actualPreview');
                boton.setAttribute('data-tooltip', datos.color);


            })
            .catch((error) => {
                console.log(error);
            })

        $('#EditJornadaModal').modal('show');
    }

    // Método para actualizar un registro
    formEditar.addEventListener('submit', (event) => {
        event.preventDefault();
        // Get the color-preview element
        var colorPreviewDiv = document.getElementById('color-previewE');

        // Get the computed style of the color-preview div
        var style = window.getComputedStyle(colorPreviewDiv);

        // Get the background color from the computed style
        var backgroundColor = style.getPropertyValue('background-color');
        let formData = new FormData(formEditar);
        formData.append('color', backgroundColor);
        const formObject = Object.fromEntries(formData);

        axios.post("{{ route('jornadas.update') }}", formData)
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
                axios.get('jornadas/' + id + '/delete').then((result) => {
                    console.log(result);
                }).catch((error) => {
                    console.log(error);
                })
                window.location.href = url;
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
                axios.get('jornadas/' + id + '/changeState')
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
