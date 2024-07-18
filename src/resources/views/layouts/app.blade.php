<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
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
                            <form class="form" action="/logout" method="post">
                                @csrf
                                <button style="width: 100%;" class="nav-link text-primary nav-button">Logout</button>
                            </form>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-primary" href="">Mypage</a>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link text-primary" href="">Home</a>
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
    <script src="{{ mix('js/app.js') }}"></script>
</body>

</html>