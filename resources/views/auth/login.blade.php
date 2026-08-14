<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión | Sistema de Cobranza</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">

    <style>
        body{
            min-height:100vh;
            margin:0;
            background:linear-gradient(135deg,#1f2933,#111827);
            display:flex;
            align-items:center;
            justify-content:center;
            font-family:"Source Sans Pro",sans-serif;
        }

        .login-wrapper{
            width:100%;
            max-width:420px;
            padding:20px;
        }

        .login-card{
            background:#fff;
            border-radius:16px;
            padding:32px;
            box-shadow:0 20px 45px rgba(0,0,0,.25);
        }

        .logo-circle{
            width:80px;
            height:80px;
            border-radius:50%;
            background:#198754;
            color:#fff;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:34px;
            margin:auto;
            margin-bottom:15px;
        }

        .system-title{
            text-align:center;
            font-size:24px;
            font-weight:bold;
            color:#1f2933;
        }

        .system-subtitle{
            text-align:center;
            color:#6c757d;
            margin-bottom:28px;
        }

        .form-control{
            height:46px;
        }

        .btn-login{
            background:#198754;
            color:#fff;
            border:none;
            height:46px;
            font-weight:bold;
        }

        .btn-login:hover{
            background:#157347;
            color:#fff;
        }

        #togglePassword{
            border-left:none;
        }

        .footer-text{
            text-align:center;
            color:#d1d5db;
            margin-top:20px;
            font-size:13px;
        }
    </style>

</head>

<body>

<div class="login-wrapper">

    <div class="login-card">

        <div class="logo-circle">
            <i class="fas fa-building"></i>
        </div>

        <div class="system-title">
            Sistema de Cobranza
        </div>

        <div class="system-subtitle">
            Acceso Administrativo
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                Correo o contraseña incorrectos.
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">

                <label>Correo electrónico</label>

                <div class="input-group">

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="usuario@empresa.com"
                        value="{{ old('email') }}"
                        required
                        autofocus>

                    <div class="input-group-append">

                        <span class="input-group-text">

                            <i class="fas fa-envelope"></i>

                        </span>

                    </div>

                </div>

            </div>

            <div class="form-group">

                <label>Contraseña</label>

                <div class="input-group">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Ingrese su contraseña"
                        required>

                    <div class="input-group-append">

                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            id="togglePassword">

                            <i class="fas fa-eye" id="iconPassword"></i>

                        </button>

                    </div>

                </div>

            </div>

            <button type="submit" class="btn btn-login btn-block mt-4">

                <i class="fas fa-sign-in-alt mr-2"></i>

                Iniciar sesión

            </button>

        </form>

    </div>

    <div class="footer-text">

        © {{ date('Y') }} Sistema Administrativo de Cobranza

    </div>

</div>

<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

<script>

document.getElementById('togglePassword').addEventListener('click', function(){

    const password=document.getElementById('password');
    const icon=document.getElementById('iconPassword');

    if(password.type==="password"){

        password.type="text";

        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");

    }else{

        password.type="password";

        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");

    }

});

</script>

</body>
</html>