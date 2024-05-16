
<script>
    function showEvents(nficha,num,jornada,programa,fechaFinLectiva,idambiente, idinstructor, idFicha, idcomponent, inicio, final){
    // Asegúrate de que Bootstrap esté correctamente incluido y que la versión sea  5.
    // Agrega la clase 'show' al elemento offcanvas para activarlo
    $('#offcanvasRight').addClass('show');
    $('.offcanvas-backdrop').addClass('show');

    // Elementos llamasdos por id
    const ficha = document.getElementById('offcanvasRightLabel');
    const trimestreFicha = document.querySelector('.trimestres-ficha');
    // Constantes
    const url= '{{ route("horarioInformation.showEventoFicha") }}'
    const dataFicha = {
        'idFicha': idFicha,
        'num':num
    }
    var componentTypes = @json($component_type);
    const message = `No se encontraron eventos de este tipo creados`;


    ficha.innerHTML = `
        <div class = 'row row-container-title'>
            <div class='col-12'>
                <p>${nficha} ${num!==null ? `- ${num}`:''}</p>
                <p>${programa}</p>
                <img class="img" src="http://127.0.0.1:8000/img/logosena_verde.png" alt="Logo Sena">
            </div>
        </div>

        <div class='row row-container '>
            <div class='col-1'></div>
            <div class='col-2'>Jornada</div>
            <div class='col-3'>${jornada}</div>
            <div class='col-3'>FECHA FIN LECTIVA</div>
            <div class='col-3'>${fechaFinLectiva}</div>
        </div>
        <div class='row row-container'>
        </div>
    `;

    // Generar y insertar el HTML del collapse para cada tipo de componente
    componentTypes.forEach(componentType => {
        var collapseHTML = collapseTypeComponent(componentType.name,message);
        trimestreFicha.innerHTML += collapseHTML;
    });

    // VALOR A MOSTRAR
    var contador = 0;

    // DATOS POR EVENTO
    axios.post(url, dataFicha)
    .then((result) => {
        const dataFi = result.data.data;
        if(dataFi){
            dataFi.map(dataF => {

                var startTimeYear =  moment(dataF.inicio).format('YYYY-MM-DD');
                var endTimeYear = moment(dataF.final).format('YYYY-MM-DD');
                var startTimeHour =  moment(dataF.inicio).format('HH:mm');
                var endTimeHour = moment(dataF.final).add(1, 'minutes').format('HH:mm');
                const bloque =  `${startTimeYear} A ${endTimeYear} <br> ${startTimeHour} - ${endTimeHour} `;
                const nameInstructor = this.nameInstructor(dataF.instructorname, dataF.instructorlastname);
                let evento = `
                    <div class='row container-data-study-sheet'>
                        <p class='col-2'>${dataF.ambiente}</p>
                        <p class='col-3' class='block'>${bloque}</p>
                        <p class='col-3'>${nameInstructor}</p>
                        <p class='col-3'>${dataF.namecomponent}</p>

                        @role('superadmin|administrador')
                        <div class="btn-group col-1">
                            <div class="btns-group-d">
                                <a class="btn btn-sm btn-warning mx-2 tooltipA" data-tooltip="Editar" id="editar" onclick="editar(${dataF.idambiente},${dataF.idinstructor},${dataF.idficha},${dataF.idcomponent},'${dataF.inicio}','${dataF.final}')">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a class="btn btn-sm btn-danger tooltipA eliminar" '{{ route('horarioInformation.listar') }}'  onclick="eliminar(${dataF.idambiente},${dataF.idinstructor},${dataF.idficha},${dataF.idcomponent},'${dataF.inicio}','${dataF.final}')" data-tooltip="Eliminar">
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
                const collapseElement = document.querySelector(`#collapseWidthFicha${dataF.component_type}`);
                if (collapseElement) {
                    if (dataF.component_type) {
                        if (collapseElement.textContent === message){
                            collapseElement.innerHTML = ''
                        };
                        collapseElement.innerHTML += evento;
                    }
                }
                });
        }
        else{
            trimestreFicha.innerHTML = `<div>Datos no encontrados</div>`
        }

    })
    .catch((error) => {
        console.log(error);
    });


    // Event listener for when the offcanvas is hidden
    offcanvasRight.addEventListener('hidden.bs.offcanvas', function () {
        // Clear the content of trimestreFicha
        trimestreFicha.innerHTML = '';
    });

    // Agrega un evento de escucha para el cambio de colapso
    $('.collapse').on('show.bs.collapse', function () {
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

//FUNCION PARA INSERTAR UN COLLAPSE
function collapseTypeComponent(title,message){
    return `
    <p class="d-inline-flex gap-1">
        <a class="btn-collapse" data-bs-toggle="collapse" href="#collapseWidthFicha${title}" role="button" aria-expanded="false" aria-controls="collapseWidthFicha${title}" data-bs-parent="#collapseParent">
            ${title}
        </a>
    </p>
    <div class="row">
        <div class='col-12'>
            <div class="collapse" id="collapseWidthFicha${title}" >${message}</div>
        </div>
    </div>
    `
}


//FUNCION PARA SACAR EL NOMBRE DEL INSTRUCTOR
function nameInstructor (instructorname,instructorlastname){
    var name = instructorname;
    var lastName = instructorlastname;
    if(name!==null && lastName!==null){

    name = name.split(" ");
    lastName = lastName.split(" ");
    for (var i = 0; i < name.length; i++) {
        name[i] = name[i].charAt(0).toUpperCase() + name[i].slice(1);
    }
    for (var i = 0; i < lastName.length; i++) {
        lastName[i] = lastName[i].charAt(0).toUpperCase() + lastName[i].slice(1);
    }
    name = name.join(" ");
    lastName = lastName.join(" ");

        return `${name} ${lastName}`;
    }else if(name !== null){

    name = name.split(" ");

    for (var i = 0; i < name.length; i++) {
        name[i] = name[i].charAt(0).toUpperCase() + name[i].slice(1);
    }

    name = name.join(" ");

        return `${name}`
    }else{
        return `Instructor en proceso de contratación`
    }
}

</script>

