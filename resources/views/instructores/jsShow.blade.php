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
        let coordinacion = document.querySelector('#coordinacion');
        let contrato = document.querySelector('#contrato');
        let componentes = document.querySelector('#componentes');
        let condiciones = document.querySelector('#condiciones');
        let horas = document.querySelector('#horas');
        let tipo = document.querySelector('#tipo');

        console.log(id);

        axios.get(`${id}/consulta`)
            .then((result) => {
                console.log(result);
                let data = result.data.data[0];
                console.log(data);
                nombre.textContent = data.name;
                apellido.textContent = data.lastname;
                tipoDoc.textContent = data.nicknames;
                doc.textContent = data.document;
                email.textContent = data.email;
                estado.textContent = data.state;
                tel.textContent = data.phone;
                roles.textContent = data.roles;
                coordinacion.textContent = data.coordinations;
                contrato.textContent = data.contract;
                componentes.textContent = data.components;
                condiciones.textContent = data.conditions;
                horas.textContent = data.hours
                tipo.textContent = data.type

            })
            .catch((error) => {
                console.log(error);
            });
    });
</script>
