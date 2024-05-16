<script>
//////////////////////////////////////////////////////////////////////////////////
    let dataRadarChart = @json($radarChart);
    let dataRadarChartA = @json($radarChartA);
    let dataRadarChartB = @json($radarChartB);
    let labelDataArray = dataRadarChartB.map(category => category.name);
    let countTeachers = [0];
    let sumTeacherRadar  =4;




        ////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //DATA COLORS
        var colorsPrograms = @json($colorsPrograms);

        var programColors = colorsPrograms.reduce(function(acc, program) {
            acc[program.name] = program.color; // Asume que el color ya está en formato rgba
            return acc;
        }, {});
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////


        var groupedData = {};
        var groupedDataRadar = {};

        // DATOS DE LA BASE DE DATOS

        //PROGRAMAS
        function  showPrograms(){
            axios.get('{{route('programas.listar')}}')
            .then((result) => {
                let datos = result.data;
            })
            .catch((error) => {
                console.log(error);
            });

        }
        showPrograms();


        // OFERTAS
        function  showoffers(){
            axios.get('{{route('ofertas.listar')}}')
            .then((result) => {
                let datos = result.data;
            })
            .catch((error) => {
                console.log(error);
            });

        }

        let dataStackedBarChart = @json($stackedBarChart);
        // console.log(dataStackedBarChart);
        // dataStackedBarChart.map((item) => {
        //     console.log(item);
    // });

    /////////////////////////////////////////////////
    //BACKGROUND ALEATORIO

        // Función para generar un color aleatorio
        // 'rgba(54, 162, 235, 0.5)'
        // function getRandomColor() {
        //     var color = 'rgba(';
        //     for (var i =  0; i <  3; i++) {
        //         // Genera un número aleatorio entre  54 y  255 para cada componente de color
        //         var randomValue = Math.floor(Math.random() *  202) +  54;
        //         color += randomValue + ', ';
        //     }
        //     color += '0.5)'; // Opacidad fija de  0.5
        //     return color;
        // }


        ////////////////////////////////////////////////////////////////////////////////////////
        // funcion para determinar el color
            function getColor(label){
                var color = programColors[label];
                return color || null;
            }



    // Función para agregar datos a las etiquetas
    function addDataToLabel(label, data) {
        if (!groupedData[label]) {
            // Si la etiqueta no existe, inicialízala con un array de ceros
            groupedData[label] = Array(5).fill(0);
        }
        // Suma el conteo de programas para la jornada correspondiente
        groupedData[label][['mañana', 'tarde', 'noche', 'madrugada','fin de semana'].indexOf(data.name)] += parseInt(data.program_count);
    }



        // Itera sobre dataStackedBarChart y agrega los datos a las etiquetas
        dataStackedBarChart.forEach((item) => {
            addDataToLabel(item.program_name, item);
            console.log(groupedData);
        });

        // Convierte el objeto en el formato requerido por Chart.js
        var datasets = Object.keys(groupedData).map((label) => ({
            label: label,
            data: groupedData[label],
            backgroundColor: getColor(label),
             // Utiliza la función getColor para obtener el color
        }));

        //////////////////////////////////////////////////////////////////////////////////////
        //RADARCHART



        function addDataToRadarChart() {
            // Inicializa el objeto que va a contener los datos agrupados por labels
            var groupedDataRadar = {};

            // Itera sobre cada elemento en dataRadarChartA
            dataRadarChartA.forEach((item) => {
                // Crea una clave única para cada label basada en nameCo y nameCondi


                var labelKey = item.nameC !== '' && item.nameCondi !== '' && item.nameCondi !== null ? item.nameCo + '_' + item.nameCondi : item.nameCo;
                let labelName = item.nameC !== '' && item.nameCondi !== '' && item.nameCondi !== null ? item.nameCo + ' ' + item.nameCondi : item.nameCo;

                // Verifica si ya existe una entrada para esta label
                if (!groupedDataRadar[labelKey] && item.stateU ==='activo') {
                    // Si no existe, inicializa un nuevo objeto con un array vacío para los datos
                    groupedDataRadar[labelKey] = {
                        label: labelName, // La etiqueta será la concatenación de nameCo y nameCondi
                        data: [], // Un array vacío para los datos
                    };
                }

                // Encuentra el índice de nameC en labelDataArray para saber en qué posición colocar el teacher_count
                var index = labelDataArray.indexOf(item.nameC);

                // Si el índice es válido (no -1), agrega el teacher_count al array de datos en la posición correspondiente
                if (index !== -1 && item.stateU ==='activo') {
                    groupedDataRadar[labelKey].data[index] = item.teacher_count;
                    countTeachers.push(item.teacher_count);
                }
            });

            // Convierte el objeto de datos agrupados en un array de datasets para el gráfico


            return dataSetsRadar = Object.values(groupedDataRadar);

            // Ahora puedes usar dataSetsRadar para configurar tu gráfico radar
            // ...
        }

        var dataSetsRadar= addDataToRadarChart()
        sumTeacherRadar = Math.max(...countTeachers) + 2;





