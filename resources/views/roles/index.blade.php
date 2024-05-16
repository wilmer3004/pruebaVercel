@extends('layouts.plantilla')

{{-- Estilos --}}
<link rel="stylesheet" href="{{ asset('css/datatables.css') }}">
<link rel="stylesheet" href="{{ asset('css/validacion.css') }}">
<link rel="stylesheet" href="{{ asset('css/tooltip.css') }}">

@section('content')
@include('roles.create')
@include('roles.edit')
    {{-- Rol card --}}
    <div class="container d-flex flex-row-reverse">
        <div class="col-md-6 col-sm-8 col-lg-4 px-5 py-4">
            <div class="bg-white d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalroles }}</h3>
                    <p class="fs-5">{{ $totalroles ==1 ? 'Rol' : 'Roles' }}</p>
                </div>
                <i class="fas fa-user-tag fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </div>
        <div class="col-md-6 col-sm-8 col-lg-4 px-5 py-4">
            <div class="bg-white d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div> 
                    <h3 class="fs-2">{{ $totalrolesDeshabilitados }}</h3>
                    <p class="fs-5">{{ $totalrolesDeshabilitados == 1? 'Deshabilitado' : 'Deshabilitados' }}</p>
                </div>
                <i class="fas fa-solid fa-xmark fs-1 third-text border rounded-full third-bg p-3"></i>
            </div>
        </div>
        <div class="col-md-6 col-sm-8 col-lg-4 px-5 py-4">
            <div class="bg-white d-flex justify-content-around align-items-center around shadow-lg p-3 mb-5 bg-body rounded">
                <div>
                    <h3 class="fs-2">{{ $totalrolesHabilitados }}</h3>
                    <p class="fs-5">{{ $totalrolesHabilitados == 1? 'Habilitado' : 'Habilitados' }}</p>
                </div>
                <i class="fas fa-solid fa-check fs-1 primary-text border rounded-full secondary-bg p-3"></i>
            </div>
        </div>
    </div>
    {{-- End Rol card --}}

    {{-- Content --}}
    <div class="container">

        <h1 class="mb-3 text-center">ROLES</h1>

        <div class="container d-flex flex-row-reverse">
            <a href="" type="button" class="btn btn-large my-3 mx-2 boton" data-bs-toggle="modal"
                data-bs-target="#CreateRolModal"><i class="fas fa-plus-circle me-2"></i>Agregar Rol</a>
        </div>

        <div class="col container">
            <table class="table" id="roles">
                <thead>
                    <tr>
                        <th scope="col" width="80">#</th>
                        <th scope="col" width="">Rol</th>
                        <th scope="col" width="">Descripción</th>
                        <th scope="col" width="">Estado</th>
                        <th scope="col" width="150">Acciones</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @include('roles.js')
@endsection
