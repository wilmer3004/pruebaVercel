<script>
    // Validation to remove the DataTable if it exists
    if ($.fn.DataTable.isDataTable('#eventsTeacher')) {
        $('#eventsTeacher').DataTable().destroy();
    }
    $('#eventsTeacher thead').empty();
    $('#eventsTeacher tbody').empty();

    // DataTable initialization
    let table = $('#eventsTeacher').DataTable({
        "ajax": {
            'url': '{{ route("horarioInformationTeacher.listar") }}',
            'dataSrc': 'data',
            'type': 'GET'
        },
        "columns": [
        {
            'title':'Tipo Doc',
            'render':function(data, type, row){
                return `${row.nicknames}`;
            }
        },
        {
            'title':'Identificacion',
            'render':function(data, type, row){
                return `${row.document}`;
            }
        },
        {
                'title': 'Nombres',
                'render': function(data, type, row){
                    if (row.instructorname) { // Check if instructorname is defined
                        let name = row.instructorname.split(" ");
                        for (var i = 0; i < name.length; i++) {
                            name[i] = name[i].charAt(0).toUpperCase() + name[i].slice(1);
                        }
                        name = name.join(" ");
                        return `${name}`;
                    } else {
                        return ''; // Return an empty string or a default value if instructorname is undefined
                    }
                },
            },
            {

                'title': 'Apellidos',
                'render': function(data, type, row){
                    if (row.instructorlastname) { // Check if instructorname is defined
                        let lastName = row.instructorlastname.split(" ");
                        for (var i = 0; i < lastName.length; i++) {
                            lastName[i] = lastName[i].charAt(0).toUpperCase() + lastName[i].slice(1);
                        }
                        lastName = lastName.join(" ");
                        return `${lastName}`;
                    } else {
                        return ''; // Return an empty string or a default value if instructorname is undefined
                    }
                },
            },

            {
                'title': 'Acciones',
                'render': function(data, type, row) {
                    return `
                    <div class="row">
                        @role('superadmin|administrador')
                        <div class=" col-12">
                            <a class="btn btn-sm btn-info mx-1 tooltipA" data-tooltip="Mostrar" id="showModalEventTeacher" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight" onclick="showEventsTeacher('${row.nicknames}',${row.idinstructor},'${row.instructorname}','${row.instructorlastname}',${row.document})">
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
        "pageLength": 25,
        "lengthMenu": [[10, 25], [10, 25]], // Only allow 10 and 25 entries per page
        "order": [[2, 'asc']], // Orders the 3rd column (index 2) in ascending order by default
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



</script>
