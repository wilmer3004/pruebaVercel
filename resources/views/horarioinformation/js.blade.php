
<script>
// axios.get('{{ route('horarioInformation.listar') }}')
//             .then((result) => {

//                 console.log('====================================');
//                 console.log(result.data.data);
//                 console.log('====================================');
//             })
//             .catch((error) => {
//                 console.log(error);
//             })
// console.log(@json($trimestres));

//         //*** DATATABLES ***
$id = 0;
            if ($.fn.DataTable.isDataTable('#eventos')) {
                $('#eventos').DataTable().destroy();
            }
            $('#eventos thead').empty();
            $('#eventos tbody').empty();




        let table = $('#eventos').DataTable({
            "ajax": {
                "url": '{{ route("horarioInformation.listar") }}',
                "dataSrc": "data", // The key of the array where DataTables should look for the data
                "type": "GET"
                },
            "columns": [
                {
                    'title':'Ficha',
                    'render': function(data, type, row) {
                        return `
                            ${row.nficha} ${row.num!==null ? `- ${row.num}`:''}
                        `
                    }
                },

                {
                    'data': 'trimestre',
                    'title': 'Trimestre' // Añade el título a la columna Ambiente
                },
                {
                    'data': 'programa',
                    'title': 'Programa' // Añade el título a la columna Programa
                },

                {
                    'data': 'endlective',
                    'title': 'Fin-Lectiva' // Añade el título a la columna Programa
                },

                {
                    'title': 'Acciones',
                    'render': function(data, type, row) {
                        return `
                        <div class="row">
                            @role('superadmin|administrador')
                            <div class=" col-12">
                                <a class="btn btn-sm btn-info mx-1 tooltipA" data-tooltip="Mostrar" id="showModalEvent" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight" onclick="showEvents(${row.nficha},${row.num},'${row.jornada}','${row.programa}','${row.endlective}',${row.idambiente},${row.idinstructor},${row.idficha},${row.idcomponent},'${row.inicio}','${row.final}')">
                                    <i class="fas fa-eye"></i>
                                </a>
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
            "pageLength":  25,
            "lengthMenu": [[10,  25], [10,  25]], // Only allow  10 and  25 entries per page
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


    function eliminar(idAmbiente,idInstructor,idFicha,idComponent,fechaInicio,fechaFin){

        Swal.fire({
            title: "¿Estás seguro?",
            text: "Se eliminará el registro seleccionado",
            icon: "warning",
            confirmButtonText: "Si, eliminar",
            showCancelButton: true,
            cancelButtonText: "Cancelar",
        }).then((result) => {

            event.preventDefault();

            const url = 'http://127.0.0.1:8000/horarioInformation/datatable';
            const bloqueInicio = moment(fechaInicio).format('HH:mm:ss');
            const bloqueFin = moment(fechaFin).format('HH:mm:ss');


            if (result.isConfirmed) {
                const data = {
                idAmbiente: idAmbiente,
                idInstructor: idInstructor,
                idFicha: idFicha,
                idComponent: idComponent,
                bloqueInicio: bloqueInicio,
                bloqueFin: bloqueFin,
            };

            // Send the DELETE request with Axios
            axios.delete('{{ route('horarioInformation.destroy') }}', { data })
                .then((response) => {
                    if(response.status === 200){
                        Swal.fire({
                            title: "¡Eliminado!",
                            text: 'Se elimino el evento correctamente',
                            icon: "success",
                            showConfirmButton: true,
                            confirmButtonText: "Listo",
                            allowEscapeKey: false,
                            allowOutsideClick: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = url;

                            }
                        });

                    }
                    else{
                        Swal.fire({
                            title: "¡Ops..!",
                            text: 'Hubo un error y el evento no se elimino correctamente',
                            icon: "error",
                            showConfirmButton: true,
                            confirmButtonText: "Listo",
                            allowEscapeKey: false,
                            allowOutsideClick: false,
                        });
                    }
                    // Handle successful deletion here
                })
                .catch((error) => {
                    console.log(error);
                    // Handle errors here
                });
                // window.location.href = url;
            }
        });



    }


    function editar(idAmbiente, idInstructor, idFicha, idComponent, fechaInicio, fechaFin) {
    // Asignar los valores a los input de tipo hidden
    document.getElementById('idAmbiente').value = idAmbiente;
    document.getElementById('idFicha').value = idFicha;
    document.getElementById('idComponent').value = idComponent;
    document.getElementById('fechaInicio').value = fechaInicio;
    document.getElementById('fechaFin').value = fechaFin;

    const url = '{{ route("horarios.instructor") }}';
    let dataFicha = {
        'ficha': idFicha
    }
axios.post(url,dataFicha)
    .then((result) => {
        var instructoresSelect = document.getElementById('instructores');
        instructoresSelect.innerHTML = '';

        const instructor = result.data.data;
        // First, add the default option for null value
        let opcionNull = document.createElement('option');
        opcionNull.value = 'null';
        opcionNull.textContent = 'Instructor en contratación';
        instructoresSelect.appendChild(opcionNull);

        instructor.forEach((x) => {

            let opcionIns = document.createElement('option');
            opcionIns.value = x.id;
            opcionIns.textContent = `${x.name.toUpperCase()} ${x.lastname.toUpperCase()}`;

            if (x.id === null || x.id == idInstructor) {
                opcionIns.setAttribute('selected', 'selected');
            }

            instructoresSelect.appendChild(opcionIns);


        });
    })
    .catch((error) => {
        console.log(error);
    });

    $('#EditEventoModal').modal('show');






}

function editarData(){
    const url = 'http://127.0.0.1:8000/horarioInformation/datatable';
    var instructoresSelect = document.getElementById('instructores');
    var instructor = null;


    //Valores de los input de tipo hidden
    var idInstructor = document.getElementById('instructores').value;
    var idAmbiente = document.getElementById('idAmbiente').value;
    var idFicha = document.getElementById('idFicha').value;
    var idComponent = document.getElementById('idComponent').value;
    var fechaInicio = document.getElementById('fechaInicio').value;
    var fechaFin = document.getElementById('fechaFin').value;



    if (instructoresSelect.value !== 'Seleccione un instructor') {
        instructor = instructoresSelect.value;

        const data = {
            'componente':idComponent,
            'ficha':idFicha,
            'instructor':idInstructor,
        }
        axios.post('{{ route("horarios.teacher") }}',data).then((result) => {
            if(result.data.code == 200){
                Swal.fire({
                                title: "¡Editado!",
                                text: result.data.message,
                                icon: "success",
                                showConfirmButton: true,
                                confirmButtonText: "Listo",
                                allowEscapeKey: false,
                                allowOutsideClick: false,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = url;

                                }
                            });
            }else if(result.data.code == 500){
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
        ).catch((error) => {
            console.log(error);
        });

        // window.location.href = url;

    }

    else {

        swal.fire("Defina el Instructor");
        return;
    }
}








</script>

