<script>
    let programacionInstructor;
    let selectMonth1;
    let busy;
    let available;

    busy = document.getElementById('busy');
    available = document.getElementById('available');
    function showEventsTeacher(nicknames,idTeacher,nameTeacher,lastNameTeacher,documentTeacher){
        $('#offcanvasRight').addClass('show');
        $('.offcanvas-backdrop').addClass('show');


        // Elementos llamasdos por id
        const instuctorLabel = document.getElementById('offcanvasRightLabel');
        programacionInstructor = document.querySelector('.programacion-instructor');

        //Constantes
        const url = '{{route("horarioInformationTeacher.showEventsTeacher")}}';
        const dataTeacher = {
            'idTeacher' : idTeacher,
        };

        const message = `No se encontraron eventos de este tipo creados`;

        instuctorLabel.innerHTML = `
            <div class = 'row row-container-title-teacher'>
                <div class='title col-12'>
                    <img class="img" src="http://127.0.0.1:8000/img/logosena_verde.png" alt="Logo Sena">
                    <p>${nameTeacher} ${lastNameTeacher} - ${nicknames} ${documentTeacher}</p>
                </div>
            </div>


            <div class='row row-container'>
            </div>
        `;

        //Datos por evento
        axios.post(url,dataTeacher)
            .then((result)=>{
                const data = result.data.data;
                if(data){
                    data.forEach(component => {
                        const collapseId = `collapseWidthTeacher${component.idcomponent}`;
                        const existingCollapse = document.getElementById(collapseId);

                        if (!existingCollapse) {
                            var collapseHTML = collapseComponent(component.idcomponent,component.namecomponent, message);
                            programacionInstructor.innerHTML += collapseHTML;
                        }
                    });


                    data.map(dataT => {
                        var startTimeYear =  moment(dataT.inicio).format('YYYY-MM-DD');
                        var endTimeYear = moment(dataT.final).format('YYYY-MM-DD');
                        var startTimeHour =  moment(dataT.inicio).format('HH:mm');
                        var endTimeHour = moment(dataT.final).add(1, 'minutes').format('HH:mm');
                        const bloque =  `${startTimeYear} A ${endTimeYear}  ${startTimeHour} - ${endTimeHour} `;
                        const numFicha = '';
                        if(dataT.num != null){
                            numFicha = `- ${dataT.num}`;
                        }
                        let evento = `
                            <div class='row container-data-teacher px-2'>
                                <p class='col-2'>${dataT.nficha} ${numFicha}</p>
                                <p class='col-2'>${dataT.ambiente}</p>
                                <p class='col-3' class='block'>${dataT.programa}</p>
                                <p class='col-3' class='block'>${bloque}</p>
                                @role('superadmin|administrador')
                                <div class="btn-group col-2">
                                    <div class="btns-group-d">
                                        <a class="btn btn-sm btn-danger tooltipA  data-tooltip="Eliminar">
                                            <i class="fas fa-trash"></i>
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
                        `;
                        // Ahora, insertas este HTML en el elemento deseado
                        const collapseElement = document.querySelector(`#collapseWidthTeacher${dataT.idcomponent}`);
                        if (collapseElement) {
                            if (dataT.namecomponent) {
                                if (collapseElement.textContent === message){
                                    collapseElement.innerHTML = ''
                                };
                                collapseElement.innerHTML += evento;
                            }
                        }
                        });
                        totalHoursMonthTeacher(data[0].totalHours);
                        totalHoursQuarterTeacher(data[0].totalHours,idTeacher);

                }else{
                    programacionInstructor.innerHTML = `<div>Datos no encontrados</div>`
                }
            }).catch((error)=>{
                console.log(error);
            });

         selectMonth1 = document.getElementById('selectMonths');





    }

    document.addEventListener('DOMContentLoaded', function() {
        const offcanvasRight = document.getElementById('offcanvasRight');
        offcanvasRight.addEventListener('hidden.bs.offcanvas', function () {
            // Ahora programacionInstructor debería estar definido
            if (programacionInstructor) {
                programacionInstructor.innerHTML = '';
            }
            // Asegúrate de que selectMonth1 también esté definido aquí
            selectMonth1 = document.getElementById('selectMonths');
            if (selectMonth1) {
                selectMonth1.innerHTML = '';
            }
        });
    });

    //FUNCION PARA INSERTAR UN COLLAPSE
    function collapseComponent(id,title,message){
        return `
        <p class="d-inline-flex gap-1">
            <a class="btn-collapse" data-bs-toggle="collapse" href="#collapseWidthTeacher${id}" role="button" aria-expanded="false" aria-controls="collapseWidthTeacher${id}" data-bs-parent="#collapseParent">
                ${title}
            </a>
        </p>
        <div class="row">
            <div class='col-12'>
                <div class="collapse" id="collapseWidthTeacher${id}" >${message}</div>
            </div>
        </div>
        `
    }

    function totalHoursMonthTeacher(hoursMonthT){
        const hoursMotnh = document.getElementById('hoursMonth');
        hoursMotnh.innerHTML = `${hoursMonthT}`;
    }

    function totalHoursQuarterTeacher(totalHourMonth,idTeacher) {

        urlYearQuarter = '{{ route("horarioInformationTeacher.totalHoursTeacherQuarter") }}';


        const currentDay = new Date();
        let year = currentDay.getFullYear();
        let month = currentDay.getMonth() + 1; // Asegúrate de que el mes sea 1-indexado
        let day = currentDay.getDate(); // Corregido el nombre de la variable

        if (day < 10) {
            day = `0${day}`; // Asegúrate de que el día tenga dos dígitos
        }

        if (month < 10) {
            month = `0${month}`; // Asegúrate de que el mes tenga dos dígitos
        }

        const fullYear = `${year}-${month}-${day}`;
        dataYearQuarter = {
            'currentDate':fullYear
        };
        dataYearQuarterHours = {
            'currentDate':fullYear,
            'idTeacher':idTeacher
        };

        axios.post(urlYearQuarter,dataYearQuarter)
            .then((result)=>{
                const dataMonthQuarter = result.data.data;
                const totalHoursQuarter = document.getElementById('hoursQuarter');
                const selectMonth = document.getElementById('selectMonths');
                const busyAndAvailableHoursUrl = '{{ route("horarioInformationTeacher.busyAndAvailableHoursTeacher") }}'
                if(result.data.data){
                    dataMonthQuarter.forEach(month => {
                        // Verifica si la opción ya existe
                        const optionExists = Array.from(selectMonth.options).some(opt => opt.text === month);
                        if (!optionExists) {
                            selectMonth.innerHTML += `
                                <option value = '${month}'>${month}</option>
                            `;
                        }

                    });
                    selectMonth1 = $(document.getElementById('selectMonths'));
                    // Antes de agregar el nuevo event listener, elimina el anterior
                    selectMonth1.off('change').on('change', function(e) {
                        axios.post(busyAndAvailableHoursUrl, dataYearQuarterHours)
                            .then((responseH) => {
                                let dataH = responseH.data.data;
                                if(dataH){
                                    let busyHours = dataH.horasPorMesOcupado;
                                    let availableHours = dataH.horasPorMesDesocupado;
                                    let busyHoursValue = 0;
                                    let availableHoursValue = totalHourMonth;

                                    if (busyHours.hasOwnProperty(selectMonth.value)) {
                                        busyHoursValue = busyHours[selectMonth.value];
                                    }
                                    if (availableHours.hasOwnProperty(selectMonth.value)) {
                                        availableHoursValue = availableHours[selectMonth.value];
                                    }
                                    if(busyHoursValue<0){
                                        busy.innerHTML = 0;
                                    }else{
                                        busy.innerHTML = busyHoursValue;
                                    }
                                    if (availableHoursValue<0) {
                                        available.innerHTML = 0;
                                    }else{
                                        available.innerHTML = availableHoursValue;
                                    }



                                }





                            }).catch((error1) => {
                                console.log(error1);
                            });
                    });


                    let totalHQ = totalHourMonth* dataMonthQuarter.length;
                    totalHoursQuarter.innerHTML = `${totalHQ}`;
                }
            }).catch((error)=>{
                console.log(error);
            });

            // Event listener for when the offcanvas is hidden
    offcanvasRight.addEventListener('hidden.bs.offcanvas', function () {
        // Clear the content of trimestreFicha
        programacionInstructor.innerHTML = '';
        selectMonth1.innerHTML = `
        <option value="null" selected disabled>Seleccione un mes</option>
        `;
        busy.innerHTML = 0;
        available.innerHTML = 0;

    });

    // Agrega un evento de escucha para el cambio de colapso utilizando delegación
    $(document).on('show.bs.collapse', '.collapse', function () {
        // Obtiene el identificador del colapso que se está mostrando
        var currentCollapseId = $(this).attr('id');

        // Cierra todos los demás colapsos
        $('.collapse').each(function () {
            var collapseId = $(this).attr('id');
            if (collapseId !== currentCollapseId) {
                $(this).collapse('hide');
            }
        });
    });
    }





</script>

