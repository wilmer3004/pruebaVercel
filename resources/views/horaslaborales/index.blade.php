@extends('layouts.plantilla')

{{-- Estilos --}}
<link rel="stylesheet" href="{{ asset('css/users.css') }}">
<link rel="stylesheet" href="{{ asset('css/validacion.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltip.css') }}">

@section('content')
@include('horaslaborales.create')

    {{-- Users card --}}
    {{-- <div class="container d-flex flex-row-reverse">
        <div class="col-md-3 px-5 py-2">
            <div
                class="bg-white  d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">123</h3>
                    <p class="fs-5"></p>
                </div>
                <i class="fas fa-chalkboard-teacher fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </div>
    </div> --}}

    {{-- content --}}
    <div class="container">

        <h1 class="mb-3 text-center py-3 text">HORAS</h1>

        <div class="container d-flex flex-row-reverse">

            <a href="" type="button" class="btn btn-large btn-success my-5 mx-2" data-bs-toggle="modal"
                data-bs-target="#CreateHorasModal"><i class="fas fa-plus-circle me-2"></i>Agregar Hora</a>
        </div>

        <div class="col table2 container">
            <table class="table table-hover shadow-lg p-3 mb-5 bg-body rounded index">
                <thead>
                    <tr>
                        <th scope="col" width="50">#</th>
                        <th scope="col" width="200">Tipo de Contrato</th>
                        <th scope="col">Horas diaria mínima</th>
                        <th scope="col">Horas diaria máxima</th>
                        <th scope="col">Horas mensual mínima</th>
                        <th scope="col">Horas mensual máxima</th>
                        <th scope="col" width="170">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($horas as $hora)
                        <tr>
                            <th scope="row">{{ $hora->id }}</th>
                            <td width="100">{{ $hora->contrato->name }}</td>
                            <td>{{ $hora->dh_min }}</td>
                            <td>{{ $hora->dh_max }}</td>
                            <td>{{ $hora->mh_min }}</td>
                            <td>{{ $hora->mh_max }}</td>
                            <td>
                                <div class="row">
                                    <div class="col-3 py-2">
                                        <a type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editHorasModal{{ $hora->id }}"><i
                                                class="far fa-edit"></i></a>
                                    </div>

                                    <div class="col-3 py-2">
                                        <a class="btn btn-sm btn-danger" href="{{ route('horas.destroy', $hora) }}" onclick="confirmation(event)"><i
                                            class="fas fa-trash"></i></a>
                                        {{-- <form action="{{ route('horas.destroy', $hora) }}" method="POST">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-sm"><i
                                                    class="fas fa-trash"></i></button>
                                        </form> --}}
                                    </div>
                                </div>
                            </td>
                        </tr>

                        {{-- Se incluye el modal --}}
                        @include('horaslaborales.edit')
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{-- content --}}
    <script src="{{ asset('js/horaslaborales/validaciones.js') }}"></script>
@endsection