////////////////////////////////////////////////////////////////////////////////////

        // DATOS DE PRUEBA PARA REPRESENTACIÓN DE GRAFICOS

        var dataStackedBar = {
            labels: ['January', 'February', 'March', 'April', 'May'],
            datasets: [{
                label: 'Dataset 1',
                data: [10, 20, 30, 40, 50],
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
            }, {
                label: 'Dataset 2',
                data: [20, 30, 10, 25, 35],
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
            }, {
                label: 'Dataset 3',
                data: [15, 25, 35, 15, 30],
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
            }]
        };

        var dataBar = {
            labels: ['January', 'February', 'March', 'April', 'May'],
            datasets: [{
                label: 'Dataset 1',
                data: [10, 20, 30, 40, 50],
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderRadius: 10
            }]
        };

        var dataRadar = {
            labels: labelDataArray,
            datasets: dataSetsRadar
        };

        var dataGroupedBar = {
            labels: ['January', 'February', 'March', 'April', 'May'],
            datasets: [{
                label: 'Dataset 1',
                data: [10, 20, 30, 40, 50],
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
            }, {
                label: 'Dataset 2',
                data: [15, 25, 35, 45, 55],
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
            }]
        };

        //////////////////////////////////////////////////////////////////////////////////
        // Opciones de configuración para los gráficos


        var options = {
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true
                }
            }
        };


        //////////////////////////////////////////////////////////////////////////////////
        // Crear los gráficos

        var ctxRadar = document.getElementById('radarChart').getContext('2d');
        var ctxGroupedBar = document.getElementById('groupedBarChart').getContext('2d');


        //////////////////////////////////////////////////////////////////////////////////
        // Polar grafic

        var ctxPolar = document.getElementById('polarChart').getContext('2d');
        var labelsPolarRadar = {!!json_encode($namePrograms->pluck('name')) !!};
        var dataPolarRadar = {!! json_encode($resuladoPolarRadar->pluck('program_count')) !!};


        var polarChart = new Chart(ctxPolar, {
            type: 'polarArea',
            data: {
                labels: labelsPolarRadar,
                datasets: [{
                    label: 'Número de Fichas',
                    data: dataPolarRadar,
                    backgroundColor: labelsPolarRadar.map(function(label) {
                        return programColors[label] || 'rgba(0,  0,  0,  0.5)'; // default color if not found
                    }),
                    borderColor: labelsPolarRadar.map(function(label) {
                        // You can also map the border colors in the same way if needed
                        return programColors[label] || 'rgba(0,  0,  0,  1)'; // default color if not found
                    }),
                    borderWidth:  1
                }]
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'Numero de fichas por programa',
                        font: {
                            size:  20
                        }
                    }
                },
                scales: {
                    r: {
                        suggestedMin:  0
                    }
                }
            }
        });



        //////////////////////////////////////////////////////////////////////////////////
        // Stacked Bar
        var dataStackedBar = {
            labels: ['Jornada de la Mañana', 'Jornada de la Tarde', 'Jornada de la Noche','Jornada madrugada<', 'Jornada fin'],
            datasets: datasets,
        };




        var options = {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Numero de fichas por programa y jornada',
                    font: {
                        size: 20
                    }
                }
            },

            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true
                }
            }
        };

        var ctxStackedBar = document.getElementById('stackedBarChart').getContext('2d');
        ctxStackedBar.canvas.width = 800; // Ajusta el ancho del gráfico
        ctxStackedBar.canvas.height = 200; // Ajusta la altura del gráfico

        new Chart(ctxStackedBar, {
            type: 'bar',
            data: dataStackedBar,
            options: options
        });

        /////////////////////////////////////////////////////////////////////////////////////
        // Bar chart Border Radius

        // Inicio de configuración datos


        var ctxBar = document.getElementById('barChartWithBorderRadius').getContext('2d');

        new Chart(ctxBar, {
            type: 'bar',
            data: dataBar,
            options: {}
        });

        /////////////////////////////////////////////////////////////////////////////////////

        new Chart(ctxRadar, {
            type: 'radar',
            data: dataRadar,
            options: {
                scales: {
                    r: {
                        min:  0,
                        max: sumTeacherRadar, // Set the max value to the sum of teacher_count values
                        // ... other scale options
                    }
                },
                // ... other chart options
            }
        });

        new Chart(ctxGroupedBar, {
            type: 'bar',
            data: dataGroupedBar,
            options: {}
        });



</script>
