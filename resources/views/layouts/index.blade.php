@extends('layouts.plantilla')

@section('content')
    {{-- cards --}}

    <div class="container fluid px-4">
        @role('superadmin|administrador|programador')
        <div class="row g-3 my-2">

            {{-- Users card --}}
            <div class="col-md-3">
                <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center around">
                    <div>
                        <h3 class="fs-2">{{ $users }}</h3>
                        <p class="fs-5">Usuarios</p>
                    </div>
                    <i class="fas fa-users fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                </div>
            </div>

            {{-- ficha card --}}
            <div class="col-md-3">
                <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center around">
                    <div>
                        <h3 class="fs-2">{{ $fichas }}</h3>
                        <p class="fs-5">Fichas</p>
                    </div>
                    <i class="fas fa-user-graduate fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                </div>
            </div>

            {{-- programas card --}}
            <div class="col-md-3">
                <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center around">
                    <div>
                        <h3 class="fs-2">{{ $programas }}</h3>
                        <p class="fs-5">Programas</p>
                    </div>
                    <i class="fas fa-book fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                </div>
            </div>

            {{-- ambientes card --}}
            <div class="col-md-3">
                <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center around">
                    <div>
                        <h3 class="fs-2">{{ $ambientes }}</h3>
                        <p class="fs-5">Ambientes</p>
                    </div>
                    <i class="fas fa-school fs-1 primary-text border rounded-full secondary-bg p-3"></i>
                </div>
            </div>

        </div>
        @endrole
    </div>
@endsection
