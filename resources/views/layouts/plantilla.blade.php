<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" href="{{asset('img/logosena_verde.png')}}">

    {{-- Estilos --}}
    <link rel="stylesheet" href="{{ asset('css/datatables.css') }}">

    <link rel="stylesheet" href="{{ asset('css/horarioInformation.css') }}">
    <link rel="stylesheet" href="{{ asset('css/horarioInformationTeacher.css') }}">

    {{-- Script para bootstrap --}}
    @vite(['resources/js/app.js'])

    {{-- Integracion de fullcalendar --}}
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    {{-- Integracion Jquery --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{-- Integracion de moment.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    {{-- Integracion de sweet alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Integracion de select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- Intregración de datatables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    {{-- <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bulma.min.js"></script> --}}

    {{-- Integracion de flatpickr --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <title>@yield('tittle')</title>
</head>

<body>

    @include('layouts.loader')
    @include('layouts.dashboard')

    <script defer>
        @if (session()->has('inicio'))
            Swal.fire({
                position: 'bottom-end',
                icon: 'success',
                title:
                `@auth
                    {{Auth::user()->persona->name}}
                @endauth has iniciado sesión`,
                showConfirmButton: false,
                timer: 2500,
                toast: true,
                timerProgressBar: true
            })
        @endif
    </script>

</body>

</html>
