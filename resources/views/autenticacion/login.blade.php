<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('img/logosena_verde.png') }}">
    {{-- Integracion de sweet alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Login</title>
    {{-- Script para bootstrap --}}
    @vite(['resources/js/app.js'])

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

</head>

<body>
    @include('layouts.loader')
    <main class="container d-flex align-items-center justify-content-center vh-100">
        <div class="card p-5">
            <div class="border-bottom">
                <h1 class="mb-0">Ingreso de usuarios</h1>
            </div>

            <div class="card-img">

            </div>

            <form method="POST" action="{{ route('login') }}" class="mt-4">
                @csrf
                <div class="mb-3">
                    <label for="emailInput" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="emailInput"
                        placeholder="Ingrese su email" value="{{old('email')}}" required>
                </div>

                <div class="mb-3">
                    <label for="passwordInput" class="form-label">Password</label>
                    <span class="icon-eye">
                        <i class="fas fa-eye"></i>
                    </span>
                    <input type="password" class="form-control" name="password" id="passwordInput"
                        placeholder="Ingrese su contraseña" required>
                </div>

                <div class="mb-3 form-check">
                    {{-- <input type="checkbox" class="form-check-input" name="remember" id="rememberCheck"> --}}
                    {{-- <label class="form-check-label" for="rememberCheck">Mantener Sesión Iniciada</label> --}}
                </div>

                <div class="bts-group">
                    <a href="#" class="bt boton" >Regresar</a>
                    <button type="submit" class="bt boton">Iniciar Sesión</button>
                </div>
            </form>
        </div>
    </main>

    @include('autenticacion.js')
</body>

</html>
