@extends('layouts.plantilla')

<link rel="stylesheet" href="{{ asset('css/calendario.css') }}">
{{-- <link rel="stylesheet" href="{{ asset('css/formulariohr.css') }}"> --}}

@section('content')
    @include('horarios.createmodal')
    <div class="container d-flex flex-row-reverse">
        {{-- Boton para redirigir a Programación --}}
        <a href="{{ route('horarios.create') }}" type="button" class="btn btn-large my-3 mx-2 boton">
            <i class="fas fa-plus-circle me-2"></i>Programación</a>
        {{-- Boton off canvas para herramienta de filtros --}}
        <a class="btn btn btn-large my-3 mx-2 boton" data-bs-toggle="offcanvas" href="#tool_filter" role="button"
            aria-controls="tool_filter">
            <i class="fa-solid fa-filter"></i> Filtros
        </a>
    </div>

    {{-- Estructura del off canvas --}}
    <div class="offcanvas offcanvas-start" tabindex="-1" id="tool_filter" aria-labelledby="tool_filterLabel"
        style="overflow-y: visible;">
        {{-- Titulo off canvas --}}
        <div class="offcanvas-header">
            <h5 class="offcanvas-title title_off_cavas" id="tool_filterLabel">Caja de filtros <i
                    class="fa-solid fa-filter"></i></h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        {{-- Cuerpo del offcanvas --}}
        <div class="offcanvas-body">
            <hr>
            <form class="text-start" id="filtrar">

                {{-- Dates --}}

                <div class="text-start mb-4">
                    <div class="row">
                        <div class="col" id="contStart">
                            <h5 class="subtitle_canvas_off my-3">Fecha Inicio <i class="fa-solid fa-circle"></i></h5>
                            <input type="date" class="form-control" name="start" id="start"
                                placeholder="First name" aria-label="First name">
                        </div>
                        <div class="col" id="contEnd">
                            <h5 class="subtitle_canvas_off my-3">Fecha Final <i class="fa-regular fa-circle"></i></h5>
                            <input type="date" class="form-control" name="end" id="end" placeholder="Last name"
                                aria-label="Last name">
                        </div>
                    </div>
                </div>

                <hr>

                {{-- Selects --}}

                {{-- Select de componentes --}}
                <h5 class="subtitle_canvas_off mt-4 mb-3">Componente <i class="fa-solid fa-book-open"></i></h5>
                <select class="form-control select1 select" id="componentesSelect" name="componentesSelect"
                    style="width: 80%;" multiple>
                    <option disabled>Seleccione componentes</option>
                </select>
                {{-- Select de Ambientes --}}
                <h5 class="subtitle_canvas_off my-3">Ambiente <i class="fa-solid fa-school"></i></h5>
                <select class="form-control select1 select" id="ambienteSelect" name="ambienteSelect" style="width: 80%;"
                    multiple>
                    <option disabled>Seleccione ambientes</option>
                </select>
                {{-- Select de ficha --}}
                <h5 class="subtitle_canvas_off my-3">Ficha <i class="fa-solid fa-graduation-cap"></i></h5>
                <select class="form-control select1 select" id="fichaSelect" name="fichaSelect" style="width: 80%;"
                    multiple>
                    <option disabled>Seleccione fichas</option>
                </select>
                {{-- Select de instructor --}}
                <h5 class="subtitle_canvas_off my-3">Instructor <i class="fa-solid fa-graduation-cap"></i></h5>
                <select class="form-control select1 select" id="instructorSelect" name="instructorSelect" style="width: 80%;"
                    multiple>
                    <option disabled>Seleccione instructor</option>
                </select>
                {{-- Select de tipo de componente  --}}
                <h5 class="subtitle_canvas_off my-3">Tipo de componente <i class="fa-solid fa-microchip"></i></h5>
                <select class="form-control select1 select" id="tipoComponentesSelect" name="tipoComponentesSelect" style="width: 80%;">
                    <option disabled selected>Seleccione un tipo componente</option>
                </select>
                <br>
                {{-- Botones de filtro y limpiar filtro --}}
                {{-- Submit --}}
                <div class=" d-flex justify-content-start mt-4">
                    <!-- Submit -->
                    <button type="submit" style="background-color: #00324d" class="btn btn-primary subtitle_canvas_off  me-3">
                        Filtrar <i class="fa-solid fa-filter"></i>
                    </button>

                    {{-- Reiniciar --}}

                    <button type="button" id="reloadButton" style="background-color: #00324d" class="btn btn-primary subtitle_canvas_off me-3">
                        Reiniciar <i class="fa-solid fa-rotate-right"></i>
                    </button>

                    {{-- Clean --}}

                    <button id="clean" type="button" style="background-color: #00324d" class="btn btn-primary subtitle_canvas_off">
                        Limpiar <i class="fa-solid fa-backward"></i>
                    </button>
                </div>

                <br>
                <span id="error" class="mt-3"></span>
            </form>
        </div>
    </div>

    {{-- Calendario --}}
    <div class="container">
        <div class="card-body">
            <div class="calendar" id="calendar"></div>
        </div>
    </div>

    <script src="{{ asset('js/horarios/calendario.js') }}" type="module"></script>
    {{-- <script src="{{ asset('js/horarios/funciones.js') }}"></script> --}}
@endsection
