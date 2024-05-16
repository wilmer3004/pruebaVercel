<!-- index.blade.php -->
@extends('layouts.plantilla')

{{-- Estilos --}}
<link rel="stylesheet" href="{{ asset('css/charts.css') }}">

{{-- Libreria --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@section ('content')
    @csrf
    <div style="height: 900px; overflow: auto;">
        {{-- GRAFICAS --}}

            <h1 class="mt-5 text-center">GRAFICAS</h1>

            {{-- PROGRAMAS --}}
            
            <div class="container text-center mt-4 mb-5">
                <h3 class="text-start mb-5" style="color: #033f60">PROGRAMAS <i class="fa-solid fa-book"></i></h3>
                <div class="row align-items-center mt-5">
                    <div class="card mx-auto my-auto mb-3" style="width: 600px; height: 600px;">
                        <div class="card-body">
                            <!-- Numero de fichas en los programas (general) -->
                            <canvas id="polarChart"></canvas>
                        </div>
                    </div>
                    <div class="card mx-auto my-auto mb-3" style="width: 600px; height: 600px;">
                        <div class="card-body">
                            <!-- Números de fichas en programas filtrado por jornada -->
                            <canvas id="stackedBarChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="row align-items-center mt-5">
                    <div class="card mx-auto my-auto mb-3" style="width: 600px; height: 600px;">
                        <div class="card-body">
                            <!-- Número de programas por tipo de oferta y trimestre -->                     
                            <canvas id="barChartWithBorderRadius"></canvas>
                        </div>
                    </div>
                    <div class="card mx-auto my-auto mb-3" style="width: 600px; height: 600px;">
                        <div class="card-body">
                            <!-- Numero de Instructores por coordinación, tipo de contrato que tienen y el número de instructores con condiciones de horas -->
                            <canvas id="radarChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="row align-items-center mt-5">
                    <div class="card mx-auto my-auto mb-3" style="width: 700px; height: 400px;">
                        <div class="card-body">
                            <canvas id="groupedBarChart"></canvas>
                        </div>
                    </div>
                </div>

        </div>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    </div>
    
    @include('graficas.js')





@endsection
