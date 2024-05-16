<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/email.css') }}">
    <title>Document</title>


</head>

<body>

    <div class="card">

        <div class="card__img">

        </div>

        <div class="card__title">
            <h1>Bienvenido</h1>
        </div>

        <div class="card__text">
            <p>
                {{ $contacto['nombre'] . ' ' . $contacto['apellido'] }} has sido registrado al sistema de programación de
                horarios,
                aquí podrás generar los horarios para el centro de servicios financieros.
            </p>
            <br>
            <p>Recuerda que para iniciar sesión las credenciales de usuario y contraseña son tu correo y número de
                documento respectivamente.</p>
        </div>

    </div>

</body>

</html>
