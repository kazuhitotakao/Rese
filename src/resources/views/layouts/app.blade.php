<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    @if(app('env')=='local')
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @endif
    @if(app('env')=='production')
    <link rel="stylesheet" href="{{ secure_asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('css/common.css') }}">
    @endif
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/f094d395e0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    @yield('css')
    @livewireStyles
</head>

<body>
    <nav class="navbar bg-body-tertiary" aria-label="Light offcanvas navbar">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbarLight" aria-controls="offcanvasNavbarLight" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="header__logo" href="/">
                Rese
            </a>
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbarLight" aria-labelledby="offcanvasNavbarLightLabel">
                <div class="offcanvas-header">
                    <button type="button" class="close" data-bs-dismiss="offcanvas" aria-label="Close">✕</button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                        @if (Auth::check())
                        <li class="nav-item">
                            <a class="nav-link text-primary" href="/">Home</a>
                        </li>
                        <li class="nav-item">
                            <form class="form__logout" action="/logout" method="post">
                                @csrf
                                <button class="nav-link text-primary nav-button">Logout</button>
                            </form>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-primary" href="/my-page">Mypage</a>
                        </li>
                        @can('register')
                        <li class="nav-item">
                            <a class="nav-link text-primary" href="/admin-page">Admin Page</a>
                        </li>
                        @endcan
                        @can('owner')
                        <li class="nav-item">
                            <a class="nav-link text-primary" href="/owner-page">Shop Info</a>
                        </li>
                        @endcan
                        @else
                        <li class="nav-item">
                            <a class="nav-link text-primary" href="/guest">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-primary" href="/register">Registration</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-primary" href="/login">Login</a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <main>
        @yield('content')
    </main>
    @livewireScripts
    <script src="{{ mix('js/app.js') }}"></script>
    @yield('script')
</body>

</html>
