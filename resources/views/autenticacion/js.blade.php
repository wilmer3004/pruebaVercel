<script defer>
    @if (isset($error))
        Swal.fire({
            position: 'bottom-end',
            icon: 'error',
            title: 'Credenciales incorrectas',
            showConfirmButton: false,
            timer: 2500,
            toast: true,
            timerProgressBar: true
        })
    @endif

    @if (session()->has('cerrar'))
        Swal.fire({
            position: 'bottom-end',
            icon: 'success',
            title: 'Has cerrado sesión',
            showConfirmButton: false,
            timer: 2500,
            toast: true,
            timerProgressBar: true
        })
    @endif

    @if (session()->has('message'))
        Swal.fire({
            position: 'bottom-end',
            icon: 'success',
            title: 'Has cerrado sesión',
            showConfirmButton: false,
            timer: 2500,
            toast: true,
            timerProgressBar: true
        })
    @endif

    // *** ICONO MOSTRAR CONTRASEÑA *** //

    const iconEye = document.querySelector(".icon-eye");
    const icon = document.querySelector("i");

    iconEye.addEventListener('click', function() {
        if (this.nextElementSibling.type === 'password') {
            this.nextElementSibling.type = 'text';
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            this.nextElementSibling.type = 'password';
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    })
</script>
