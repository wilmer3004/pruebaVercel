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

    // *** MÉTODOS ***

    // Método para crear
    let form1 = document.querySelector('#form1');

    $('#roles').select2();
    $('#estado').select2();

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
            console.log(formObject);

            axios.post('{{ route('personas.store') }}', formData)
                .then((result) => {
                    console.log(result);
                    let url = result.data.url;
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
                        window.location.href = url;
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
</script>
