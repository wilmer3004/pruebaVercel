<script defer>

    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const id = urlParams.get('id');


        let nombre = document.querySelector('#nombre');
        let apellido = document.querySelector('#apellido');
        let tipoDoc = document.querySelector('#tipoDoc');
        let doc = document.querySelector('#doc');
        let email = document.querySelector('#email');
        let estado = document.querySelector('#estado');
        let tel = document.querySelector('#tel');
        let roles = document.querySelector('#roles');
        let documentsType = document.querySelector('#documentsType');

        console.log(id);

        axios.get(`${id}/consulta`)
            .then((result) => {
                console.log(result);
                let data = result.data.data[0];

                nombre.textContent = data.name;
                apellido.textContent = data.lastname;
                tipoDoc.textContent = data.nicknames;
                doc.textContent = data.document;
                email.textContent = data.email;
                estado.textContent = data.state;
                tel.textContent = data.phone;
                roles.textContent = data.roles;
            })
            .catch((error) => {
                console.log(error);
            });
    });

</script>
