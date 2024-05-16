<script>

    function abrirModal(){
        $('#tableTrimestres').modal('show');
    }


    //***DataTable***

    let tableTrimestre = $('#tableTrimestre').DataTable({
        "ajax":{
            "url": '{{ route("horarioInformation.listarTrimestre") }}',
            "dataSrc": "data",
            "type": "GET",
        },
        "columns":[
            {
                'data':'trimestre_id',
                "width": "300px",
                'title': 'N° Trimestre'
            },
            {
                'data': null,
                'title': 'fechas',
                "width": "300px",
                'render': function(data, type, row) {
                    return row.quarter_start + ' - ' + row.quarter_end;
                }
            },
            {
                'data': 'fichas_programadas',
                "width": "300px",
                'title': 'N° Fichas'
            },
            {
                'title': 'Acciones',
                "width": "300px",
                'render': function (data, type, row){
                    return `
                    <div class="row">
                        @role('superadmin|administrador')
                        <div class="">
                            <div class="w-90">
                                <a class="btn btn-warning " data-tooltip="Generar" onclick="generarExcel('${row.quarter_start}','${row.quarter_end}')">
                                    Generar Excel
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
        "pageLength":  25,
        "lengthMenu": [[10,  25], [10,  25]], // Only allow  10 and  25 entries per page
        "order": [[3, 'asc']], // Ordena la columna 'Programa' (índice  3) en orden descendente por defecto
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


    const thead = document.querySelector('#tableExcel thead');
    const styles = {
        textAlign: 'center',
        width: '24px',
        border: '1px solid gray',
        fontSize: '14px',
        color: 'black',
        borderBottom: '1px solid black',
    };
    const monthNames = [
            "Enero", "Febrero", "Marzo", "Abril",
            "Mayo", "Junio", "Julio", "Agosto",
            "Septiembre", "Octubre", "Noviembre", "Diciembre"
        ];

    const colorsMonth= {
        "Enero": '#D0CECE',
        "Febrero": '#C6E0B4',
        "Marzo": '#8EAADB',
        "Abril": '#FFD965',
        "Mayo": '#D6DCE4',
        "Junio": '#F4B083',
        "Julio": '#5B9BD5',
        "Agosto": '#BDDAE1',
        "Septiembre": '#A5A5A5',
        "Octubre": '#FBE4D5',
        "Noviembre": '#F2F2F2',
        "Diciembre": '#FEF2CB'
    }

    var fechaIni, fechaFinal;

    function generarExcel(quarter_start,quarter_end){


        // Modal para el tiempo de descarga
        let condicion= false;
        let timerInterval;

        // Función para cerrar el SweetAlert
        function cerrarSweetAlert() {
            Swal.close();
            clearInterval(timerInterval);
            $('#tableTrimestres').modal('hide');
        }

        // Verifica la condición y cierra el SweetAlert si es verdadera
        function verificarCondicion() {
            if (condicion) {
                cerrarSweetAlert();
            }
        }

        // Muestra el SweetAlert y verifica la condición cada segundo
        Swal.fire({
            title: "Generando ⏲",
            html: ". . . esto tomara un tiempo",
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
                // Establece un intervalo para verificar la condición cada segundo
                timerInterval = setInterval(verificarCondicion,  1000);
            },
            willClose: () => {
                clearInterval(timerInterval);
            }
        }).then((result) => {
            // Maneja los eventos de cierre del SweetAlert aquí
        });


        // ================== Fechas las cuales van a estar en el rango del evento =========
        const fechas={
            'fechaInicio':  quarter_start,
            'fechaFin': quarter_end ,
        }

        // ================= Peticion para traer los eventos de ese trimestre =================

        let eventTri= null;

        // Le asignamos toda la data a la variable
        axios.post('{{ route('horarioInformation.listarEventsTrimestre') }}', fechas )
            .then((result) => {
                eventTri= result.data.data;


                // =========================== Insercion de Datos =========================================

                const table= document.getElementById('trbody');
                const meses= document.querySelector('.meses');
                const numDays= document.querySelector('.Days');
                const nameDays= document.querySelector('.nameDays');
                table.innerHTML= '';
                meses.innerHTML='<td colspan="8" style="text-align: center; font-size: 20px; font-family: Yu gothic; font-weight: bold; height: 35px; background-color: rgb(208, 214, 245); border-right: 1px solid black">PROGRAMACION FORMACION TITULADA PRESENCIAL</td>';
                nameDays.innerHTML= '<td colspan="8" style="text-align: center; font-size: 20px; font-family: Yu gothic; font-weight: bold; height: 35px; background-color: rgb(208, 214, 245); border-right: 1px solid black">CENTRO DE SERVICIOS FINANCIEROS</td>';
                numDays.innerHTML= `
                <tr>
                    <th style="width: 150px; height: 40px; font-size: 15px; border: 1px solid #000; background-color: rgb(208, 214, 245); font-family: Gungsuh; text-align: center">Ambiente <button style="cursor: pointer; border: none; background-color: transparent"></button></th>
                    <th style="width: 130px; font-size: 15px; border: 1px solid #000; background-color: rgb(208, 214, 245); font-family: Gungsuh; text-align: center">Ficha <button style="cursor: pointer; border: none; background-color: transparent"></button></th>
                    <th style="width: 130px; font-size: 15px; border: 1px solid #000; background-color: rgb(208, 214, 245); font-family: Gungsuh; text-align: center">Trimestre</th>
                    <th style="width: 300px; font-size: 15px; border: 1px solid #000; background-color: rgb(208, 214, 245); font-family: Gungsuh; text-align: center">Programa</th>
                    <th style="width: 110px; font-size: 15px; border: 1px solid #000; background-color: rgb(208, 214, 245); font-family: Gungsuh; text-align: center">Fecha Fin Lectiva</th>
                    <th style="width: 150px; font-size: 15px; border: 1px solid #000; background-color: rgb(208, 214, 245); font-family: Gungsuh; text-align: center">Competencia</th>
                    <th style="width: 250px; font-size: 15px; border: 1px solid #000; background-color: rgb(208, 214, 245); font-family: Gungsuh; text-align: center">Instructor</th>
                    <th style="width: 130px; font-size: 15px; border: 1px solid #000; background-color: rgb(208, 214, 245); font-family: Gungsuh; text-align: center">Hora</th>
                </tr>
                `

                function createTableRow(item, bgColor) {

                        var instructorNameContent = 'En contratacion';
                        var bold= 'font-weight:bold'
                        if (item['instructorname'] !== null) {
                            bold= '';
                            instructorNameContent = `${item.instructorname} ${item.instructorlastname}`;
                        }
                        console.log(item.num)

                        return `<tr style="border: solid  0.2px #000">
                                    <td style="background-color: ${bgColor}; opacity:  0.5; text-align: start; border:  1px solid gray; height:  25px" class="ambienteExcel">${item.ambiente}</td>
                                    <td style="background-color: ${bgColor}; opacity:  0.5; text-align: center; border:  1px solid gray" class="fichaExcel">${item.nficha} ${(item.num!=null) ? `- ${item.num}` : ''}</td>
                                    <td style="background-color: ${bgColor}; opacity:  0.5; text-align: center; border:  1px solid gray" class="trimestreExcel">${item.trimestre}</td>
                                    <td style="background-color: ${bgColor}; opacity:  0.5; text-align: start; border:  1px solid gray" class="programaExcel">${item.programa}</td>
                                    <td style="background-color: ${bgColor}; opacity:  0.5; text-align: center; border:  1px solid gray" class="fechaFinLectivaExcel">${item.endlective}</td>
                                    <td style="background-color: ${bgColor}; opacity:  0.5; text-align: start; border:  1px solid gray" class="competenciaExcel">${item.namecomponent}</td>
                                    <td style="background-color: ${bgColor}; opacity:  0.5; text-align: center; border:  1px solid gray; ${bold}" class="nombreInsExcel" >${instructorNameContent}</td>
                                    <td style="background-color: ${bgColor}; opacity:  0.5; text-align: start; border:  1px solid gray" class="bloqueExcel">${item.inicio} ${item.final}</td>
                                </tr>`;
                }


                // agregamos los colores por coordinacion
                for(var i=0; i<eventTri.length; i++) {
                    var item = eventTri[i];
                    var bgColor;
                    let colorCoordination= @json($colorsCoprdination);

                    colorCoordination.forEach( function(color){
                        if(color['name']==item['namecordination']){
                            bgColor=color['color'];
                        }
                    })

                    table.innerHTML += createTableRow(item, bgColor);
                }

                fechaIni= new Date(fechas['fechaInicio']);
                fechaFinal= new Date(fechas['fechaFin']);
                fechaIni.setDate(fechaIni.getDate()+1)
                fechaFinal.setDate(fechaFinal.getDate()+1)

                // Generar encabezados para cada mes del año
                for (let month = fechaIni.getMonth(); month <= fechaFinal.getMonth(); month++) {

                    var startDate,endDate;

                    // Estas validaciones es para la generacion de dias por mes
                    if(month === fechaIni.getMonth()){
                        startDate = new Date(fechaIni.getFullYear(), month, fechaIni.getDate()); // Primer día del mes
                        endDate = new Date(fechaFinal.getFullYear(), month + 1, 0); // Último día del mes
                    } else if( month === fechaFinal.getMonth()){
                        startDate = new Date(fechaIni.getFullYear(), month, 1); // Primer día del mes
                        endDate = new Date(fechaFinal.getFullYear(), month, fechaFinal.getDate());
                    } else {
                        startDate = new Date(fechaIni.getFullYear(), month, 1); // Primer día del mes
                        endDate = new Date(fechaFinal.getFullYear(), month + 1, 0 );
                    }

                    const headers = generateMonthHeaders(startDate, endDate);

                    const numDays = headers.length;
                    meses.innerHTML += `<td colspan="${numDays}" style="background-color: ${colorsMonth[monthNames[month]]} ; text-align: center; font-size: 16px; font-family: Calibri; font-weight: bold; border: 1px solid black">${monthNames[month]}</td>`;

                    const totalDays = headers.length;


                    headers.forEach((headerText, index) => {
                        const tdDay = document.createElement('th');
                        const tdNumDay= document.createElement('th');

                        tdDay.textContent= headerText[0]
                        tdNumDay.textContent = headerText.slice(-2);


                        // agrega los bordes cuando acabe los dias
                        if ((index +  1) === totalDays) {
                            styles.borderRight = '1px solid black';
                        } else {
                            delete styles.borderRight;
                        }

                        // agregar un color de fondo los sabados y domingos
                        if (headerText[0]=='S' || headerText[0]=='D'){
                            styles.backgroundColor = '#D0CECE';
                        } else {
                            delete styles.backgroundColor;
                        }

                        // Agrega los estilos al elementos para agregar
                        for (const styleProperty in styles) {
                            tdDay.style[styleProperty] = styles[styleProperty];
                            tdNumDay.style[styleProperty] = styles[styleProperty];
                        }

                        // Agrega los campos especificos para agregar el numero de dias y el dia
                        const targetRowDay = document.querySelector('#tableExcel tr:nth-child(2)');
                        const targetRowNumDay = document.querySelector('#tableExcel thead tr:nth-child(1)');
                        targetRowNumDay.appendChild(tdNumDay);
                        targetRowDay.appendChild(tdDay);
                    });
                    }


                // ============================ Agregar el minuto a la hora final ========================================

                // Validaciones y operaciones con fechas
                const filas = document.querySelectorAll('#tableExcel tbody tr');

                // Recorre cada fila de la tabla
                filas.forEach(function(fila, index) {

                    // Utiliza querySelector y verifica si el elemento existe
                    const bloqueElement = fila.querySelector('.bloqueExcel');



                    // Verificar si los elementos existen antes de acceder a sus propiedades
                    if (bloqueElement) {
                        const bloque = bloqueElement.textContent;
                        // ... (otras variables)

                        // sacar las fechas del bloque
                        var fechaini= bloque.slice(0,19)
                        var fechafin= bloque.slice(19,39)

                        // Convertir las fechas en objetos Date
                        var fechaIniObjeto= new Date(fechaini);
                        var fechaFinObjeto = new Date(fechafin);
                        // Sumar un minuto a la fecha y hora
                        fechaFinObjeto.setMinutes(fechaFinObjeto.getMinutes() + 1);

                        // Sacar la hora
                        var horaIni= fechaIniObjeto.getHours();
                        var horaFin = fechaFinObjeto.getHours();

                        // Añadir el texto al campo bloque
                        bloqueElement.textContent=`${horaIni} a ${horaFin}`
                    }
                });

            })
            .catch((error) => {
                console.log(error);
            })



        let dataEvents=null;

        // Le asignamos toda la data a la variable
        axios.post('{{ route('horarioInformation.listarEventsTrimester') }}', fechas )
            .then((result) => {
                dataEvents= result.data.data;
                generarEventosProgramados(dataEvents);
            })
            .catch((error) => {
                console.log(error);
            })



        //   ================ Creamos un array el cual contendra los dias a saltarse el horario =========================
        const holidays= @json($holidays);
        const excludedDates= [];

        holidays.forEach(function(date) {
            excludedDates.push(date['date']);
        });


        // ============================= Generar todo los dias del años ===================================================================
        function generateMonthHeaders(fechaInicio, fechaFin) {
            let encabezados = [];
            const dayNames = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];

            // Convertir las fechas de inicio y fin a objetos Date si son strings
            const fechaInicioObj = new Date(fechaInicio);
            const fechaFinObj = new Date(fechaFin);

            for (let fechaActual = new Date(fechaInicioObj); fechaActual <= fechaFinObj; fechaActual = new Date(fechaActual.getTime() + 24 * 60 * 60 * 1000)) {
                const diaDeSemana = fechaActual.getDay();
                const diaDelMes = fechaActual.getDate();
                // Asegúrate de que excludedDates esté definido y sea un array
                if (!excludedDates.includes(fechaActual.toISOString().split('T')[0])) {
                    encabezados.push(`${dayNames[diaDeSemana]} ${diaDelMes}`);
                }
            }

            return encabezados;
        }


        function generarEventosProgramados(dataEvents){

            // Obtén el tbody
            const tbody = document.getElementById('trbody');
            // Obtén todos los tr existentes en el tbody
            const rows = tbody.querySelectorAll('tr');


            for (let i = 0; i < rows.length; i ++){

                // agrega los campos programados


                    const currentRow = rows[i];

                    for (let month = fechaIni.getMonth(); month <= fechaFinal.getMonth(); month++) {

                        var startDate,endDate;

                        if(month === fechaIni.getMonth()){
                            startDate = new Date(fechaIni.getFullYear(), month, fechaIni.getDate()); // Primer día del mes
                            endDate = new Date(fechaFinal.getFullYear(), month + 1, 0); // Último día del mes
                        } else if( month === fechaFinal.getMonth()){
                            startDate = new Date(fechaIni.getFullYear(), month, 1); // Primer día del mes
                            endDate = new Date(fechaFinal.getFullYear(), month, fechaFinal.getDate());
                        } else {
                            startDate = new Date(fechaIni.getFullYear(), month, 1); // Primer día del mes
                            endDate = new Date(fechaFinal.getFullYear(), month + 1, 0 );
                        }

                        const headers = generateMonthHeaders(startDate, endDate);

                        const totalDays = headers.length;

                        headers.forEach((headerText, index) => {

                            delete styles.borderBottom

                            // agrega los bordes cuando acabe los dias
                            if ((index +  1) === totalDays) {
                                styles.borderRight = '1px solid black';
                            } else {
                                delete styles.borderRight;
                            }

                            if (headerText[0]=='S' || headerText[0]=='D'){
                                styles.backgroundColor = '#D0CECE';
                            } else {
                                delete styles.backgroundColor;
                            }


                            let letter= ''

                            // console.log(dataEvents);

                            dataEvents.forEach(function (event, dataIndex) {


                                let evento= new Date(event['inicio'])

                                //Mostrar todas las validaciones 
                                // console.log(
                                //     new Date(event['inicio']).getDate(),
                                //     `${new Date(event['inicio']).getHours()} a ${new Date(event['final']).getHours() + 1})`,
                                //     currentRow.querySelector('.bloqueExcel').textContent,
                                //     (month === new Date(event['inicio']).getMonth() ),
                                //     (Number(headerText.slice(-2)) === new Date(event['inicio']).getDate()),
                                //     (currentRow.querySelector('.fichaExcel').textContent.trim() === `${event['nficha']}${(event['num']!=null) ? ` - ${event['num']}` : ''}`),
                                //     (currentRow.querySelector('.trimestreExcel').textContent == event['trimestre']) ,
                                //     (currentRow.querySelector('.programaExcel').textContent == event['programa']) ,
                                //     (currentRow.querySelector('.fechaFinLectivaExcel').textContent == event['endlective']) ,
                                //     (currentRow.querySelector('.competenciaExcel').textContent == event['namecomponent']) ,
                                //     ((currentRow.querySelector('.nombreInsExcel').textContent ==
                                //         `${event['instructorname']} ${event['instructorlastname']}` ||
                                //         currentRow.querySelector('.nombreInsExcel').textContent == 'En contratacion')),
                                //     (currentRow.querySelector('.bloqueExcel').textContent ==
                                //         `${new Date(event['inicio']).getHours()} a ${new Date(event['final']).getHours() + 1})`)
                                //     )

                                if (
                                    month === new Date(event['inicio']).getMonth() &&
                                    Number(headerText.slice(-2)) === new Date(event['inicio']).getDate() &&
                                    currentRow.querySelector('.fichaExcel').textContent.trim() === `${event['nficha']}${(event['num']!=null) ? ` - ${event['num']}` : ''}` &&
                                    currentRow.querySelector('.trimestreExcel').textContent == event['trimestre'] &&
                                    currentRow.querySelector('.programaExcel').textContent == event['programa'] &&
                                    currentRow.querySelector('.fechaFinLectivaExcel').textContent == event['endlective'] &&
                                    currentRow.querySelector('.competenciaExcel').textContent == event['namecomponent'] &&
                                    (currentRow.querySelector('.nombreInsExcel').textContent ==
                                        `${event['instructorname']} ${event['instructorlastname']}` ||
                                        currentRow.querySelector('.nombreInsExcel').textContent == 'En contratacion') &&
                                    currentRow.querySelector('.bloqueExcel').textContent ==
                                        `${new Date(event['inicio']).getHours()} a ${new Date(event['final']).getHours() + 1}`
                                ) {

                                    // console.log("Entro")
                                    letter = 'P';

                                    if (event['estado']=='inactivo'){
                                        styles['color']='blue';
                                    } else {
                                        styles['color']='black';
                                    }
                                }
                            });

                                const tdEvent = document.createElement('td');
                                tdEvent.textContent = letter;

                                // Agrega los estilos al elementos para agregar
                                for (const styleProperty in styles) {
                                        tdEvent.style[styleProperty] = styles[styleProperty];
                                    }

                                currentRow.appendChild(tdEvent);

                        });



                    }
                }

                // Condicion para cerrar el modal
                condicion= true;
                if(condicion){
                    let tabla = document.getElementById('tableExcel');
                    exportTableToExcel('tableExcel', `horarios/${quarter_start}-${quarter_end}/.xls`);
                } else{
                    console.log("No entro")
                }
            }





    }





//    ==================funcion para exportar el excel ======================================

    function exportTableToExcel(tableId, filename = 'excel_data.xls') {
        const table = document.getElementById(tableId);
        // Se obtiene el HTML de toda la tabla
        const html = table.outerHTML;
        // se crea un objeto Blob que contiene el HTML de la tabla
        const blob = new Blob([html], {type: 'application/vnd.ms-excel'});
        // Se crea un URL permitiendo acceder al contenido de blob
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        // se establece el atributo href del elemento <a> con el URL creado para el Blob
        a.href = url;
        a.download = filename;
        // Se simula el clic en el enlace, activando la descarga del archivo.
        a.click();
        // Se libera el recurso creado con URL.createObjectURL, ayudando a prevenir pérdidas de memoria
        URL.revokeObjectURL(url);
    }




</script>

