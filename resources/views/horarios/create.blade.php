@extends('layouts.plantilla')

{{-- Estilos --}}
<link rel="stylesheet" href="{{ asset('css/formulariohr.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltip.css') }}">

@section('content')
    {{-- <div class="container py-5">
        <h1 class=" text-center  text">PROGRAMACIÓN HORARIO</h1>
    </div> --}}
    @include('horarios.js')

    <div class="body my-5">

        {{-- Linea proceses --}}
        <form class="form">

            {{-- Barra de Progreso --}}
            <div class="progressbar">

                <div class="progress" style="height: 4px" id="progress"></div>
                <div class="progress-step progress-step-active" data-title="Programa"></div>
                <div class="progress-step" data-title="Ficha"></div>
                <div class="progress-step" data-title="Opciones"></div>

            </div>

        </form>

        {{-- Primera Página --}}
        <form action="#" class="form form-step form-step-active mt-3" id="form1">

            @csrf
            {{-- <div class="form-step form-step-active"> --}}
            <h3 class="tittle fw-bold">Seleccione un programa</h3>
            <hr>
            <div class="input-group">

                <ul>
                    @foreach ($programas as $programa)
                        <li>
                            <p id="{{ $programa->id }}" class="optionP">{{ $programa->name }}</p>
                        </li>
                    @endforeach
                </ul>

            </div>
            <div class="bts-group">

                <a href="{{ route('horarios.index') }}" class="bt bt-prev" id="">Calendario</a>
                <input class="bt bt-next" value="Siguiente" id="generarFichas">

            </div>
            {{-- </div> --}}

        </form>

        {{-- Segunda Página --}}
        <form action="#" class="form form-step mt-3" id="form2">
            @csrf

            <div class="form-step2">

                {{-- Titulo --}}
                <h3 class="tittle fw-bold" id="programas"></h3>
                <h5 class="tittle fw-bold">Ficha</h5>
                <hr>

                {{-- Jornada y Oferta --}}
                <div class="container">
                    <div class="row">
                        {{-- Jornada --}}
                        <div class="col">
                            <label for="days[]" class="titleLabel">Jornada</label>
                            <br>
                            <select id="jornadasSearchSelect" class="select2Days selectStepTwo" name="days[]"
                                style="width: 95%">
                                <option disabled selected>jornadas...</option>
                            </select>
                        </div>
                        {{-- Tipo de oferta --}}
                        <div class="col">
                            <div class="col">
                                <label for="ofert[]" class="titleLabel">Oferta</label>
                                <br>
                                <select id="offertSearchSelect" class="select2Offert selectStepTwo" name="ofert[]"
                                    style="width: 95%">
                                    <option disabled selected>tipo de oferta...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <div class="row align-items-center">
                        {{-- Trimestre --}}
                        <div class="col">
                            <label for="quarters[]" class="titleLabel">Trimestre</label>
                            <br>
                            <select id="quarterstSearchSelect" class="select2Quarters selectStepTwo" name="quarters[]"
                                style="width: 95%">
                                <option disabled selected>trimestre ficha...</option>
                            </select>
                        </div>
                        {{-- Buscar --}}
                        <div class="col">
                            <label for="studySheets[]" class="titleLabel">Ficha</label>
                            <select class="" name="fichaSelectionStepTwo" id="fichaSelectionStepTwo"
                                style="width: 95%;">
                                <option disabled selected>Seleccione una ficha</option>
                            </select>
                        </div>
                    </div>

                    <button class="bt-icon mt-4" id="searchFicha" type="button">Buscar</button>

                </div>

                <hr>

                <div class="fichasSelection">
                    {{-- Seleccion ficha --}}
                    <section class="messsageSearch">
                        <p class="subTitle">Selección de ficha</p>
                        <p class="textDown">(jornada, oferta, trimestre)</p>
                    </section>
                </div>

                {{-- Boton --}}
                <div class="bts-group">
                    <a href="#" class="bt bt-prev" id="volver">Volver</a>
                    <input class="bt bt-next" value="Siguiente" id="generarOpciones">
                </div>
            </div>

        </form>

        {{-- Tercer Página --}}
        <form action="#" class="form form-step mt-3" id="form3">
            @csrf
            {{-- <div class="form-step"> --}}
            <h3 class="tittle fw-bold" id="info"></h3>
            <hr>
            <div class="input-group">

                <div class="row">

                    <div class="col-md-3">
                        <label class="form-label fw-bold"></label><br>
                    </div>

                    <!-- Asignación de fechas -->

                    <div class="col-md-9 cont-tri">
                        <label class="form-label fw-bold">Trimestre</label><br>
                        <select class="select3" name="trimestres" id="trimestres" style="width: 70%;">
                            <option disabled selected>Seleccione un trimestre</option>
                        </select>
                    </div>


                    <div class="col-12 col-md-6 mt-2 mb-4">
                        <label class="form-label fw-bold">Fecha Inicial</label><br>
                        <input class="fecha fechaInicio" type="date" name="fechaInicio" id="fechaInicio" readonly>
                        <! -- readOnly para que no sean modificados desde el front-end-- !>
                    </div>

                    <div class="col-12 col-md-6 mt-2 mb-4">
                        <label class="form-label fw-bold">Fecha Final</label><br>
                        <input class="fecha fechaFinal" type="date" name="fechaFinal" id="fechaFinal" readonly>
                    </div>

                    <!-- Asignación de componente -->

                    <div class="col-12 col-md-6 mt-2 mb-4">
                        <label class="form-label fw-bold">Componente</label><br>
                        <select class="form-control select1 select" id="componentesSelect" style="width: 80%;">
                            <option disabled selected>Seleccione un componente</option>
                        </select>
                    </div>

                    <!-- Asignación de ambiente -->

                    <div class="col-12 col-md-6 mt-2 mb-4">
                        <label class="form-label fw-bold">Ambiente</label><br>
                        <select disabled class="form-control select2 select" id="ambientesSelect" style="width: 80%;">
                            <option disabled selected>Seleccione un ambiente</option>
                        </select>
                    </div>

                    <!-- Asignación de hora -->

                    <div class="col-12 col-md-6 mt-2 mb-4">

                        <label class="form-label fw-bold">Bloque</label><br>
                        <select class="select3" name="bloques" id="bloques" style="width: 80%;">
                            <option disabled selected>Seleccione un bloque</option>
                        </select>

                    </div>

                    <div class="col-6 col-md-3 mt-1 mb-2">

                        <label class="form-label fw-bold">Hora Inicial</label><br>
                        <div class="tooltipA" data-tooltip="Hora de Inicio">
                            <input class="hora horaInicio" type="time" name="fechaInicio" id="horaI" readonly>
                        </div>

                    </div>

                    <div class="col-6 col-md-3 mt-1 mb-2">

                        <label class="form-label fw-bold">Hora Final</label><br>
                        <div class="tooltipA" data-tooltip="Hora de Fin">
                            <input class="hora horaFinal" type="time" name="fechaFinal" id="horaF" readonly>
                        </div>

                    </div>

                    <!-- Asignación de Instructor -->

                    <div class="col-md-9 mb-2" >

                        <label class="form-label fw-bold">Instructor</label><br>
                        <select disabled class="select3" name="instructores" id="instructores" style="width: 90%;">
                            <option disabled selected>Seleccione un instructor</option>
                            <option>Instructor en contratación</option>
                        </select>

                    </div>

                    {{-- Caja de herramientas --}}
                    <div class="col-md-20 mb-2 mt-4">
                        {{-- Boton --}}
                        <p>
                            <button class="bt" type="button" data-bs-toggle="collapse" href="#toolboxcollapse"
                                aria-expanded="false" aria-controls="toolboxcollapse">
                                <i class="fa-solid fa-gear"></i></button>
                        </p>
                        {{-- Contenido caja de herramienta --}}
                        <div class="collapse collapse-horizontal" id="toolboxcollapse">
                            <div class="card card-body">
                                {{-- Personalización de fechas --}}

                                <h5 class="title_color title_color_tool_box mb-3">Caja de herramientas <i class="fa-solid fa-gear"></i></h5>

                                <div class="container">
                                    <label class="form-label fw-bold title_color">Personalizacion de fechas</label>
                                    <div class="row">
                                        <div class="col">
                                            {{-- Fecha inicio de toolbox --}}
                                            <label class="form-label fw-bold title_color" for="startDateToolBox">Fecha inicio</label><br>
                                            <input class="fechaToolBox fechaInicioToolBox" type="date"
                                                name="fechaInicioToolBox" id="startDateToolBox">
                                            <span id="errorFechaInicioToolBox"></span>
                                        </div>
                                        <div class="col">
                                            {{-- Fecha final de toolbox --}}
                                            <label class="form-label fw-bold title_color" for="endDateToolBox">Fecha final</label><br>
                                            <input class="fechaToolBox fechaFinalToolBox" type="date"
                                                name="fechaInicio" id="endDateToolBox">
                                            <span id="errorFechaFinalToolBox"></span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Personalización de numero de sesiones y Progrmación continua --}}
                                <div class="container">
                                    <div class="row">
                                        <div class="col">
                                            {{-- Personalización de numero de sesiones --}}
                                            <label class="form-label fw-bold title_color" for="numSesionsClassToolBox">Personalización numero de sesiones</label><br>
                                            <input type="number" id="numSesionsClassToolBox" min="1" max="90">
                                            <span id="errorNumSesionsToolBox"></span>
                                        </div>
                                        <div class="col">
                                            {{-- Programación continua --}}
                                            <label class="form-label fw-bold title_color" for="continuesProgramation">Programación continua</label>
                                            <input type="checkbox" class="check_box_programacion_continua" id="continuesProgramation">
                                            <span id="errorContinuesProgramationToolBox"></span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Boton de reset --}}
                                <a href="#" class="bt bt_clear_data_tool_box" id="clearToolBox">Limpiar <i class="fa-solid fa-broom"></i></a>

                            </div>
                        </div>
                    </div>

                </div>

            </div>


            <div class="bts-group">
                <a href="#" class="bt bt-prev" id="volver2">Volver</a>
                <input class="bt" value="Generar Sesiones" id="generarSesiones">
            </div>
            {{-- </div> --}}
        </form>

    </div>

    {{-- <script src="{{ asset('js/horarios/funciones.js') }}" defer></script> --}}
@endsection
