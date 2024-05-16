@extends('layouts.plantilla')

<link rel="stylesheet" href="{{ asset('css/programas.css') }}">
<link rel="stylesheet" href="{{ asset('css/validacion.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltip.css') }}">
<link rel="stylesheet" href="{{ asset('css/horarioInformation.css') }}">


@section('content')
@csrf
@include('horarioinformation.instructor.showEventsTeacher')


     {{-- Navbar --}}
     <div class="container my-3">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="navbutton button nav-link fs-5 {{ request()->routeIs('horarioInformation.datatable') ? 'active' : '' }}"
                    aria-current="page" href="{{ route('horarioInformation.datatable') }}"><i class="fas fa-users me-2"></i>Fichas</a>
            </li>
            <li class="nav-item">
                <a class="navbutton button nav-link fs-5 {{ request()->routeIs('horarioInformationTeacher.index') ? 'active' : '' }}"
                    aria-current="page" href="{{ route('horarioInformationTeacher.index') }}"><i
                        class="fas fa-network-wired me-2"></i>Instructores</a>
            </li>
        </ul>
    </div>
    {{-- End navbar --}}

    {{-- Programas card --}}
    <div class="container d-flex flex-row-reverse">
        <div class="col-md-6 col-sm-6 col-lg-4 px-5 py-2">
            <div
                class=" bg-white d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalEvents }}</h3>
                    <p class="fs-5">Eventos Instructor</p>
                </div>
                <i class="fa-regular fa-calendar-days fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </div>
    </div>
    {{-- End Programas card --}}

    <div class="container">

        <h1 class="mb-3 text-center text">Eventos Instructor</h1>

        <div class="col container">
            <a href="{{route('horarios.create')}}" type="button" class="btn btn-large my-3 mx-2 boton">
                <i class="fas fa-plus-circle me-2"></i>Programación</a>
            <table class="table" id="eventsTeacher">

            </table>
        </div>
    </div>
    @include('horarioinformation.instructor.showEventsTeacherJS')
    @include('horarioinformation.instructor.jsTableTeacher')
    @endsection







