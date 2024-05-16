document.addEventListener("DOMContentLoaded", function () {
    const calendarEl = document.getElementById("calendar");

    let formFilter= document.getElementById("filtrar")
    let error= document.getElementById("error");
    let clear= document.getElementById("clean");

    /* Select */
    let componentesSelect = document.getElementById("componentesSelect")
    let ambienteSelect = document.getElementById("ambienteSelect")
    let tipoComponentesSelect = document.getElementById("tipoComponentesSelect")
    let fichaSelect = document.getElementById("fichaSelect")
    let instructorSelect = document.getElementById("instructorSelect");
    let start= document.getElementById("start");
    let end= document.getElementById("end");

    let componentes = [];
    let ambientes = [];
    let fichas = [];
    let tipoComponente = [];
    let instructores = [];

    /* Función para agregar opciones a un select */
    function agregarOpciones(select, datos) {
        datos.forEach(dato => {
            let opcion = new Option(dato, dato);
            select.add(opcion);
        });
    }

    axios
        .get("horario/show")
        .then((result) => {
            console.log(result.data);
            result.data.events.forEach(evento => {
                // Verificar y almacenar componentes
                if (evento.component && !componentes.includes(evento.component)) {
                    componentes.push(evento.component);
                }

                // Verificar y almacenar ambientes
                if (evento.environment && !ambientes.includes(evento.environment)) {
                    ambientes.push(evento.environment);
                }

                // Verificar y almacenar fichas
                let numeroFicha = evento.num >= 1 ? evento.number + '-' + evento.num : evento.number;
                if (evento.number && !fichas.includes(numeroFicha)) {

                    fichas.push(numeroFicha);
                }

                // Verificar y almacenar tipoComponente
                if (evento.type && !tipoComponente.includes(evento.type)) {
                    tipoComponente.push(evento.type);
                }
                // Verificar y almacenar instructor
                if (evento.teacher_name && evento.teacher_lastname) {
                    let fullNameTeacher =  `${evento.teacher_name} ${evento.teacher_lastname}`;
                    if(!instructores.includes(fullNameTeacher)){
                        instructores.push(fullNameTeacher);
                    }
                }
            });

            /* Agregar opciones a los select */
            agregarOpciones(tipoComponentesSelect, tipoComponente);
            agregarOpciones(componentesSelect, componentes);
            agregarOpciones(ambienteSelect, ambientes);
            agregarOpciones(fichaSelect, fichas);
            agregarOpciones(instructorSelect, instructores)

            /* Transformar select a select2 */
            $(componentesSelect).select2({
                theme: "classic",
            });
            $(ambienteSelect).select2({
                theme: "classic",
            });

            $(fichaSelect).select2({
                theme: "classic",
            });

            $(tipoComponentesSelect).select2({
                theme: "classic",
            });

            $(instructorSelect).select2({
                theme:"classic",
            });


            inicializarCalendario(sacarEventosFestivos(result.data.events, result.data.holidays ));
        })
        .catch((err) => {
            console.log(err);
        });

    function inicializarCalendario(eventos) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: "dayGridMonth",
            headerToolbar: {
                left: "prev,next today",
                center: "title",
                right: "dayGridMonth,timeGridWeek,timeGridDay,multiMonthYear",
            },
            locale: "es",
            buttonText: {
                today: "Hoy",
                month: "Mes",
                week: "Semana",
                day: "Día",
                list: "Lista",
                year: "Año",
            },
            events: eventos,
            eventRender: (evento, elemento) => {
                elemento.find(".fc-event-time").remove();
                evento.el.style.backgroundColor = '#f0f0f0'; // Color de fondo gris claro

                },
            dateClick: function (info) {
                /* *** Se comentarea ya que no se captura el día seleccionado del calendario **** */
                // let dia = info.dateStr;
                // diaform = moment(dia).format("D MMMM YYYY");
                // document.getElementById("diaSeleccionado").textContent = diaform;
                // $("#evento").modal("show");
                // calendar.unselect();
            },
        });
        calendar.render();
    }

    formFilter.addEventListener('submit', (event) => {
        event.preventDefault();

        let formData = new FormData(formFilter);
        const formObject = {};

        for (let [key, value] of formData.entries()) {
            // Si el valor es un array (select múltiple), concatenamos los valores separados por comas
            if (Array.isArray(formObject[key])) {
                formObject[key] = formObject[key].concat(value);
            } else if (formObject[key]) {
                // Si ya hay un valor para la clave, convertimos el valor existente en un array y agregamos el nuevo valor
                formObject[key] = [formObject[key], value];
            } else {
                formObject[key] = value;
            }
        }


        if(formObject.start=="" && formObject.end=="" & Object.keys(formObject).length==2 ){
            error.innerHTML="*Por favor seleccione algun dato"
        } else {
            error.innerHTML=''

            start= new Date(formObject.start)
            end= new Date(formObject.end)


            if(end<start){
                error.innerHTML="*La fecha final no puede ser mayor a la fecha de inicio"
            } else {
                error.innerHTML=" "
                console.log(formObject);
                axios
                .post("horario/filter", formObject)
                .then((result) => {

                    if(Object.keys(result.data.events).length==0){
                        Swal.fire({
                            title: "Dato no encontrado!",
                            text: "No se encontro ningun evento programado con los datos recibidos",
                            icon: "warning",
                            confirmButtonText: "Ok",
                            showCancelButton: false,
                        })
                        } else {
                        error.innerHTML = ''

                        inicializarCalendario(sacarEventosFestivos(result.data.events, result.data.holidays ));
                    }
                });
            }
        }
    });

    clear.addEventListener('click', ()=>{

        // Deseleccionar todas las opciones
        $(componentesSelect).val(null).trigger('change');
        $(ambienteSelect).val(null).trigger('change');
        $(fichaSelect).val(null).trigger('change');
        $(tipoComponentesSelect).val(null).trigger('change');
        $(instructorSelect).val(null).trigger('change');

        document.getElementById("contStart").innerHTML="";
        document.getElementById("contStart").innerHTML=`
                <h5 class="subtitle_canvas_off my-3">Fecha Inicio <i class="fa-solid fa-circle"></i></h5>
                <input type="date" class="form-control" name="start" id="start" placeholder="First name" aria-label="First name">
        `;
        document.getElementById("contEnd").innerHTML="";
        document.getElementById("contEnd").innerHTML=`
            <h5 class="subtitle_canvas_off my-3">Fecha Final <i class="fa-regular fa-circle"></i></h5>
            <input type="date" class="form-control" name="end" id="end" placeholder="Last name" aria-label="Last name">
        `;


    });

    document.getElementById("reloadButton").addEventListener("click", function() {
        error.innerHTML=""

        axios
        .get("horario/show")
        .then((result) => {

            inicializarCalendario(sacarEventosFestivos(result.data.events, result.data.holidays));
        })
        .catch((err) => {
            console.log(err);
        });
    });


    function sacarEventosFestivos(eventos, festivos) {

        let eventos2=eventos;

        // Eliminar la hora de las fechas para que se pueda ver en el horario como todo el dia, si se pone la hora cambiar el diseño
        for (var i = 0; i < eventos2.length; i++) {

            var start = new Date(eventos2[i].start);
            eventos2[i].start = start.toISOString().split('T')[0];

            var end = new Date(eventos2[i].end);
            eventos2[i].end = end.toISOString().split('T')[0];
        }

        // Agregar la propiedad 'backgroundColor' a cada objeto dentro del diccionario 'eventos2'
        for (var clave in eventos2) {
            if (eventos2.hasOwnProperty(clave)) {
                eventos2[clave].textColor= 'black';

                switch(eventos2[clave].type){
                    case 'tecnica':
                        eventos2[clave].color= '#ACE7FF';
                        break;
                    case 'transversal':
                        eventos2[clave].color= '#E7FFAC';
                        break;
                    default:
                        eventos2[clave].color= 'green';
                }
            }
        }

        let festivos2=festivos;

        //Unir los dos arrays
        eventos2 = eventos2.concat(festivos2);

        return eventos2;

    }


});
