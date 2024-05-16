<script defer>
    document.addEventListener("DOMContentLoaded", function() {
        // VARIABLES PARA LA FUNCIONALIDAD DEL FORMULARIO
        const prevBtns = document.querySelectorAll(".bt-prev");
        const nextBtns = document.querySelectorAll(".bt-next");
        const progress = document.getElementById("progress");
        const formSteps = document.querySelectorAll(".form-step");
        const progressStep = document.querySelectorAll(".progress-step");
        let formStepsNum = 0;

        // Variables para el filtrado de la ficha

        // VARIABLES PARA LA GESTIÓN DE LOS HORARIOS
        const opcionP = document.querySelectorAll(".optionP");
        let opcionActiva = null;
        let programa = 0;
        let programacont = "";
        let programas = document.getElementById("programas");
        let fichas = document.getElementById("fichas");
        let fichaCont = "";
        let fichaJor = "";
        let ficha = 0;
        let program = 0;

        // Options
        // Segundo paso
        let selectFichaStepTwo = document.querySelector("#fichaSelectionStepTwo");
        let select2Days = document.querySelector(".select2Days");
        let select2Offert = document.querySelector(".select2Offert");
        let select2Quarters = document.querySelector(".select2Quarters");
        let fichasSelection = document.querySelector(".messsageSearch");
        let fichaGenerate = document.querySelector(".fichasSelection");
        let columList = document.querySelector(".column-list");
        let fichaMessageEmpy =
            '<section class="messsageSearch"> <p class="subTitle">No hay fichas</p></section>';
        let startMessageFicha =
            '<section class="messsageSearch"><p class="subTitle">Selección de ficha</p><p class="textDown">(jornada, oferta, trimestre)</p></section>';
        let selectListFicha =
            '<div class="input-group"><ul class= "column-list"><li id="fichas"></li></ul></div>';
        let fichasSearch;
        let fichasFilter;

        // Tercer paso
        let componentesSelect = document.getElementById("componentesSelect")
        let ambientesSelect = document.getElementById("ambientesSelect");
        let trimestresSelect = document.querySelector("#trimestres");
        let instructoresSelect = document.getElementById("instructores");
        let bloqueSelect = document.getElementById("bloques");

        let componentes = [];
        let componentType = "";
        let ambientes = [];
        let trimestres = [];
        let instructores = [];
        let bloques = [] // generar array para el select
        let horaI = document.getElementById("horaI");
        let horaF = document.getElementById("horaF");
        let tri = 0;
        let triName = 0;
        const inicio = document.querySelector(".fechaInicio");
        const final = document.querySelector(".fechaFinal");
        const inicioHora = document.querySelector(".horaInicio");
        const finalHora = document.querySelector(".horaFinal");

        /* Elementos tool box */
        let startDateToolBox = document.getElementById("startDateToolBox");
        let endDateToolBox = document.getElementById("endDateToolBox");
        let numSesionsClassToolBox = document.getElementById("numSesionsClassToolBox");
        let continuesProgramation = document.getElementById("continuesProgramation");
        /* Elementos span error */
        let errorFechaInicioToolBox = document.getElementById("errorFechaInicioToolBox");
        let errorFechaFinalToolBox = document.getElementById("errorFechaFinalToolBox");
        let errorNumSesionsToolBox = document.getElementById("errorNumSesionsToolBox");
        let errorContinuesProgramationToolBox = document.getElementById("errorContinuesProgramationToolBox");


        // Declaración de todos los select
        $(selectFichaStepTwo).select2({
            theme: "classic",
        });

        $(ficha).select2({});
        $(bloqueSelect).select2({
            theme: "classic",
        });
        $(componentesSelect).select2({
            theme: "classic",
        });
        $(ambientesSelect).select2({
            theme: "classic",
        });
        $(trimestresSelect).select2({
            theme: "classic",
        });
        $(instructoresSelect).select2({
            theme: "classic",
        });

        flatpickr(horaI, {
            // enableTime: true,
            noCalendar: true,
            dateformat: "H:i",
            time_14hr: true,
        });
        flatpickr(horaF, {
            // enableTime: true,
            noCalendar: true,
            dateformat: "H:i",
            time_14hr: true,
        });

        //  Funcionalidad del formulario

        nextBtns.forEach((bt) => {

            bt.addEventListener("click", () => {

                if (!opcionActiva || programa === 0) {

                    swal.fire("Seleccione un programa"); // Mensaje de alerta
                    return; // Evita que el resto del código se ejecute

                } else if (formStepsNum === 1 && (!opcionActiva || ficha === 0)) {

                    swal.fire("Seleccione una ficha");
                    return;
                } else {

                    formStepsNum++; // Aumento del paso a mas uno pasar continuar
                    updateFormSteps();
                    updateProgressbar();

                }

            });

        });
        // Funcion de botones

        // Atras
        prevBtns.forEach((bt) => {
            bt.addEventListener("click", () => {
                formStepsNum--;
                updateFormSteps();
                updateProgressbar();
            });
        });

        // Update state
        function updateFormSteps() {
            formSteps.forEach((formStep) => {
                formStep.classList.contains("form-step-active") &&
                    formStep.classList.remove("form-step-active");
            });

            formSteps[formStepsNum].classList.add("form-step-active");
        }

        // Update progresbar
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

        // Limpiar formulario

        document
            .getElementById("volver")
            .addEventListener("click", () => {

                // Limpiar seleccion
                programa = 0;
                ficha = 0;
                el.classList.remove("input-group-active");
                opcionActiva.classList.remove("input-group-active");

                // Limpiar los selects
                select2Days.innerHTML = '<option disabled selected>jornadas...</option>';
                select2Offert.innerHTML = '<option disabled selected>tipo de oferta...</option>';
                select2Quarters.innerHTML = '<option disabled selected>trimestre ficha...</option>';
                fichaSelectionStepTwo.innerHTML = '<option disabled selected>Seleccione una ficha</option>';

                // Limpiar fichas
                fichaGenerate.innerHTML = ``;
                fichaGenerate.innerHTML = startMessageFicha;


            });


        document
            .getElementById("volver2")
            .addEventListener("click", () => {

                // Limpiar los select
                componentesSelect.innerHTML = '<option disabled selected>Seleccione un componente</option>';
                ambientesSelect.innerHTML = '<option disabled selected>Seleccione un ambiente</option>';
                trimestresSelect.innerHTML = '<option disabled selected>Seleccione un trimestre</option>';
                bloqueSelect.innerHTML = '<option disabled selected>Seleccione un bloque</option>';

                // select4.innerHTML = "";

                // Limpiar los inputs
                fechaInicio.value = '';
                fechaFinal.value = '';
                inicioHora.value = '';
                finalHora.value = '';

            });

        /* Limpiar data en tool box */
        document.getElementById("clearToolBox").addEventListener("click", () => {
            startDateToolBox.value = '';
            endDateToolBox.value = '';
            numSesionsClassToolBox.value = '';
            continuesProgramation.checked = false;
            /* Errors */
            errorFechaInicioToolBox.innerHTML = '';
            errorFechaFinalToolBox.innerHTML = '';
            errorNumSesionsToolBox.innerHTML = '';
            errorContinuesProgramationToolBox = '';

        });

        // ***** PROGRAMACIÓN DE HORARIOS *****

        // ** PRIMERA PAGINA **

        // Dar estilo a cada programa elegido y capturar el contenido y id de la etiqueta
        Array.from(opcionP).forEach(function(opcion) {
            opcion.addEventListener("click", (event) => {
                programa = event.target.id;
                programacont = event.target.innerHTML;
                if (opcionActiva) {
                    opcionActiva.classList.remove("input-group-active");
                }
                opcion.classList.add("input-group-active");
                opcionActiva = opcion;
            });
        });

        // ** SEGUNDA PAGINA **

        $(document).ready(function() {
            $('.select2Days').select2();
        });

        $(document).ready(function() {
            $('.select2Offert').select2();
        });

        $(document).ready(function() {
            $('.select2Quarters').select2();
        });

        /* Buscar fichas relacionadas al programa */

        // Enviar cada programa para listar las fichas asignadas a este ->

        document.getElementById("generarFichas").addEventListener("click", () => {

            let data = {
                programa,
            };
            programas.textContent = `Programa de ${programacont}`;
            // Petición axios ->
            axios
                .post("fichas", data)
                .then((result) => {

                    // Almacenar Data ->
                    fichasSearch = result.data.data[0]; // fichas relacionadas al programa
                    const jornadasSearch = result.data.data[1]; // Jornadas
                    const ofertaSearch = result.data.data[2]; // Ofertas
                    const trimestreSearch = result.data.data[3]; // Trimestres

                    // Listar data en select de jornadas ->
                    const jornadasSearchSelect = document.getElementById('jornadasSearchSelect');

                    // Listar Componentes ->
                    jornadasSearch.forEach((x) => {
                        const opcionjornadasSearch = document.createElement("option");
                        const jornadasSearchType = x.type_name;
                        opcionjornadasSearch.value = x.name;
                        opcionjornadasSearch.textContent = `${x.name}`;
                        jornadasSearchSelect.appendChild(opcionjornadasSearch);
                    });

                    // Listar data en select de ofertas ->
                    const offertSearchSelect = document.getElementById('offertSearchSelect');

                    // Listar Ofertas ->
                    ofertaSearch.forEach((x) => {
                        const opcionOfertasSearch = document.createElement("option");
                        const ofertasSearchTyoe = x.type_name;
                        opcionOfertasSearch.value = x.id;
                        opcionOfertasSearch.textContent = `${x.name}`;
                        offertSearchSelect.appendChild(opcionOfertasSearch);
                    });

                    // Listar data en select de oferta ->
                    const quarterstSearchSelect = document.getElementById('quarterstSearchSelect');

                    // Listar trimestre ->
                    trimestreSearch.forEach((x) => {
                        const opcionQuartersSarch = document.createElement("option");
                        const quartersSearchTpe = x.type_name;
                        opcionQuartersSarch.value = x.id;
                        opcionQuartersSarch.textContent = `${x.name}`;
                        quarterstSearchSelect.appendChild(opcionQuartersSarch);
                    });

                    // Listar fichas ->
                    fichasSearch.forEach((x) => {
                        const opcionStudySheetSearch = document.createElement("option");
                        const studySheetSearhType = x.type_name;
                        opcionStudySheetSearch.value = x.id;
                        opcionStudySheetSearch.textContent = `${x.number}` + (x.num ==
                            null ? '' : -x.num);
                        fichaSelectionStepTwo.appendChild(opcionStudySheetSearch);
                    });
                })
                .catch((err) => {});

        });


        /* Buscar fichas */
        document.getElementById("searchFicha").addEventListener("click", () => {

            // Reinicio variable ->
            fichasFilter = [];

            // Condicionales busquedad ->
            if (((select2Days.value) === 'jornadas...') && ((select2Offert.value) ===
                    'tipo de oferta...') && ((select2Quarters.value) === 'trimestre ficha...')) {

                fichasFilter = fichasSearch; // Se almacena los datos de las fichas

                // En caso de no encontrar ninguna coincidencias retornara un mensaje
                if (fichasFilter.length === 0) {

                    fichaGenerate.innerHTML =
                        fichaMessageEmpy; // fichaMessageEmpy es un elemento html con un mensaje
                    fichasSelection.style.display = 'block'; // Para que se muestre por pantalla

                } else {
                    /* Agregar espacio para listar las fichas */
                    fichaGenerate.innerHTML = ``; // Vaciar caja
                    fichaGenerate.innerHTML =
                        selectListFicha; // Mensaje de seleción de ficha, almacena un elemento <p></p>

                    /* Generar fichas */
                    let row = document.createElement("div");
                    row.classList.add("row"); // Crear un contenedor para las columnas
                    fichasFilter.forEach(x => {
                        let col = document.createElement("div");
                        col.classList.add("col-md-4"); // Asignar clase para crear una columna

                        let el = document.createElement("p");
                        el.id = x.id;
                        if (x.num == null) {
                            el.textContent = x.number;
                        } else {
                            el.textContent = `${x.number}-${x.num}`;
                        }
                        el.classList.add("optionF");
                        el.addEventListener("click", (event) => {
                            ficha = event.target.id;
                            fichaCont = event.target.innerHTML;
                            fichaJor = x.day;
                            if (opcionActiva) {
                                opcionActiva.classList.remove("input-group-active");
                            }
                            el.classList.add("input-group-active");
                            opcionActiva = el;
                        });

                        col.appendChild(el); // Agregar el elemento al div de la columna
                        row.appendChild(
                            col); // Agregar el div de la columna al contenedor de fila
                    });

                    document.getElementById('fichas').appendChild(
                        row); // Agregar el contenedor de fila al contenedor principal
                }
            } else {

                // Paso los valores de fichasSearch que contiene un json con los datos de las fichas
                // a otra variables que contendra la información y sobre la cual se realizara el filto
                fichasFilter = fichasSearch;

                // Recuperar valores de los selects
                let daySelectValueStepTwo = select2Days.value
                    .toLowerCase(); // recuperar el valor del select de jornada
                let offertSelectValueStepTwo = select2Offert
                    .value; // recuperar el valor del select de tipo de oferta
                let quarterSelectValueStepTwo = select2Quarters
                    .value; // recuperar el valor del select del trimestre

                // Filter
                // 1. Primer filtro hacia jornada, mediante una ternaria se examina el valor recuperado de los selects
                //    ejemeplo, si es igual jornada a 'jornadas...' no realizara el filtrado y pasara al siguiente filtro
                daySelectValueStepTwo != 'jornadas...' ?
                    fichasFilter = fichasFilter.filter((fichas) => fichas.day ===
                        daySelectValueStepTwo) : "";
                // 2. Segundo filtro hacia oferta, de la misma manera del anterior trabaja desde el anterior filtro
                //    si este llego a filtrar, de lo contrario continua
                offertSelectValueStepTwo != 'tipo de oferta...' ?
                    fichasFilter = fichasFilter.filter((fichas) => fichas.offer_id ===
                        offertSelectValueStepTwo) : "";
                // 3. Tercer filtro hacia el trimestre en el cual se encuentra la ficha, de la misma manera trabajo sobre el
                //    el anterior filtro
                quarterSelectValueStepTwo != 'trimestre ficha...' ?
                    fichasFilter = fichasFilter.filter((fichas) => fichas.quarter_id ===
                        quarterSelectValueStepTwo) : "";

                // En caso de no encontrar ninguna coincidencias retornara un mensaje
                if (fichasFilter.length === 0) {

                    fichaGenerate.innerHTML =
                        fichaMessageEmpy; // fichaMessageEmpy es un elemento html con un mensaje
                    fichasSelection.style.display = 'block'; // Para que se muestre por pantalla

                } else {
                    /* Agregar espacio para listar las fichas */
                    fichaGenerate.innerHTML = ``; // Vaciar caja
                    fichaGenerate.innerHTML =
                        selectListFicha; // Mensaje de seleción de ficha, almacena un elemento <p></p>

                    /* Generar fichas */
                    let row = document.createElement("div");
                    row.classList.add("row"); // Crear un contenedor para las columnas
                    fichasFilter.forEach(x => {
                        let col = document.createElement("div");
                        col.classList.add("col-md-4"); // Asignar clase para crear una columna

                        let el = document.createElement("p");
                        el.id = x.id;
                        if (x.num == null) {
                            el.textContent = x.number;
                        } else {
                            el.textContent = `${x.number}-${x.num}`;
                        }
                        el.classList.add("optionF");
                        el.addEventListener("click", (event) => {
                            ficha = event.target.id;
                            fichaCont = event.target.innerHTML;
                            fichaJor = x.day;
                            if (opcionActiva) {
                                opcionActiva.classList.remove("input-group-active");
                            }
                            el.classList.add("input-group-active");
                            opcionActiva = el;
                        });

                        col.appendChild(el); // Agregar el elemento al div de la columna
                        row.appendChild(
                            col); // Agregar el div de la columna al contenedor de fila
                    });

                    document.getElementById('fichas').appendChild(
                        row); // Agregar el contenedor de fila al contenedor principal
                }
            }
        });

        /* Seleccionar ficha */

        selectFichaStepTwo.onchange = (event) => {
            // Recuperar el valor
            ficha = selectFichaStepTwo.value;
            let elementoEncontrado = fichasSearch.find(elemento => elemento.id === ficha);
            fichaCont = elementoEncontrado.number;
            fichaJor = elementoEncontrado.day;

        };

        // * Tercera pagina *

        // Generar las opciones base para el formulario de programación
        document.getElementById("generarOpciones").addEventListener("click", () => {

            if (formStepsNum === 1 && (!opcionActiva || ficha === 0)) {
                swal.fire("Seleccione una ficha"); // Mensaje de alerta
                return; // Evita que el resto del código se ejecute
            } else {

                let data = {
                    ficha,
                };

                // Titulación
                info.innerHTML = `${programacont} <br> (${fichaCont} - ${fichaJor}) `;

                // LLamado al metodo del controlador
                axios.post("baseOptions", data)
                    .then((result) => {
                        const componentes = result.data.data[0];
                        const trimestres = result.data.data[1];
                        const bloques = result.data.data[2];

                        // Asegúrate de que componentesSelect esté definido y sea una referencia al elemento select
                        const componentesSelect = document.getElementById('componentesSelect');

                        // Generar el select con todos los componentes
                        componentes.forEach((x) => {
                            const opcionComponente = document.createElement("option");
                            const componentType = x
                                .type_name; // Declarado dentro del bloque forEach
                            opcionComponente.value = x.id;
                            opcionComponente.textContent = `${x.name} - ${componentType}`;
                            componentesSelect.appendChild(opcionComponente);
                        });

                        // Generar los trimestres del año
                        trimestresSelect.onchange = (event) => {
                            let opcionSel = trimestresSelect.options[trimestresSelect
                                .selectedIndex];

                            let start_date = opcionSel.dataset.startDate;
                            let finish_date = opcionSel.dataset.finishDate;

                            inicio.value = start_date;
                            final.value = finish_date;
                        };

                        // Generar los trimestres del año
                        trimestres.forEach((x) => {
                            let opcionTrimestre = document.createElement("option");
                            opcionTrimestre.value = x.id;
                            opcionTrimestre.textContent =
                                `Trimestre ${x.quarter} (${x.start_date} - ${x.finish_date})`;
                            opcionTrimestre.dataset.startDate = x.start_date;
                            opcionTrimestre.dataset.finishDate = x.finish_date;
                            trimestresSelect.appendChild(opcionTrimestre);
                        });

                        // Generar los bloques
                        bloqueSelect.onchange = (event) => {
                            let opcionSelBloq = bloqueSelect.options[bloqueSelect
                                .selectedIndex];
                            if ("time_start" in opcionSelBloq.dataset && "time_end" in
                                opcionSelBloq.dataset) {
                                inicioHora.value = opcionSelBloq.dataset.time_start;
                                finalHora.value = opcionSelBloq.dataset.time_end;
                            }
                        };

                        // Generar los bloques
                        bloques.forEach(bloque => {
                            let opcionBloque = document.createElement("option");
                            opcionBloque.value = bloque.id;
                            opcionBloque.textContent =
                                `${bloque.time_start} - ${bloque.time_end}`;
                            opcionBloque.dataset.time_start = bloque.time_start;
                            opcionBloque.dataset.time_end = bloque.time_end;
                            bloqueSelect.appendChild(opcionBloque);
                        });

                    })

                    .catch((error) => {

                    });

                // Ambiente
                componentesSelect.onchange = (event) => {

                    ambientesSelect.innerHTML =
                        '<option disabled selected>Seleccione un ambiente</option>';

                    if (componentesSelect.value === 'Seleccione un componente') {

                        ambientesSelect.disabled = true;

                    } else {

                        ambientesSelect.disabled = false; // Habilitar el select de ambiente
                        let component = componentesSelect
                            .value; // Valor id del select de componente

                        let data = {
                            component,
                        }

                        axios.post("ambiente", data)
                            .then((result) => {

                                const enviroment = result.data.data;

                                // Generar el select con todos los ambientes
                                enviroment.forEach((env) => {
                                    let opcionAmbientes = document.createElement(
                                        "option");
                                    opcionAmbientes.value = env.id;
                                    opcionAmbientes.textContent = env.name;
                                    ambientesSelect.appendChild(opcionAmbientes);
                                });

                            })

                    };
                };

                // Instructor
                ambientesSelect.onchange = (event) => {

                    // Retornar el valor de instructores disable
                    instructoresSelect.innerHTML =
                        '<option disabled selected>Seleccione un instructor</option>' +
                        '<option>Instructor en contratación</option>';

                    // Condicionales valor de ambienteSelect
                    if (ambientesSelect.value === 'Seleccione un ambiente') {

                        instructoresSelect.disabled = true;

                    } else {

                        // Habilitar select
                        instructoresSelect.disabled = false;

                        // Definir data para petición axios
                        let data = {
                            ficha,
                        }

                        // Solicitud de datos
                        axios.post("instructor", data)

                            .then((result) => {

                                // Data
                                const instructor = result.data.data;

                                // Listar elementos
                                instructor.forEach((x) => {

                                    let opcionIns = document.createElement('option');
                                    opcionIns.value = x.id;
                                    opcionIns.textContent =
                                        `${x.name.toUpperCase()} ${x.lastname.toUpperCase()} - CC ${x.document}`;
                                    instructoresSelect.appendChild(opcionIns);

                                })
                            })
                    }

                }

                /* Limpieza de formularios */

                document
                    .getElementById("volver2")
                    .addEventListener("click", () => {

                        // Limpiar los options
                        componentesSelect.innerHTML =
                            '<option disabled selected>Seleccione un componente</option>';
                        ambientesSelect.innerHTML =
                            '<option disabled selected>Seleccione un ambiente</option>';
                        trimestresSelect.innerHTML =
                            '<option disabled selected>Seleccione un trimestre</option>';
                        bloqueSelect.innerHTML =
                            '<option disabled selected>Seleccione un bloque</option>';
                        instructoresSelect.innerHTML =
                            '<option disabled selected>Seleccione un instructor</option>' +
                            '<option>Instructor en contratación</option>';

                        ambientesSelect.disabled = true;
                        instructoresSelect.disabled = true;

                        // Limpiar los inputs
                        fechaInicio.value = '';
                        fechaFinal.value = '';
                        inicioHora.value = '';
                        finalHora.value = '';
                    });

                // Ambiente
                componentesSelect.onchange = (event) => {

                    ambientesSelect.innerHTML =
                        '<option disabled selected>Seleccione un ambiente</option>';

                    if (componentesSelect.value === 'Seleccione un componente') {

                        ambientesSelect.disabled = true;

                    } else {

                        ambientesSelect.disabled = false; // Habilitar el select de ambiente
                        let component = componentesSelect
                            .value; // Valor id del select de componente

                        let data = {
                            component,
                        }

                        axios.post("ambiente", data)
                            .then((result) => {

                                const enviroment = result.data.data;

                                // Generar el select con todos los ambientes
                                enviroment.forEach((env) => {
                                    let opcionAmbientes = document.createElement(
                                        "option");
                                    opcionAmbientes.value = env.id;
                                    opcionAmbientes.textContent = env.name;
                                    ambientesSelect.appendChild(opcionAmbientes);
                                });

                            })

                    };
                };

                // Instructor
                ambientesSelect.onchange = (event) => {

                    // Retornar el valor de instructores disable
                    instructoresSelect.innerHTML =
                        '<option disabled selected>Seleccione un instructor</option>' +
                        '<option>Instructor en contratación</option>';

                    // Condicionales valor de ambienteSelect
                    if (ambientesSelect.value === 'Seleccione un ambiente') {

                        instructoresSelect.disabled = true;

                    } else {

                        // Habilitar select
                        instructoresSelect.disabled = false;

                        // Definir data para petición axios
                        let data = {
                            ficha,
                        }

                        // Solicitud de datos
                        axios.post("instructor", data)

                            .then((result) => {

                                // Data
                                const instructor = result.data.data;

                                // Listar elementos
                                instructor.forEach((x) => {

                                    let opcionIns = document.createElement('option');
                                    opcionIns.value = x.id;
                                    opcionIns.textContent =
                                        `${x.name.toUpperCase()} ${x.lastname.toUpperCase()} - Doc. ${x.document}`;
                                    instructoresSelect.appendChild(opcionIns);

                                })
                            })
                    }

                }

                document
                    .getElementById("volver2")
                    .addEventListener("click", () => {

                        // Limpiar los options
                        componentesSelect.innerHTML =
                            '<option disabled selected>Seleccione un componente</option>';
                        ambientesSelect.innerHTML =
                            '<option disabled selected>Seleccione un ambiente</option>';
                        trimestresSelect.innerHTML =
                            '<option disabled selected>Seleccione un trimestre</option>';
                        bloqueSelect.innerHTML =
                            '<option disabled selected>Seleccione un bloque</option>';
                        instructoresSelect.innerHTML =
                            '<option disabled selected>Seleccione un instructor</option>' +
                            '<option>Instructor en contratación</option>';

                        // Pagina Dos

                        ambientesSelect.disabled = true;
                        instructoresSelect.disabled = true;


                        // Limpiar los inputs
                        fechaInicio.value = '';
                        fechaFinal.value = '';
                        inicioHora.value = '';
                        finalHora.value = '';
                    });

            }

        });

        // Generar las sesiones de cada trimestre
        let info2 = document.querySelector('#info2');

        document.getElementById("generarSesiones").addEventListener("click", (event) => {

            event.preventDefault();

            /* Elementos formulario base */
            const inicioInput = document.getElementById("fechaInicio").value;
            const finalInput = document.getElementById("fechaFinal").value;
            const fechas = [];
            const fechaInicio = moment(inicioInput, "YYYY-MM-DD");
            const fechaFinal = moment(finalInput, "YYYY-MM-DD");
            let componente = componentesSelect.value;
            let ambiente = ambientesSelect.value;
            let bloqueI = horaI.value;
            let bloqueF = horaF.value;
            if (instructoresSelect.value == 'Instructor en contratación') {
                instructor = null;
            } else if (instructoresSelect.value != 'Seleccione un instructor') {
                instructor = instructoresSelect.value;
            }

            /* Elementos tool box */
            const inicioRangeInputToolBox = document.getElementById("startDateToolBox").value;
            const finalRangeInputToolBox = document.getElementById("endDateToolBox").value;
            const numSesionsToolBox = document.getElementById("numSesionsClassToolBox").value;
            const continuesProgramation = document.getElementById("continuesProgramation").value;

            // Clonar el array de las fechas
            let fechaActual = fechaInicio.clone();
            // Generar el array de las fechas según el trimestre
            while (fechaActual.isSameOrBefore(fechaFinal)) {
                fechas.push(fechaActual.format("YYYY-MM-DD"));
                fechaActual.add(1, "day");
            }

            /* Funciones de validaciones para reutilizar */

            function validationDataStep3() {
                if (inicio.value === '') {
                    swal.fire("Seleccione un trimestre");
                    return false;
                }
                else if (componentesSelect.value === 'Seleccione un componente') {
                    swal.fire("Seleccione un componente");
                    return false;
                }
                else if (ambientesSelect.value === 'Seleccione un ambiente') {
                    swal.fire("Seleccione un ambiente");
                    return false;
                }
                else if (horaI.value === '' || horaF.value === '') {
                    swal.fire("Defina el bloque");
                    return false;
                }
                else if (instructoresSelect.value == 'Seleccione un instructor') {
                    swal.fire("Seleccione un Instructor");
                    return false;
                }
                return true;
            }

                /* Validation num sesions in toolbox */
            function validationNumSessionToolBox(valueInputNumSessions) {
                regrexValue = /^(?:[1-9]|[1-8][0-9]|90)$/; // function regrex to validate

                if (valueInputNumSessions === '') {
                    errorNumSesionsToolBox.innerHTML = '';
                    return true;
                }

                if (!regrexValue.test(valueInputNumSessions)) {
                    errorNumSesionsToolBox.innerHTML = '<p style="color: red;">*Ingrese un valor valido que estre entre 1 y 90.</p>';
                    return false;
                }

                errorNumSesionsToolBox.innerHTML = '';
                return true;

            };

            /* Validaciones tool box */
            if (inicioRangeInputToolBox != '' || finalRangeInputToolBox != '' || numSesionsToolBox != '') {
                /* Mensaje de confirmación de la operación */
                swal.fire({
                    title: "¿Estas seguro?",
                    text: "Usar la caja de herramientas representa un cambio total en la manera en la que se programa. Si no esta seguro de lo que va a realizar mejor consulte el manual de usuario.",
                    icon: "warning",
                    confirmButtonText: "Confirmar",
                    showCancelButton: true,
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    /* Si el usuario da click en confirmar pasara a este paso */
                    if (result.isConfirmed) {
                        if (validationDataStep3()) {

                            /* Validar que las fechas esten correctas */

                            // 1. Validar que este la fecha inicial
                            if (inicioRangeInputToolBox === '' && finalRangeInputToolBox != '') {
                                errorFechaFinalToolBox.innerHTML = '';
                                errorFechaInicioToolBox.innerHTML = '<p style="color: red;">Por favor selecione la fecha.</p>';
                                return;
                            }

                            // 2. Validar que este la fecha final
                            else if (inicioRangeInputToolBox != '' && finalRangeInputToolBox === '') {
                                errorFechaInicioToolBox.innerHTML = '';
                                errorFechaFinalToolBox.innerHTML = '<p style="color: red;">Por favor selecione la fecha.</p>'
                                return;
                            }

                            // 3. Validar que la fecha inicial sea mayor a la final
                            else if (inicioRangeInputToolBox > finalRangeInputToolBox || finalRangeInputToolBox < inicioRangeInputToolBox) {
                                errorFechaFinalToolBox.innerHTML = '';
                                errorFechaInicioToolBox.innerHTML = '';
                                errorFechaInicioToolBox.innerHTML = '<p style="color: red;">*La fecha inicial no puede ser mayor a la fecha final.</p>';
                                return;
                            }

                            // 5. Validar que las fechas esten dentro del rango de fechas del trimestre
                            else if ((inicioRangeInputToolBox < inicioInput || finalRangeInputToolBox > finalInput) && (inicioRangeInputToolBox != '' && finalRangeInputToolBox != '')) {
                                errorFechaFinalToolBox.innerHTML = '';
                                errorFechaInicioToolBox.innerHTML = '';
                                errorFechaInicioToolBox.innerHTML = '<p style="color: red;">Las fechas deben estar dentro del rango de ' + inicioInput + ' y ' + finalInput + '</p>';
                                return;
                            }

                            // 5. Validar que la fechas no esten en otros años mayores
                            else if (inicioRangeInputToolBox > inicioInput && finalRangeInputToolBox > finalInput) {
                                errorFechaFinalToolBox.innerHTML = '';
                                errorFechaInicioToolBox.innerHTML = '';
                                errorFechaInicioToolBox.innerHTML = '<p style="color: red;">Las fechas deben estar dentro del rango de ' + inicioInput + ' y ' + finalInput + '</p>';
                                return;
                            }

                            // 6. Validar que las fechas no esten en otros años menores
                            else if (inicioRangeInputToolBox < inicioInput && finalRangeInputToolBox < finalInput && inicioRangeInputToolBox != '' && finalRangeInputToolBox != ''){
                                errorFechaFinalToolBox.innerHTML = '';
                                errorFechaInicioToolBox.innerHTML = '';
                                errorFechaInicioToolBox.innerHTML = '<p style="color: red;">Las fechas deben estar dentro del rango de ' + inicioInput + ' y ' + finalInput + '</p>';
                                return;
                            }
                            else {
                                errorFechaFinalToolBox.innerHTML = '';
                                errorFechaInicioToolBox.innerHTML = '';

                                /* Validación del numero de sesiones personalizados */

                                if (!validationNumSessionToolBox(numSesionsToolBox)) {
                                    // En caso que no cumpla con las relas de un valor entre 0 a 90
                                    return;
                                }

                                else {
                                    console.log('todo bien');
                                }

                            }

                        }
                    }
                })
            } else {
                if (validationDataStep3()) {
                    // Declarar la data
                    let data = {
                        fechas,
                        componente,
                        ambiente,
                        ficha,
                        fichaJor,
                        bloqueI,
                        bloqueF,
                        inicioInput,
                        finalInput,
                        instructor,
                        confirmation1: true,
                        confirmation2: false,
                        confirmation3: false,
                    };

                    // Realizar la petición axios
                    axios.post("{{ route('horarios.store') }}", data).then((result) => {
                        if (result.data.success) {
                            Swal.fire({
                                title: "¡Guardado!",
                                text: result.data.message,
                                icon: "success",
                                showConfirmButton: true,
                                confirmButtonText: "Listo",
                                allowEscapeKey: false,
                                allowOutsideClick: false,
                            })

                            // Limpiar los options
                            componentesSelect.innerHTML = '<option disabled selected>Seleccione un componente</option>';
                            ambientesSelect.innerHTML = '<option disabled selected>Seleccione un ambiente</option>';
                            trimestresSelect.innerHTML = '<option disabled selected>Seleccione un trimestre</option>';
                            bloqueSelect.innerHTML = '<option disabled selected>Seleccione un bloque</option>';
                            instructoresSelect.innerHTML = '<option disabled selected>Seleccione un instructor</option>' + '<option>Instructor en contratación</option>';

                            //Reiniciar la pagina
                            window.location.href = "{{ route('horarios.index') }}";

                            // Capturar los instructores
                            instructores = result.data.data;
                            let fichaId = document.querySelector("#ficha");
                            let componenteId = document.querySelector("#componente");
                            fichaId.value = ficha;
                            componenteId.value = componente;



                        } else {
                            if (result.data) {
                                if (result.data.totalEvents - result.data.evenDayAvailable == 1 ||result.data.totalEvents - result.data.oddDayAvailable == 1) {
                                    Swal.fire({
                                        title: "¿Desea generar las sesiones, sin importar que no sea generada una sesion?",
                                        text: result.data.message,
                                        icon: "question",
                                        confirmButtonText: "Sí, generar",
                                        showCancelButton: true,
                                        cancelButtonText: "Cancelar",
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            let data1 = {
                                                fechas,
                                                componente,
                                                ambiente,
                                                ficha,
                                                fichaJor,
                                                bloqueI,
                                                bloqueF,
                                                inicioInput,
                                                finalInput,
                                                instructor,
                                                confirmation1: true,
                                                confirmation2: true,
                                            };

                                            // Realizar la petición axios
                                            axios
                                                .post("store", data1)
                                                .then((result1) => {
                                                    if (result1.data
                                                        .success) {
                                                        Swal.fire({
                                                            title: "¡Guardado!",
                                                            text: result1
                                                                .data
                                                                .message,
                                                            icon: "success",
                                                            showConfirmButton: true,
                                                            confirmButtonText: "Listo",
                                                            allowEscapeKey: false,
                                                            allowOutsideClick: false,
                                                        })

                                                        window.location.href = "{{ route('horarios.index') }}";


                                                    } else {
                                                        if (result1.data && result1.data.hasOwnProperty('totalEventsST') && result1.data.hasOwnProperty('evenDayAvailableST') && result1.data.hasOwnProperty('oddDayAvailableST')) {
                                                            data.confirmation2 = true;
                                                            console.log("ddddd");
                                                            validationStudySheet(result1, data);
                                                        } else {
                                                            Swal.fire({
                                                                title: "¡Programación interrupida...!",
                                                                text: result1.data? result1.data.message : "Error al procesar la respuesta",
                                                                icon: "error",
                                                                showConfirmButton: true,
                                                                confirmButtonText: "Listo",
                                                                allowEscapeKey: false,
                                                                allowOutsideClick: false,
                                                            });
                                                        }

                                                    }
                                                })
                                                .catch((error) => {});
                                        }
                                    });
                                }else if(result.data && result.data.hasOwnProperty('totalEventsST') && result.data.hasOwnProperty('evenDayAvailableST') && result.data.hasOwnProperty('oddDayAvailableST')){
                                    /////////////////////////////////////////////////////////////////////////////////
                                    // validacion ficha
                                    console.log("holaaa");
                                    validationStudySheet(result,data);
                                }else{
                                    Swal.fire({
                                    title: "¡Programación interrupida...!",
                                    text: result.data.message,
                                    icon: "error",
                                    showConfirmButton: true,
                                    confirmButtonText: "Listo",
                                    allowEscapeKey: false,
                                    allowOutsideClick: false,
                                });
                                }


                            } else {

                                Swal.fire({
                                    title: "¡Programación interrupida...!",
                                    text: result.data.message,
                                    icon: "error",
                                    showConfirmButton: true,
                                    confirmButtonText: "Listo",
                                    allowEscapeKey: false,
                                    allowOutsideClick: false,
                                });
                            }

                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
                }
            }

        });
    });



    function validationStudySheet(result,data){
        console.log(result.data.totalEventsST - result.data.evenDayAvailableST == 1);
        console.log(result.data.totalEventsST - result.data.oddDayAvailableST == 1);
        if (result.data.totalEventsST - result.data.evenDayAvailableST == 1 || result.data.totalEventsST - result.data.oddDayAvailableST == 1) {
                                    return Swal.fire({
                                        title: "¿Desea generar las sesiones, sin importar que no sea generada una sesion?",
                                        text: result.data.message,
                                        icon: "question",
                                        confirmButtonText: "Sí, generar",
                                        showCancelButton: true,
                                        cancelButtonText: "Cancelar",
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            data2 = {
                                                fechas: data.fechas,
                                                componente: data.componente,
                                                ambiente: data.ambiente,
                                                ficha: data.ficha,
                                                fichaJor: data.fichaJor,
                                                bloqueI: data.bloqueI,
                                                bloqueF: data.bloqueF,
                                                inicioInput: data.inicioInput,
                                                finalInput: data.finalInput,
                                                instructor: data.instructor,
                                                confirmation1: true,
                                                confirmation2:data.confirmation2,
                                                confirmation3: true,
                                            };
                                            // Realizar la petición axios
                                            axios
                                                .post("store", data2)
                                                .then((result1) => {
                                                    if (result1.data
                                                        .success) {
                                                        Swal.fire({
                                                            title: "¡Guardado!",
                                                            text: result1
                                                                .data
                                                                .message,
                                                            icon: "success",
                                                            showConfirmButton: true,
                                                            confirmButtonText: "Listo",
                                                            allowEscapeKey: false,
                                                            allowOutsideClick: false,
                                                        })

                                                        window.location.href ="{{ route('horarios.index') }}";


                                                    } else {
                                                        Swal.fire({
                                                            title: "¡Programación interrupida...!",
                                                            text: result1
                                                                .data
                                                                .message,
                                                            icon: "error",
                                                            showConfirmButton: true,
                                                            confirmButtonText: "Listo",
                                                            allowEscapeKey: false,
                                                            allowOutsideClick: false,
                                                        });
                                                    }
                                                })
                                                .catch((error) => {

                                                });
                                        }
                                    });
                                } else {
                                    return Swal.fire({
                                        title: "¡Programación interrupida...!",
                                        text: result.data.message,
                                        icon: "error",
                                        showConfirmButton: true,
                                        confirmButtonText: "Listo",
                                        allowEscapeKey: false,
                                        allowOutsideClick: false,
                                    });
                                }
                                return false;
    }
</script>
