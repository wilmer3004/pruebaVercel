<script defer>
    // *** VALIDACIONES DEL FORMULARIO ***
    let nameError = document.getElementById('name-error');
    let apellidoError = document.getElementById('apellidos-error');
    let numeroDocError = document.getElementById('numeroDocumento-error');
    let emailError = document.getElementById('email-error');
    let telefonoError = document.getElementById('telefono-error');
    let error = document.querySelector('#error');
    let nombre = document.querySelector('#nombres');
    let apellido = document.querySelector('#apellidos');
    let numerodoc = document.querySelector('#numeroDocumento');
    let correo = document.querySelector('#email');
    let telefono = document.querySelector('#telefono');

    function validateName() {
        name = nombre.value;

        if (name.length == 0) {
            nameError.innerHTML = '*El nombre es requerido';
            return false;
        }

        if (!name.match(/^[a-zA-ZÀ-ÿ\s]{3,20}$/)) {
            nameError.innerHTML = "*Digite un nombre válido";
            return false;
        }

        nameError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p> ';
        return true;
    }

    function validateApellido() {
        lastname = apellido.value;

        if (lastname.length == 0) {
            apellidoError.innerHTML = '*El apellido es requerido';
            return false;
        }

        if (!lastname.match(/^[a-zA-ZÀ-ÿ\s]{3,20}$/)) {
            apellidoError.innerHTML = '*Digite un apellido válido';
            return false;
        }

        apellidoError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p>';
        return true;
    }

    function validateNumDoc() {
        docnumber = numerodoc.value;

        if (docnumber.length == 0) {
            numeroDocError.innerHTML = '*El número de documento es requerido';
            return false;
        }

        if (!docnumber.match(/^\d{3,14}$/)) {
            numeroDocError.innerHTML = '*Digite un número de documento válido';
            return false;
        }

        numeroDocError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p>';
        return true;
    }

    function validateEmail() {
        email = correo.value;

        if (email.length == 0) {
            emailError.innerHTML = '*El email es requerido';
            return false;
        }

        if (!email.match(/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/)) {
            emailError.innerHTML = '*Digite un email válido';
            return false;
        }

        emailError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p>';
        return true;
    }

    function validateTel() {
        phone = telefono.value;

        if (phone.length == 0) {
            telefonoError.innerHTML = '*El teléfono es requerido';
            return false;
        }

        if (!phone.match(/^\d{3,14}$/)) {
            telefonoError.innerHTML = '*Digite un teléfono válido';
            return false;
        }

        telefonoError.innerHTML = '<p><i class="fas fa-circle-check me-1"></i> El campo es válido</p>';
        return true;
    }

    //*** FUNCIONALIDAD DEL FORMULARIO ***
    const prevBtns = document.querySelectorAll(".bt-prev");
    const nextBtns = document.querySelectorAll(".bt-next");
    const progress = document.getElementById("progress");
    const formSteps = document.querySelectorAll(".form-step");
    const progressStep = document.querySelectorAll(".progress-step");
    let formStepsNum = 0;

    prevBtns.forEach((bt) => {
        bt.addEventListener("click", () => {
            formStepsNum--;
            updateFormSteps();
            updateProgressbar();
        });
    });

    function updateFormSteps() {
        formSteps.forEach((formStep) => {
            formStep.classList.contains("form-step-active") &&
                formStep.classList.remove("form-step-active");
        });

        formSteps[formStepsNum].classList.add("form-step-active");
    }

    function updateProgressbar() {
        progressStep.forEach((progressStep, idx) => {
            if (idx < formStepsNum + 1) {
                progressStep.classList.add("progress-step-active");
            } else {
                progressStep.classList.remove("progress-step-active");
            }
        });

        const progressActive = document.querySelectorAll(".progress-step-active");

        progress.style.width = ((progressActive.length - 1) / (progressStep.length - 1)) * 100 + '%';
    }

    // *** MÉTODOS ***

    // Método para crear
    let roles = document.querySelector('#roles');
    let coordinaciones = document.querySelector('#coordinaciones')
    let componetesSel = document.querySelector('#componentes');
    let condiciones = document.querySelector('#condiciones');
    let contratos = document.querySelector('#contratos');
    let form2 = document.querySelector('#form2');
    let form1 = document.querySelector('#form1'); // Todos los datos del formulario
    let id = document.querySelector('#id');
    let coord = document.querySelector('#coord');
    let fullname = document.querySelector('#fullname');

    $(roles).select2();
    $(coordinaciones).select2();
    $('#tipoDoc').select2();
    $('#estado').select2();
    $(componetesSel).select2();
    $(condiciones).select2();
    $(contratos).select2();
    $('#tipo').select2();

    arrayCoordination = [];

    function updateArraayCoordinations() {
        // Borra todo los datos del array
        arrayCoordination.length = 0;
        $('#form-coordinations').find('input[name="coordination_ids\[\]"]').remove();
        // Para agregar todos los datos al array
        let selection = $(coordinaciones).select2('data');
        selection.forEach(element => {
            arrayCoordination.push(element['id']);
            // Agrega un campo oculto al formulario con el ID seleccionado
            $('#form-coordinations').append(`<input type="hidden" name="coordination_ids[]" value="${element['id']}">`);
        });
        // console.log(arrayCoordination);
    }

    $(coordinaciones).on('select2:select select2:unselect', function(e) {
        updateArraayCoordinations();
    });



    form1.addEventListener('submit', (event) => {
        event.preventDefault();

        if (!validateName() || !validateApellido() || !validateEmail() || !validateNumDoc() || !validateTel()) {
            error.innerHTML = '*Por favor valide que los campos estén correctos'
            return
        } else {
            error.innerHTML = '';



            // Función para guardar en minúsculas
            const emailInput = document.getElementById("email");

            emailInput.value = emailInput.value.toLowerCase();

            let formData = new FormData(form1);
            const formObject = Object.fromEntries(formData);

            axios.post('{{ route('instructores.store') }}', formData)
                .then((result) => {
                    console.log(result);
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
                        // let componentes = result.data.data[0];
                        let idUser = result.data.data[0];
                        let coordinaciones = result.data.data[1];
                        let nombre = result.data.data[2];

                        formStepsNum++;
                        updateFormSteps();
                        updateProgressbar();

                        Swal.fire({
                            title: "Exitoso",
                            text: result.data.message,
                            icon: "success",
                            showConfirmButton: true,
                            confirmButtonText: "Listo",
                            allowEscapeKey: false,
                            allowOutsideClick: false,
                        })

                        console.log(nombre);

                        // componentes.forEach((x) => {
                        //     let opcion = document.createElement("option");
                        //     opcion.value = x.id;
                        //     opcion.textContent = x.name;
                        //     componetesSel.appendChild(opcion);
                        // })

                        id.value = idUser;
                        coord.value = coordinaciones;
                        fullname.textContent = `(${nombre});`;
                    }
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

    form2.addEventListener('submit', (event) => {
        event.preventDefault();

        let formData = new FormData(form2);
        const formObject = Object.fromEntries(formData);
        console.log(formObject);

        axios.post('{{ route('instructores.details') }}', formData)
            .then((result) => {
                console.log(result);
                if (result.data.success) {
                    Swal.fire({
                        title: "¡Guardado!",
                        text: result.data.message,
                        icon: "success",
                        showConfirmButton: true,
                        confirmButtonText: "Listo",
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                    }).then((response) => {
                        if (response.isConfirmed) {
                            window.location.href = result.data.url;
                        }
                    });
                } else {
                    console.log(result);
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
    })
</script>
