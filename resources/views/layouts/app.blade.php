<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title','Sistema Administrativo de Cobranza')</title>

    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">

    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">

</head>

<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">

    <!-- Navbar -->

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">

        <ul class="navbar-nav">

            <li class="nav-item">

                <a class="nav-link" data-widget="pushmenu" href="#">

                    <i class="fas fa-bars"></i>

                </a>

            </li>

        </ul>

        <ul class="navbar-nav ml-auto">

            <li class="nav-item dropdown">

                <a class="nav-link" data-toggle="dropdown" href="#">

                    <i class="fas fa-user-circle"></i>

                    {{ Auth::user()->name }}

                    <span class="badge badge-info ml-2">

                        {{ Auth::user()->rol }}

                    </span>

                </a>

                <div class="dropdown-menu dropdown-menu-right">

                    <span class="dropdown-item-text">

                        {{ Auth::user()->email }}

                    </span>

                    <div class="dropdown-divider"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button class="dropdown-item">

                            <i class="fas fa-sign-out-alt mr-2"></i>

                            Cerrar sesión

                        </button>

                    </form>

                </div>

            </li>

        </ul>

    </nav>

    <!-- Sidebar -->

    <aside class="main-sidebar sidebar-dark-primary elevation-4">

        <a href="{{ route('dashboard') }}" class="brand-link text-center">

            <span class="brand-text font-weight-bold">

                Sistema Cobranza

            </span>

        </a>

        <div class="sidebar">

            <nav class="mt-2">

                <ul class="nav nav-pills nav-sidebar flex-column"
                    data-widget="treeview"
                    role="menu"
                    data-accordion="false">

                    <!-- DASHBOARD -->

                    <li class="nav-item">

                        <a href="{{ route('dashboard') }}" class="nav-link">

                            <i class="nav-icon fas fa-home"></i>

                            <p>Dashboard</p>

                        </a>

                    </li>

                    {{-- SOLO ADMINISTRADOR --}}

                    @if(auth()->user()->rol == 'ADMIN')

                        <li class="nav-header">

                            CATÁLOGOS

                        </li>

                        <li class="nav-item">

                            <a href="{{ route('inmobiliarias.index') }}" class="nav-link">

                                <i class="nav-icon fas fa-building"></i>

                                <p>Inmobiliarias</p>

                            </a>

                        </li>

                        <li class="nav-item">

                            <a href="{{ route('tipos-inmueble.index') }}" class="nav-link">

                                <i class="nav-icon fas fa-city"></i>

                                <p>Tipos de inmueble</p>

                            </a>

                        </li>

                        <li class="nav-item">

                            <a href="{{ route('inmuebles.index') }}" class="nav-link">

                                <i class="nav-icon fas fa-store"></i>

                                <p>Inmuebles</p>

                            </a>

                        </li>

                        <li class="nav-item">

                            <a href="{{ route('unidades.index') }}" class="nav-link">

                                <i class="nav-icon fas fa-door-open"></i>

                                <p>Unidades</p>

                            </a>

                        </li>

                        <li class="nav-item">

                            <a href="{{ route('clientes.index') }}" class="nav-link">

                                <i class="nav-icon fas fa-users"></i>

                                <p>Clientes</p>

                            </a>

                        </li>

                        <li class="nav-item">

                            <a href="{{ route('conceptos.index') }}" class="nav-link">

                                <i class="nav-icon fas fa-list"></i>

                                <p>Conceptos</p>

                            </a>

                        </li>

                        <li class="nav-item">

                            <a href="{{ route('formas-pago.index') }}" class="nav-link">

                                <i class="nav-icon fas fa-credit-card"></i>

                                <p>Formas de Pago</p>

                            </a>

                        </li>

                        <li class="nav-item">

                            <a href="{{ route('bancos.index') }}" class="nav-link">

                                <i class="nav-icon fas fa-university"></i>

                                <p>Bancos</p>

                            </a>

                        </li>

                        <li class="nav-item">

                            <a href="{{ route('cuentas-bancarias.index') }}" class="nav-link">

                                <i class="nav-icon fas fa-wallet"></i>

                                <p>Cuentas Bancarias</p>

                            </a>

                        </li>

                        {{-- ESTE MÓDULO LO HAREMOS DESPUÉS --}}

                        <li class="nav-item">

                            <a href="{{ route('usuarios.index') }}" class="nav-link">
                                
                                <i class="nav-icon fas fa-users"></i>
    
                                <p>Usuarios</p>

                            </a>

                        </li>

                    @endif

                    <li class="nav-header">

                        OPERACIÓN

                    </li>

                    <li class="nav-item">

                        <a href="{{ route('movimientos.index') }}" class="nav-link">

                            <i class="nav-icon fas fa-cash-register"></i>

                            <p>Movimientos</p>

                        </a>

                    </li>

                    <li class="nav-item">

                        <a href="{{ route('reportes.index') }}" class="nav-link">

                            <i class="nav-icon fas fa-chart-bar"></i>

                            <p>Reportes</p>

                        </a>

                    </li>

                </ul>

            </nav>

        </div>

    </aside>

    <!-- Contenido -->

    <div class="content-wrapper">

        <section class="content-header">

            <div class="container-fluid">

                <h1 class="m-0">

                    @yield('title')

                </h1>

            </div>

        </section>

        <section class="content">

            <div class="container-fluid">

                @yield('content')

            </div>

        </section>

    </div>

    <footer class="main-footer text-center">

        <strong>

            Sistema Administrativo de Cobranza © {{ date('Y') }}

        </strong>

    </footer>

</div>

<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>

<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

@stack('scripts')

</body>

</html>