<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" ng-app="@yield('ngApp')">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Hope') }} | @yield('page-title')</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap-4.6.1/bootstrap.min.css') }}">
    <!-- FontAwesome -->
    <link href="{{ asset('css/fontawesome/all.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-select-1.13.14/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/ng-table.min.css') }}" rel="stylesheet">
    <!-- AngularJS -->
    <script src="{{ asset('js/angular-1.8.2/angular.min.js') }}"></script>
    <!-- Sweet Alert -->

    <script src="{{ asset('js/sweetalert2.1.2/sweetalert.min.js') }}"></script>
    <!-- MommentsJS  -->
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <!-- Full Calendar  -->
    <script src="{{ asset('js/fullcalendar/index.global.min.js') }}"></script>
    <script src="{{ asset('js/fullcalendar/core/locales/es.global.js') }}"></script>
    
    <!-- Constants JS -->
    <script src="{{ asset('js/constantes.js') }}"></script>
    <!-- Loading  -->
    <link href="{{ asset('css/loading.css') }}" rel="stylesheet">
    @yield('styles')

</head>

<body ng-controller="@yield('ngController')">
    <!-- Modal -->
    <div class="modal fade" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true" id="menuModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-center p-3 text-secondary">
                <div class="modal-body">
                    <app-menu></app-menu>
                </div>
            </div>
        </div>
    </div>
    <!--Header and NavBar-->
    <header>
        <nav class="navbar navbar navbar-dark bg-dark navbar-expand-lg" id="sidenav-main">

            <div class="container-fluid">
                <!-- Toggler -->
                <button id="navbar-toggler" class="navbar-toggler navbar-dark" type="button" data-toggle="modal" data-target="#menuModal" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Brand -->
                <a class="navbar-brand" href="{{ url('home') }}">
                    <img class="" src="{{ asset('images/brand/logo.png') }}" alt="Logo Angular" height="45" width="45"/>
                </a>

                <!-- User Mobile-->
                <ul class="nav align-items-center d-lg-none">
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="media align-items-center">
                                @guest
                                <span class="">
                                    <img class="rounded-circle" alt="Avatar" src="{{ asset('images/brand/logo.png') }}">
                                </span>
                                @endguest
                            </div>
                        </a>
                        @auth
                        <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                            <div class=" dropdown-header noti-title">
                                <h6 class="text-overflow m-0">¡Bienvenido!</h6>
                            </div>
                            <a href="#" class="dropdown-item">
                                <!-- <i class="fas fa-id-card"></i> -->
                                <span class="">{{ Auth::user()->nickname }}</span>
                            </a>
                            <a href="#" class="dropdown-item">
                                <!-- <i class="fas fa-id-card-alt"></i> -->
                                <span class="font-weight-bold">{{ Auth::user()->nickname }}</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item" onclick="abrirPWD()">
                                <!-- <i class="fas fa-lock-open"></i> -->
                                <span>Cambiar Contraseña</span>
                            </a>
                            <a [routerLink]="['/perfil']" class="dropdown-item">
                                <!-- <i class="fas fa-sync-alt"></i> -->
                                <span>Actualizar Datos</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a [routerLink]="['/logout/1']" class="dropdown-item">
                                <span>Salir</span>
                            </a>
                        </div>
                        @endauth
                    </li>
                </ul>

                <!-- <app-menu></app-menu> -->
                @auth
                <div class="collapse navbar-collapse d-flex justify-content-start" id="navbarSupportedContent">
                    <div class="d-none d-lg-block">
                        <app-menu></app-menu>
                    </div>
                </div>
                @endauth

                <div class="collapse navbar-collapse d-flex justify-content-end" id="navbarSupportedContent">
                    <div class="d-none d-lg-block">
                        <ul class="navbar-nav">
                            @guest
                            <li class="nav-item">
                                <a class="nav-link" (click)="setDocTitle( 'Login' )" [routerLink]="['/login']">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" (click)="setDocTitle( 'Registro' )" [routerLink]="['/registro']">Registro</a>
                            </li>
                            @endguest
                            @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="media align-items-center">
                                        <div class="media-body mr-2 d-none d-lg-block">
                                            <span class="mb-0 text-white font-weight-bold user-name">{{ Auth::user()->nickname }}</span>
                                        </div>
                                        <span class="">
                                            <img width="40" height="40" class="rounded-circle" alt="Avatar" src="{{ asset('images/brand/logo.png') }}">
                                        </span>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                                    <div class=" dropdown-header">
                                        <h6 class="text-overflow m-0">¡Bienvenido!</h6>
                                    </div>
                                    <div class="dropdown-item disabled">
                                        <span class="">{{ Auth::user()->name }}</span>
                                    </div>
                                    <div class="dropdown-item disabled">
                                        <span class="">{{ Auth::user()->nickname }}</span>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <a href="" class="dropdown-item" onclick="abrirPWD()">
                                        <!-- <i class="fas fa-lock-open"></i> -->
                                        <span>Cambiar Contraseña</span>
                                    </a>
                                    <a [routerLink]="['/perfil']" class="dropdown-item">
                                        <!-- <i class="fas fa-sync-alt"></i> -->
                                        <span>Actualizar Datos</span>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item">
                                        <!-- <i class="fas fa-sign-out-alt"></i> -->
                                        <span>Salir</span>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                            @endauth
                        </ul>
                    </div>
                </div>

            </div>
        </nav>
    </header>

    <main class="py-4">
        @yield('content')
    </main>

    <script src="{{ asset('js/jquery-3.5.1/jquery.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui-1.13.1/jquery-ui.js') }}"></script>
    <script src="{{ asset('js/bootstrap-4.6.1/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/fontawesome/all.min.js') }}"></script>
    <script src="{{ asset('js/dirPagination.js') }}"></script>
    <script src="{{ asset('js/ng-table.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select-1.13.14/bootstrap-select.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.serializejson.js') }}"></script>
    <script>
        $(function() {
            $('.selectpicker').selectpicker({deselectAllText: 'Quitar todos', selectAllText: 'Elegir todos'});
        });
    </script>
    <!-- Angular File -->
    @yield('ngFile')
</body>

</html>